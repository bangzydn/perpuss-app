<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trskembali extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_transaksi_kembali',
        'pinjam_id',
        'kd_anggota',
        'tg_pinjam',
        'tg_bts_kembali',
        'tg_kembali',
        'kd_koleksi',
        'denda',
        'ket',
        'id_pengguna',
    ];

    protected $table = 'trs_kembali';

    // Relationship to TrsPinjam
    public function trsPinjam()
    {
        return $this->belongsTo(TrsPinjam::class, 'pinjam_id');
    }

    // Direct relationship to Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'kd_anggota', 'kd_anggota');
    }

    // Direct relationship to Koleksi
    public function koleksi()
    {
        return $this->belongsTo(Koleksi::class, 'kd_koleksi', 'kd_koleksi');
    }

    // Relationship to User (pengguna)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
