<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-auto justify-between">
            <h2 class="font-black text-xl text-white dark:text-white">
                {{ __('Pending Approval') }}
            </h2>
            {{-- @cannot('role-A')
            <div class="mb-2">
                <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Pinjam Buku
                </button>
            </div>
            @endcannot --}}
        </div>
    </x-slot>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Peminjaman Menunggu Persetujuan</h3>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
    
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
    
                        @if($pendingLoans->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>No. Transaksi</th>
                                            <th>Anggota</th>
                                            <th>Koleksi</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Batas Kembali</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingLoans as $key => $loan)
                                        <tr>
                                            <td>{{ $pendingLoans->firstItem() + $key }}</td>
                                            <td>{{ $loan->no_transaksi_pinjam }}</td>
                                            <td>
                                                <strong>{{ $loan->anggota->nama_anggota ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $loan->kd_anggota }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $loan->koleksi->judul ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $loan->kd_koleksi }}</small>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($loan->tg_pinjam)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($loan->tgl_bts_kembali)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($loan->tgl_pengajuan)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-warning">{{ $loan->status_pinjam }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- Tombol Setujui -->
                                                    <form action="{{ route('trsPinjam.approve', $loan->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" 
                                                                onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')">
                                                            <i class="fas fa-check"></i> Setujui
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Tombol Tolak -->
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal{{ $loan->id }}">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                    
                                                    <!-- Tombol Detail -->
                                                    <a href="{{ route('trsPinjam.show', $loan->id) }}" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
    
                                        <!-- Modal untuk penolakan -->
                                        <div class="modal fade" id="rejectModal{{ $loan->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Peminjaman</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('trsPinjam.reject', $loan->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Alasan Penolakan</label>
                                                                <textarea name="alasan_penolakan" class="form-control" rows="4" 
                                                                          placeholder="Masukkan alasan penolakan..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
    
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $pendingLoans->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada peminjaman yang menunggu persetujuan</h5>
                                <p class="text-muted">Semua peminjaman sudah diproses atau belum ada pengajuan baru.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    // Auto refresh halaman setiap 30 detik untuk update real-time
    setInterval(function(){
        if (document.hidden === false) {
            location.reload();
        }
    }, 30000);
</script>
</x-app-layout>