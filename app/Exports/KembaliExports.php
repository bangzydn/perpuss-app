<?php

namespace App\Exports;

use App\Models\Trskembali;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class KembaliExports implements FromCollection, WithHeadings
{
    protected $no_transaksi_kembali;

    public function __construct($no_transaksi_kembali = null)
    {
        $this->no_transaksi_kembali = $no_transaksi_kembali;
    }

    public function collection()
    {
        $query = Trskembali::select(
            "id",
            "no_transaksi_kembali",
            "kd_anggota",
            "tg_pinjam",
            "tg_bts_kembali",
            "tg_kembali",
            "kd_koleksi",
            "denda",
            "ket",
            "id_pengguna",
            "created_at"
        );

        if ($this->no_transaksi_kembali) {
            $query->where('no_transaksi_kembali', $this->no_transaksi_kembali);
        }

        return $query->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'no_transaksi_kembali' => $report->no_transaksi_kembali,
                'kd_anggota' => $report->kd_anggota,
                'tg_pinjam' => $report->tg_pinjam->format('j M Y'),
                'tg_bts_kembali' => $report->tg_bts_kembali->format('j M Y'),
                'tg_kembali' => $report->tg_kembali->format('j M Y'),
                'kd_koleksi' => $report->kd_koleksi,
                'denda' => $report->denda,
                'ket' => $report->ket,
                'id_pengguna' => $report->id_pengguna,
                'created_at' => $report->created_at->format('j M Y'),
            ];
        });
    }


    public function headings(): array
    {
        return [
            "Id",
            "Nomor Transaksi Pinjam",
            "Nama Anggota",
            "Tanggal Pinjam",
            "Tanggal Batas Kembali",
            "Buku Yang Dipinjam",
            "ID Pengguna",
            "Dibuat Pada"
        ];
    }
}
