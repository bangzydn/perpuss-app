<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anggota;
use App\Models\Koleksi;
use App\Models\Kebijakan;
use App\Models\TrsPinjam;
use Illuminate\Http\Request;
use App\Exports\PinjamExports;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\LoanRequestNotification;

class TrsPinjamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = TrsPinjam::orderBy('created_at', 'DESC')->paginate(2);
        $anggota = Anggota::all();
        $koleksi = Koleksi::all();

        $query = TrsPinjam::with(['koleksi'])->latest();

        // Ambil daftar kantor unik untuk dropdown filter
        $koleksiList = TrsPinjam::select('kd_koleksi')->distinct()->pluck('kd_koleksi');

        // Filter hanya jika user memilih kd_koleksi
        if ($request->filled('kd_koleksi')) {
            $query->where('kd_koleksi', $request->kd_koleksi);
        }
        
        $kebijakan = Kebijakan::first();
        $max_wkt_pjm = $kebijakan->max_wkt_pjm;
        return view('transaksi.pinjam.index')->with([
            'koleksiList' => $koleksiList,
            'data' => $data,
            'anggota' => $anggota,
            'koleksi' => $koleksi,
            'max_wkt_pjm' => $max_wkt_pjm,
        ]);
    }

    public function pendingApprovals()
    {
        $pendingLoans = TrsPinjam::with(['anggota', 'koleksi'])
            ->where('status_pinjam', 'PENDING')
            ->latest()
            ->paginate(10);

        return view('transaksi.pinjam.pending-approvals', compact('pendingLoans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $no_transaksi_pinjam = date('YmdHis');
        $data = [
            'no_transaksi_pinjam' => $no_transaksi_pinjam,
            'kd_anggota' => $request->input('kd_anggota'),
            'tg_pinjam' => $request->input('tg_pinjam'),
            'tgl_bts_kembali' => $request->input('tgl_bts_kembali'),
            'kd_koleksi' => $request->input('kd_koleksi'),
            'id_pengguna' => Auth::user()->id,
            'status_pinjam' => 'PENDING', // Status awal adalah PENDING
            'tgl_pengajuan' => now(),
        ];
        TrsPinjam::create($data);
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new LoanRequestNotification(TrsPinjam::latest()->first(), 'new_request'));
        }
        // //MENGUBAH STATUS KOLEKSI
        // $koleksi = Koleksi::where('kd_koleksi', $request->input('kd_koleksi'))->first();
        // if ($koleksi) {
        //     $koleksi->status = 'TIDAK TERSEDIA';
        //     $koleksi->save();
        // }

        //MENAMBAH JUMLAH PINJAM DI ANGGOTA
        $anggota = Anggota::where('kd_anggota', $request->input('kd_anggota'))->first();
        if ($anggota) {
            $anggota->jml_pinjam = $anggota->jml_pinjam + 1;
            $anggota->save();
        }
        return back()->with('success', 'Peminjaman Sudah ditambahkan');
    }

    public function approve($id)
    {
        $pinjam = TrsPinjam::findOrFail($id);

        // Cek apakah status masih PENDING
        if ($pinjam->status_pinjam !== 'PENDING') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya.');
        }

        // Cek apakah koleksi masih tersedia
        $koleksi = Koleksi::where('kd_koleksi', $pinjam->kd_koleksi)->first();
        if (!$koleksi || $koleksi->status !== 'TERSEDIA') {
            return back()->with('error', 'Koleksi tidak tersedia untuk dipinjam.');
        }

        // Update status peminjaman
        $pinjam->update([
            'status_pinjam' => 'DISETUJUI',
            'status_kembali' => 'BELUM_KEMBALI',
            'tgl_disetujui' => now(),
            'admin_approval_id' => Auth::user()->id,
        ]);

        // Mengubah status koleksi menjadi tidak tersedia
        $koleksi->update(['status' => 'TIDAK TERSEDIA']);

        // Menambah jumlah pinjam di anggota
        $anggota = Anggota::where('kd_anggota', $pinjam->kd_anggota)->first();
        if ($anggota) {
            $anggota->increment('jml_pinjam');
        }

        return back()->with('success', 'Peminjaman telah disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $pinjam = TrsPinjam::findOrFail($id);

        // Cek apakah status masih PENDING
        if ($pinjam->status_pinjam !== 'PENDING') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya.');
        }

        // Update status peminjaman
        $pinjam->update([
            'status_pinjam' => 'DITOLAK',
            'tgl_ditolak' => now(),
            'admin_approval_id' => Auth::user()->id,
            'alasan_penolakan' => $request->input('alasan_penolakan'),
        ]);

        return back()->with('success', 'Peminjaman telah ditolak.');
    }

    /**
     * Mark loan as returned (called from TrsKembaliController)
     */
    public function markAsReturned($id)
    {
        $pinjam = TrsPinjam::findOrFail($id);
        $pinjam->update([
            'status_kembali' => 'SUDAH_KEMBALI',
            'tgl_aktual_kembali' => now()
        ]);

        return $pinjam;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $loads = trsPinjam::all();
        $pinjam = TrsPinjam::with(['anggota', 'koleksi', 'user'])->findOrFail($id);
        return view('transaksi.pinjam.show', compact('pinjam', 'loads'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $datas = TrsPinjam::findOrFail($id);
        $kdKoleksi = $datas->kd_koleksi;

        if ($datas->status_pinjam !== 'DISETUJUI') {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang dapat diupdate.');
        }

        //MENGUBAH STATUS KOLEKSI
        if ($kdKoleksi != $request->input('kd_koleksi')) {
            $koleksi = Koleksi::where('kd_koleksi', $kdKoleksi)->first();
            $koleksi->status = 'TERSEDIA';
            $koleksi->save();

            $koleksiBaru = Koleksi::where('kd_koleksi', $request->input('kd_koleksi'))->first();
            $koleksiBaru->status = 'TIDAK TERSEDIA';
            $koleksiBaru->save();
        }

        $data = [
            'kd_anggota' => $request->input('kd_anggota'),
            'tg_pinjam' => $request->input('tgl_pinjam'),
            'tgl_bts_kembali' => $request->input('tgl_bts_kembali'),
            'kd_koleksi' => $request->input('kd_koleksi'),
            'id_pengguna' => Auth::user()->id,
        ];

        $datas->update($data);

        return back()->with('success', 'Data Sudah diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = TrsPinjam::findOrFail($id);
        $kdAnggota = $data->kd_anggota;
        $kdKoleksi = $data->kd_koleksi;

        // Only allow deletion if loan is not approved or already returned
        if ($data->status_pinjam === 'DISETUJUI' && $data->status_kembali === 'BELUM_KEMBALI') {
            return back()->with('error', 'Tidak dapat menghapus peminjaman yang sedang aktif. Silakan proses pengembalian terlebih dahulu.');
        }

        //MENGURANGI JUMLAH PINJAM DI ANGGOTA (only if it was approved)
        if ($data->status_pinjam === 'DISETUJUI') {
            $anggota = Anggota::where('kd_anggota', $kdAnggota)->first();
            if ($anggota) {
                $anggota->jml_pinjam = $anggota->jml_pinjam - 1;
                $anggota->save();
            }
        }

        //MENGUBAH STATUS KOLEKSI (only if it was approved and not returned)
        if ($data->status_pinjam === 'DISETUJUI' && $data->status_kembali === 'BELUM_KEMBALI') {
            $koleksi = Koleksi::where('kd_koleksi', $kdKoleksi)->first();
            if ($koleksi) {
                $koleksi->status = 'TERSEDIA';
                $koleksi->save();
            }
        }

        $data->delete();
        return back()->with('success', 'Data Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $no_transaksi_pinjam = $request->input('no$no_transaksi_pinjam'); // bisa null

        // Tentukan nama file
        $fileName = $no_transaksi_pinjam ? 'PeminjamanBuku_' . str_replace(' ', '_', $no_transaksi_pinjam) . '.xlsx' : 'PeminjamanBuku_All.xlsx';

        return Excel::download(new PinjamExports($no_transaksi_pinjam), $fileName);
    }
}
