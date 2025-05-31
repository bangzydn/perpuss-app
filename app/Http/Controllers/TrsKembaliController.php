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
        // Fix the relationship loading
        $data = Trskembali::with(['trsPinjam.anggota', 'trsPinjam.koleksi', 'koleksi'])
            ->orderBy('created_at', 'DESC')
            ->paginate(2);

        // Get approved loans that haven't been returned
        $availableLoans = TrsPinjam::with(['anggota', 'koleksi'])
            ->where('status_pinjam', 'DISETUJUI')
            ->where('status_kembali', 'BELUM_KEMBALI')
            ->get();

        $anggota = Anggota::all();
        $koleksi = Koleksi::all();

        // Fix the query for filtering
        $query = Trskembali::with(['trsPinjam.anggota', 'trsPinjam.koleksi', 'koleksi'])->latest();

        // Get unique collection codes for filter dropdown
        $koleksiList = Trskembali::select('kd_koleksi')->distinct()->pluck('kd_koleksi');

        // Apply filter if selected
        if ($request->filled('kd_koleksi')) {
            $query->where('kd_koleksi', $request->kd_koleksi);
        }

        $data = $query->paginate(10); // Use the filtered query

        $kebijakan = Kebijakan::first();
        $max_wkt_pjm = $kebijakan->max_wkt_pjm ?? 7; // Default value if null

        return view('transaksi.kembali.index')->with([
            'koleksiList' => $koleksiList,
            'data' => $data,
            'availableLoans' => $availableLoans,
            'anggota' => $anggota,
            'koleksi' => $koleksi,
            'max_wkt_pjm' => $max_wkt_pjm,
            'kebijakan' => $kebijakan, // Pass kebijakan to view
        ]);
    }

    /**
     * Get loan details by loan ID for return form
     */
    public function getLoanDetails($loanId)
    {
        $loan = TrsPinjam::with(['anggota', 'koleksi'])
            ->where('id', $loanId)
            ->where('status_pinjam', 'DISETUJUI')
            ->where('status_kembali', 'BELUM_KEMBALI')
            ->first();

        if (!$loan) {
            return response()->json(['error' => 'Loan not found or already returned'], 404);
        }

        // Calculate potential fine
        $today = now();
        $dueDate = \Carbon\Carbon::parse($loan->tgl_bts_kembali);
        $daysLate = $today->diffInDays($dueDate, false);
        $fine = 0;

        if ($daysLate < 0) { // If overdue
            $kebijakan = Kebijakan::first();
            $finePerDay = $kebijakan->denda_per_hari ?? 1000; // Default 1000 if not set
            $fine = abs($daysLate) * $finePerDay;
        }

        $loan->calculated_fine = $fine;
        $loan->days_late = abs($daysLate);

        return response()->json($loan);
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
        $request->validate([
            'pinjam_id' => 'required|exists:trs_pinjams,id',
            'tg_kembali' => 'required|date',
            'denda' => 'required|numeric|min:0',
            'ket' => 'nullable|string'
        ]);

        // Get the loan record
        $pinjam = TrsPinjam::findOrFail($request->input('pinjam_id'));

        // Check if loan is eligible for return
        if ($pinjam->status_pinjam !== 'DISETUJUI' || $pinjam->status_kembali !== 'BELUM_KEMBALI') {
            return back()->with('error', 'Peminjaman tidak dapat dikembalikan.');
        }

        $no_transaksi_kembali = date('YmdHis');

        $data = [
            'no_transaksi_kembali' => $no_transaksi_kembali,
            'pinjam_id' => $pinjam->id, // Reference to loan
            'kd_anggota' => $pinjam->kd_anggota,
            'tg_pinjam' => $pinjam->tg_pinjam,
            'tg_bts_kembali' => $pinjam->tgl_bts_kembali,
            'tg_kembali' => $request->input('tg_kembali'),
            'kd_koleksi' => $pinjam->kd_koleksi,
            'denda' => $request->input('denda'),
            'ket' => $request->input('ket'),
            'id_pengguna' => Auth::user()->id,
        ];

        TrsKembali::create($data);

        // Update loan status to returned
        $pinjam->update([
            'status_kembali' => 'SUDAH_KEMBALI',
            'tgl_aktual_kembali' => $request->input('tg_kembali')
        ]);

        // Change collection status back to available
        $koleksi = Koleksi::where('kd_koleksi', $pinjam->kd_koleksi)->first();
        if ($koleksi) {
            $koleksi->status = 'TERSEDIA';
            $koleksi->save();
        }

        // Don't increment jml_pinjam here - it was already incremented when approved
        // The jml_pinjam should represent total loans taken, not current active loans

        return back()->with('success', 'Pengembalian berhasil diproses');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $return = TrsKembali::with(['trsPinjam.anggota', 'trsPinjam.koleksi', 'user'])->findOrFail($id);
        return view('transaksi.kembali.show', compact('return'));
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
        $return = TrsKembali::findOrFail($id);

        $data = [
            'tg_kembali' => $request->input('tg_kembali'),
            'denda' => $request->input('denda'),
            'ket' => $request->input('ket')
        ];

        $return->update($data);

        // Update the actual return date in loan record
        $return->trsPinjam->update([
            'tgl_aktual_kembali' => $request->input('tg_kembali')
        ]);

        return back()->with('message_update', 'Data pengembalian berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $return = TrsKembali::findOrFail($id);
        $pinjam = $return->trsPinjam;

        // Revert loan status back to not returned
        if ($pinjam) {
            $pinjam->update([
                'status_kembali' => 'BELUM_KEMBALI',
                'tgl_aktual_kembali' => null
            ]);

            // Change collection status back to not available
            $koleksi = Koleksi::where('kd_koleksi', $pinjam->kd_koleksi)->first();
            if ($koleksi) {
                $koleksi->status = 'TIDAK TERSEDIA';
                $koleksi->save();
            }
        }

        $return->delete();
        return back()->with('success', 'Data pengembalian berhasil dihapus');
    }

    public function export(Request $request)
    {
        $no_transaksi_kembali = $request->input('no_transaksi_kembali'); // Fixed typo

        // Tentukan nama file
        $fileName = $no_transaksi_kembali ? 'PengembalianBuku_' . str_replace(' ', '_', $no_transaksi_kembali) . '.xlsx' : 'PengembalianBuku_All.xlsx';

        return Excel::download(new KembaliExports($no_transaksi_kembali), $fileName);
    }
}
