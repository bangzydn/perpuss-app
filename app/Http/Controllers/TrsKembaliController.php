<?php

namespace App\Http\Controllers;



use App\Models\Anggota;
use App\Models\Koleksi;
use App\Models\Kebijakan;
use App\Models\TrsPinjam;
use App\Models\Trskembali;
use Illuminate\Http\Request;
use App\Exports\KembaliExports;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TrsKembaliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Trskembali::latest()->paginate(2);
        $pinjam = TrsPinjam::all();
        $anggota = Anggota::all();
        $koleksi = Koleksi::all();

        $query = Trskembali::with(['koleksi'])->latest();

        // Ambil daftar kantor unik untuk dropdown filter
        $koleksiList = Trskembali::select('kd_koleksi')->distinct()->pluck('kd_koleksi');

        // Filter hanya jika user memilih kd_koleksi
        if ($request->filled('kd_koleksi')) {
            $query->where('kd_koleksi', $request->kd_koleksi);
        }

        $kebijakan = Kebijakan::first();
        $max_wkt_pjm = $kebijakan->max_wkt_pjm;

        return view('transaksi.kembali.index')->with([
            'koleksiList' => $koleksiList,
            'data' => $data,
            'pinjam' => $pinjam,
            'anggota' => $anggota,
            'koleksi' => $koleksi,
            'max_wkt_pjm' => $max_wkt_pjm,
        ]);
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
        $no_transaksi_kembali = date('YmdHis');

        $data = [
            'no_transaksi_kembali' => $no_transaksi_kembali,
            'kd_anggota' => $request->input('kd_anggota'),
            'tg_pinjam' => $request->input('tg_pinjam'),
            'tg_bts_kembali' => $request->input('tg_bts_kembali'),
            'tg_kembali' => $request->input('tg_kembali'),
            'kd_koleksi' => $request->input('kd_koleksi'),
            'denda' => $request->input('denda'),
            'ket' => $request->input('ket'),
            'id_pengguna' => Auth::user()->id,
        ];
        TrsKembali::create($data);

        //MENGUBAH STATUS KOLEKSI
        $koleksi = Koleksi::where('kd_koleksi', $request->input('kd_koleksi'))->first();
        if ($koleksi) {
            $koleksi->status = 'TERSEDIA';
            $koleksi->save();
        }

        //MENAMBAH JUMLAH PINJAM DI ANGGOTA
        $anggota = Anggota::where('kd_anggota', $request->input('kd_anggota'))->first();
        if ($anggota) {
            $anggota->jml_pinjam = $anggota->jml_pinjam + 1;
            $anggota->save();
        }
        return back()->with('success', 'Transaksi Sudah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $datas = TrsKembali::findOrFail($id);
        $kdKoleksi = $datas->kd_koleksi;

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
            'tg_bts_kembali' => $request->input('tgl_bts_kembali'),
            'tg_kembali' => $request->input('tgl_kembali'),
            'kd_koleksi' => $request->input('kd_koleksi'),
            'denda' => $request->input('denda'),
            'ket' => $request->input('ket')
        ];

        $datas->update($data);

        return back()->with('message_update', 'Data Sudah diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Trskembali::findOrFail($id);
        $kdAnggota = $data->kd_anggota;
        $kdKoleksi = $data->kd_koleksi;

        //MENGURANGI JUMLAH PINJAM DI ANGGOTA
        $anggota = Anggota::where('kd_anggota', $kdAnggota)->first();
        if ($anggota) {
            $anggota->jml_pinjam = $anggota->jml_pinjam - 1;
            $anggota->save();
        }

        //MENGUBAH STATUS KOLEKSI
        $koleksi = Koleksi::where('kd_koleksi', $kdKoleksi)->first();
        if ($koleksi) {
            $koleksi->status = 'TERSEDIA';
            $koleksi->save();
        }
        $data->delete();
        return back()->with('success', 'Data Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $no_transaksi_kembali = $request->input('no$no_transaksi_kembali'); // bisa null

        // Tentukan nama file
        $fileName = $no_transaksi_kembali ? 'PengembalianBuku_' . str_replace(' ', '_', $no_transaksi_kembali) . '.xlsx' : 'PeminjamanBuku_All.xlsx';

        return Excel::download(new KembaliExports($no_transaksi_kembali), $fileName);
    }
}
