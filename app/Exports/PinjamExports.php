<?php

namespace App\Exports;


use App\Models\TrsPinjam;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PinjamExports implements FromCollection, WithHeadings
{
    protected $no_transaksi_pinjam;

    public function __construct($no_transaksi_pinjam = null)
    {
        $this->no_transaksi_pinjam = $no_transaksi_pinjam;
    }

    public function collection()
    {
        $query = TrsPinjam::select(
            "id", "no_transaksi_pinjam", "kd_anggota",
            "tg_pinjam",
            "tgl_bts_kembali",
            "kd_koleksi", "id_pengguna", "created_at"
        );

        if ($this->no_transaksi_pinjam) {
            $query->where('no_transaksi_pinjam', $this->no_transaksi_pinjam);
        }

        return $query->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'no_transaksi_pinjam' => $report->no_transaksi_pinjam,
                'kd_anggota' => $report->kd_anggota,
                'tg_pinjam' => $report->tg_pinjam,
                'tgl_bts_kembali' => $report->tgl_bts_kembali,
                'kd_koleksi' => $report->kd_koleksi,
                'id_pengguna' => $report->id_pengguna,
                'created_at' => $report->created_at->format('j M Y'),
            ];
        });
    }

    
    public function headings(): array
    {
        return ["Id", "Nomor Transaksi Pinjam","Nama Anggota",
        "Tanggal Pinjam","Tanggal Batas Kembali","Buku Yang Dipinjam"
        ,"ID Pengguna", "Dibuat Pada"];
    }
}