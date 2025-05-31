<x-app-layout>
<x-slot name="header">
    <div class="flex flex-auto justify-between">
        <h2 class="font-black text-xl text-white dark:text-white">
            {{ __('Daftar Peminjaman Menunggu Persetujuan') }}
        </h2>
        
    </div>
</x-slot>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-book-open"></i> Detail Peminjaman
                        </h4>
                        <div>
                            <a href="{{ route('trsPinjam.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            @can('role', 'admin')
                                <a href="{{ route('trsPinjam.pending-approvals') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-clock"></i> Daftar Persetujuan
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Informasi Utama -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Informasi Peminjaman
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>No. Transaksi:</strong></td>
                                            <td>{{ $pinjam->no_transaksi_pinjam }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Pinjam:</strong></td>
                                            <td>{{ $pinjam->tanggal_pinjam_format }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Batas Kembali:</strong></td>
                                            <td>
                                                {{ $pinjam->tanggal_batas_kembali_format }}
                                                @php
                                                    $today = now();
                                                    $batasKembali = \Carbon\Carbon::parse($pinjam->tgl_bts_kembali);
                                                    $selisihHari = $today->diffInDays($batasKembali, false);
                                                @endphp
                                                @if($pinjam->status_pinjam === 'DISETUJUI')
                                                    @if($selisihHari < 0)
                                                        <span class="badge bg-danger">Terlambat {{ abs($selisihHari) }} hari</span>
                                                    @elseif($selisihHari <= 3)
                                                        <span class="badge bg-warning">{{ $selisihHari }} hari lagi</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $selisihHari }} hari lagi</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $pinjam->status_badge }} fs-6">
                                                    {{ $pinjam->status_pinjam }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Tanggal Pengajuan:</strong></td>
                                            <td>
                                                @if($pinjam->tgl_pengajuan)
                                                    {{ $pinjam->tgl_pengajuan->format('d/m/Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diproses Tanggal:</strong></td>
                                            <td>
                                                @if($pinjam->tgl_disetujui)
                                                    {{ $pinjam->tgl_disetujui->format('d/m/Y H:i:s') }}
                                                @elseif($pinjam->tgl_ditolak)
                                                    {{ $pinjam->tgl_ditolak->format('d/m/Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">Belum diproses</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diproses oleh:</strong></td>
                                            <td>
                                                @if($pinjam->adminApproval)
                                                    {{ $pinjam->adminApproval->name }}
                                                    <br><small class="text-muted">{{ $pinjam->adminApproval->email }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dibuat oleh:</strong></td>
                                            <td>
                                                @if($pinjam->pengguna)
                                                    {{ $pinjam->pengguna->name }}
                                                    <br><small class="text-muted">{{ $pinjam->pengguna->email }}</small>
                                                @else
                                                    <span class="text-muted">System</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Alasan Penolakan -->
                            @if($pinjam->status_pinjam === 'DITOLAK' && $pinjam->alasan_penolakan)
                                <div class="alert alert-danger mt-3">
                                    <h6><i class="fas fa-times-circle"></i> Alasan Penolakan:</h6>
                                    <p class="mb-0">{{ $pinjam->alasan_penolakan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status & Aksi -->
                <div class="col-lg-4">
                    <!-- Status Timeline -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-timeline"></i> Timeline Status
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <!-- Pengajuan -->
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Pengajuan Dibuat</h6>
                                        <p class="timeline-text">
                                            @if($pinjam->tgl_pengajuan)
                                                {{ $pinjam->tgl_pengajuan->format('d M Y, H:i') }}
                                            @else
                                                {{ $pinjam->created_at->format('d M Y, H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Status Saat Ini -->
                                @if($pinjam->status_pinjam === 'PENDING')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Menunggu Persetujuan</h6>
                                            <p class="timeline-text">Admin sedang meninjau pengajuan</p>
                                        </div>
                                    </div>
                                @elseif($pinjam->status_pinjam === 'DISETUJUI')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Disetujui</h6>
                                            <p class="timeline-text">
                                                {{ $pinjam->tgl_disetujui->format('d M Y, H:i') }}
                                                @if($pinjam->adminApproval)
                                                    <br><small>oleh {{ $pinjam->adminApproval->name }}</small>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @elseif($pinjam->status_pinjam === 'DITOLAK')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Ditolak</h6>
                                            <p class="timeline-text">
                                                {{ $pinjam->tgl_ditolak->format('d M Y, H:i') }}
                                                @if($pinjam->adminApproval)
                                                    <br><small>oleh {{ $pinjam->adminApproval->name }}</small>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Aksi Admin -->
                    @can('role', 'admin')
                        @if($pinjam->canBeProcessed())
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-user-shield"></i> Aksi Admin
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <!-- Tombol Setujui -->
                                        <form action="{{ route('trsPinjam.approve', $pinjam->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')">
                                                <i class="fas fa-check"></i> Setujui Peminjaman
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <button type="button" class="btn btn-danger w-100" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            <i class="fas fa-times"></i> Tolak Peminjaman
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>

            <!-- Informasi Anggota & Koleksi -->
            <div class="row mt-4">
                <!-- Data Anggota -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user"></i> Data Anggota
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($pinjam->anggota)
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center">
                                            <div class="avatar-lg mb-2">
                                                <i class="fas fa-user-circle fa-4x text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td><strong>Kode:</strong></td>
                                                <td>{{ $pinjam->anggota->kd_anggota }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nama:</strong></td>
                                                <td>{{ $pinjam->anggota->nama_anggota }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $pinjam->anggota->email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Telepon:</strong></td>
                                                <td>{{ $pinjam->anggota->no_telp ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total Pinjam:</strong></td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $pinjam->anggota->jml_pinjam }} buku</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3"></i>
                                    <p>Data anggota tidak ditemukan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Data Koleksi -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-book"></i> Data Koleksi
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($pinjam->koleksi)
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center">
                                            @if($pinjam->koleksi->cover_image)
                                                <img src="{{ asset('storage/' . $pinjam->koleksi->cover_image) }}" 
                                                     class="img-fluid rounded shadow-sm" alt="Cover Buku">
                                            @else
                                                <div class="book-placeholder bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 120px;">
                                                    <i class="fas fa-book fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td><strong>Kode:</strong></td>
                                                <td>{{ $pinjam->koleksi->kd_koleksi }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Judul:</strong></td>
                                                <td>{{ $pinjam->koleksi->judul }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pengarang:</strong></td>
                                                <td>{{ $pinjam->koleksi->pengarang ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Penerbit:</strong></td>
                                                <td>{{ $pinjam->koleksi->penerbit ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    @if($pinjam->koleksi->status === 'TERSEDIA')
                                                        <span class="badge bg-success">{{ $pinjam->koleksi->status }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $pinjam->koleksi->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-book-dead fa-3x mb-3"></i>
                                    <p>Data koleksi tidak ditemukan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@can('role-A')
    @if($pinjam->canBeProcessed())
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-times-circle"></i> Tolak Peminjaman
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('trsPinjam.reject', $pinjam->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Perhatian!</strong> Peminjaman yang ditolak tidak dapat diubah kembali.
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="alasan_penolakan" class="form-control" rows="4" 
                                          placeholder="Masukkan alasan penolakan yang jelas untuk anggota..." required></textarea>
                                <div class="form-text">
                                    Berikan alasan yang jelas agar anggota dapat memahami kenapa peminjaman ditolak.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-ban"></i> Tolak Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endcan

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto refresh jika status masih PENDING (setiap 30 detik)
        @if($pinjam->status_pinjam === 'PENDING')
            setInterval(function() {
                if (document.hidden === false) {
                    location.reload();
                }
            }, 30000);
        @endif
        
        // Konfirmasi sebelum approve
        const approveForm = document.querySelector('form[action*="approve"]');
        if (approveForm) {
            approveForm.addEventListener('submit', function(e) {
                if (!confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?\n\nSetelah disetujui:\n- Status koleksi akan berubah menjadi TIDAK TERSEDIA\n- Jumlah pinjaman anggota akan bertambah\n- Aksi ini tidak dapat dibatalkan')) {
                    e.preventDefault();
                }
            });
        }
    });
    </script>

</x-app-layout>
