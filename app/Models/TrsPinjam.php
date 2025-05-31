<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrsPinjam extends Model
{
    use HasFactory;

    protected $table = 'trs_pinjam';
    protected $fillable = [
        'no_transaksi_pinjam',
        'kd_anggota',
        'tg_pinjam',
        'tgl_bts_kembali',
        'kd_koleksi',
        'id_pengguna',
        'status_pinjam',
        'status_kembali',  // Add this
        'tgl_aktual_kembali',  // Add this
        'tgl_pengajuan',
        'tgl_disetujui',
        'tgl_ditolak',
        'admin_approval_id',
        'alasan_penolakan'
    ];

    protected $casts = [
        'tg_pinjam' => 'date',
        'tgl_bts_kembali' => 'date',
        'tgl_pengajuan' => 'datetime',
        'tgl_disetujui' => 'datetime',
        'tgl_ditolak' => 'datetime',
    ];

    /**
     * Relationship dengan model Anggota
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'kd_anggota', 'kd_anggota');
    }

    /**
     * Relationship dengan model Koleksi
     */
    public function koleksi()
    {
        return $this->belongsTo(Koleksi::class, 'kd_koleksi', 'kd_koleksi');
    }

    /**
     * Relationship dengan User yang membuat transaksi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    /**
     * Relationship dengan Admin yang menyetujui/menolak
     */
    public function adminApproval()
    {
        return $this->belongsTo(User::class, 'admin_approval_id');
    }

    /**
     * Scope untuk peminjaman yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status_pinjam', 'PENDING');
    }

    /**
     * Scope untuk peminjaman yang disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status_pinjam', 'DISETUJUI');
    }

    /**
     * Scope untuk peminjaman yang ditolak
     */
    public function scopeRejected($query)
    {
        return $query->where('status_pinjam', 'DITOLAK');
    }

    /**
     * Accessor untuk status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'PENDING' => 'warning',
            'DISETUJUI' => 'success',
            'DITOLAK' => 'danger'
        ];

        return $colors[$this->status_pinjam] ?? 'secondary';
    }

    /**
     * Accessor untuk format tanggal Indonesia
     */
    public function getTanggalPinjamFormatAttribute()
    {
        return $this->tg_pinjam->format('d/m/Y');
    }

    public function getTanggalBatasKembaliFormatAttribute()
    {
        return $this->tgl_bts_kembali->format('d/m/Y');
    }

    /**
     * Check apakah peminjaman masih bisa diedit
     */
    public function canBeUpdated()
    {
        return $this->status_pinjam === 'DISETUJUI';
    }

    /**
     * Check apakah peminjaman bisa diapprove/reject
     */
    public function canBeProcessed()
    {
        return $this->status_pinjam === 'PENDING';
    }

    public function trsKembali()
    {
        return $this->hasOne(TrsKembali::class, 'pinjam_id');
    }

    // Add scope for active loans
    public function scopeActiveLoan($query)
    {
        return $query->where('status_pinjam', 'DISETUJUI')
            ->where('status_kembali', 'BELUM_KEMBALI');
    }
}
