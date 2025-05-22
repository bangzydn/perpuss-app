<?php

namespace App\Http\Controllers;


use App\Models\Anggota;
use App\Models\Koleksi;
use App\Models\Kebijakan;
use App\Models\Trskembali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrsKembaliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Trskembali::all();
        $anggota = Anggota::all();
        $koleksi = Koleksi::all();

        $kebijakan = Kebijakan::first();
        $max_wkt_pjm = $kebijakan->max_wkt_pjm;
        return view('transaksi.kembali.index')->with([
            'data' => $data,
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
            'tg_pinjam' => $request->input('tgl_pinjam'),
            'tg_bts_kembali' => $request->input('tgl_bts_kembali'),
            'tg_kembali' => $request->input('tgl_kembali'),
            'kd_koleksi' => $request->input('kd_koleksi'),
            'denda' => $request->input('denda'),
            'ket' => $request->input('ket'),
            'id_pengguna' => Auth::user()->id,
        ];
        TrsKembali::create($data);

        //MENGUBAH STATUS KOLEKSI
        $koleksi = Koleksi::where('kd_koleksi', $request->input('kd_koleksi'))->first();
        if ($koleksi) {
            $koleksi->status = 'TIDAK TERSEDIA';
            $koleksi->save();
        }

        //MENAMBAH JUMLAH PINJAM DI ANGGOTA
        $anggota = Anggota::where('kd_anggota', $request->input('kd_anggota'))->first();
        if ($anggota) {
            $anggota->jml_pinjam = $anggota->jml_pinjam + 1;
            $anggota->save();
        }
        return back()->with('message_insert', 'Data Sudah ditambahkan');
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
        return back()->with('message_delete', 'Data Berhasil dihapus');
    }
    }

