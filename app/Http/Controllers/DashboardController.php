<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Anggota;
use App\Models\Koleksi;
use App\Models\TrsPinjam;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jumlahAnggotaHariIni = Anggota::whereDate('created_at', Carbon::today())->count();

        // Total anggota keseluruhan
        $totalAnggota = Anggota::count();

        // Total koleksi/buku
        $totalBuku = Koleksi::count();

        // Total transaksi pinjam
        $totalPinjam = TrsPinjam::count();

        return view('dashboard', [
            'jumlahAnggotaHariIni' => $jumlahAnggotaHariIni,
            'totalAnggota' => $totalAnggota,
            'totalBuku' => $totalBuku,
            'totalPinjam' => $totalPinjam,
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
