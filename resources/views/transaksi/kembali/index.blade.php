<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-auto justify-between">
            <h2 class="font-black text-xl text-white dark:text-white">
                {{ __('Transaksi Pengembalian') }}
            </h2>
            @cannot('role-A')
            <div class="mb-2">
                <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Pengembalian Buku
                </button>
            </div>
            @endcannot
        </div>
    </x-slot>

    

    <div class="w-auto mx-auto relative overflow-x-auto shadow-sm sm:rounded-lg mt-2 px-4 py-4">
        <x-message></x-message>
        @cannot('role-Ang')
        <div class="flex justify-between items-center mb-3">
            <!-- Filter -->
            <form method="GET" action="{{ route('trsPinjam.index') }}" class="flex items-center gap-2">
                <label for="filter" class="text-sm font-medium text-white">Filter Buku:</label>
                <select name="kd_koleksi" id="filter" class="rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="">Semua</option>
                    @foreach ($koleksiList as $kol)
                        <option value="{{ $kol }}" @if(request('kd_koleksi') == $kol) selected @endif>{{ $kol }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700">Terapkan</button>
            </form>
            <a href="{{ route('kembali-export', ['no_transaksi_kembali' => request('no_transaksi_kembali')]) }}" type="submit" class="bg-yellow-600 text-white px-6 py-1 rounded-md hover:bg-yellow-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            </a>
            <!-- Optional: Add another button or search on the right side -->
        </div>
        @endcannot
        <table style="width:100%" class="w-full text-sm text-center rtl:text-right text-white dark:text-black rounded-md shadow-xl">
            <thead class="text-md font-extrabold text-white uppercase bg-blue-900 dark:bg-blue-900 dark:text-white">
                <tr>
                    <th scope="col" class="px-4 py-3">
                        No
                    </th>
                    <th scope="col" class="px-4 py-3">
                        No Transaksi Kembali
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Anggota
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Tanggal Pinjam
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Batas Pinjam
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Tanggal Kembali
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Pinjam Buku
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Denda
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Keterangan
                    </th>
                    @cannot('role-A')
                    <th scope="col" class="px-4 py-3">
                        Aksi
                    </th>
                    @endcannot
                </tr>
            </thead>
            {{-- Fixed table body section --}}
            <tbody id="tableBody">
                @php
                $no = 1;
                @endphp
                @forelse ($data as $d)
                    <tr>
                        <td class="px-7 py-3">{{ $d->id }}</td>
                        <td class="px-7 py-3">{{ $d->no_transaksi_kembali }}</td>
                        <td class="px-7 py-3">
                            {{-- Try direct relationship first, then through trsPinjam --}}
                            @if($d->anggota)
                                {{ $d->anggota->nm_anggota }}
                            @elseif($d->trsPinjam && $d->trsPinjam->anggota)
                                {{ $d->trsPinjam->anggota->nm_anggota }}
                            @else
                                {{ $d->kd_anggota ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-7 py-3">
                            {{ $d->tg_pinjam ? \Carbon\Carbon::parse($d->tg_pinjam)->format('d M, Y') : 'N/A' }}
                        </td>
                        <td class="px-7 py-3">
                            {{ $d->tg_bts_kembali ? \Carbon\Carbon::parse($d->tg_bts_kembali)->format('d M, Y') : 'N/A' }}
                        </td>
                        <td class="px-7 py-3">
                            {{ $d->tg_kembali ? \Carbon\Carbon::parse($d->tg_kembali)->format('d M, Y') : 'N/A' }}
                        </td>
                        <td class="px-7 py-3">
                            @if($d->koleksi)
                                {{ $d->koleksi->judul }}
                            @elseif($d->trsPinjam && $d->trsPinjam->koleksi)
                                {{ $d->trsPinjam->koleksi->judul }}
                            @else
                                {{ $d->kd_koleksi ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-7 py-3">{{ $d->denda ?? 0 }}</td>
                        <td class="px-7 py-3">{{ $d->ket ?? '' }}</td>
                        @cannot('role-A')
                        <td>
                            <button
                            onclick="return updateData('{{ $d->id }}','{{ $d->kd_anggota }}'
                            ,'{{ $d->tg_kembali }}','{{ $d->tg_bts_kembali }}'
                            ,'{{ $d->kd_koleksi }}','{{ $d->denda }}','{{ $d->ket }}','{{ route('trsKembali.update', $d->id) }}')"
                            class="bg-gray-600 text-white font-bold px-3 py-1 rounded-lg
                            hover:bg-gray-700 transition">Edit</button>
                        </td>
                        @endcannot
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-7 py-3 text-center">Data Not Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="my-3">
            {{ $data->links() }}
        </div>
        
    </div>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-600 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-4 flex justify-between items-center">
                    <div class="text-lg font-bold text-white">Data trsKembali</div>
                    <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">Tambah trsKembali</button>
                </div>
                <div class="px-6 text-gray-900 dark:text-gray-100">
                    <table id="myDataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Transaksi Kembali</th>
                                <th>Kode Anggota</th>
                                <th>Anggota</th>
                                <th>Tanggal Kembali</th>
                                <th>Batas Kembali</th>
                                <th>Kode Koleksi</th>
                                <th>Judul</th>
                                <th>Denda</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($data as $d)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $d->no_transaksi_kembali }}</td>
                                    <td>{{ $d->kd_anggota }}</td>
                                    <td>{{ $d->anggota->nm_anggota }}</td>
                                    <td>{{ $d->tg_kembali }}</td>
                                    <td>{{ $d->tg_bts_kembali }}</td>
                                    <td>{{ $d->kd_koleksi }}</td>
                                    <td>{{ $d->koleksi->judul }}</td>
                                    <td>{{ $d->denda }}</td>
                                    <td>{{ $d->ket }}</td>
                                    <td>
                                        <button
                                            onclick="return updateData('{{ $d->id }}','{{ $d->kd_anggota }}'
                                            ,'{{ $d->tg_kembali }}','{{ $d->tg_bts_kembali }}','{{ $d->tg_kembali }}'
                                            ,'{{ $d->kd_koleksi }}','{{ $d->denda }}','{{ $d->ket }}','{{ route('trsKembali.update', $d->id) }}')"
                                            class="bg-gray-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-gray-700 transition">Edit</button>
                                        <button
                                            onclick="return deleteData('{{ $d->id }}','{{ $d->no_transaksi_kembali }}', '{{ route('trsKembali.destroy', $d->id) }}')"
                                            class="bg-red-600 text-white font-bold px-3 py-2 rounded-lg hover:bg-red-700 transition">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>Data Not Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- MODAL ADD DATA --}}
    <div id="modal-addData" class="hidden fixed inset-0 flex justify-center items-center m-4">
        <div class="bg-white rounded-lg p-4 w-1/2 shadow-xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-bold mb-4 bg-blue-200 p-2 rounded-xl">Transaksi Kembali</h2>
            <form id="addForm" action="{{ route('trsKembali.store') }}" method="POST" class="w-full">
                @csrf
                <p id="modal-content"></p>
                <div class="text-center">
                    <button type="submit" id="submitAdd" class="bg-blue-800 text-white px-4 py-1 rounded-md">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModalAdd(event)"
                        class="bg-red-500 text-white px-4 py-1 rounded-md">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL UPDATE DATA --}}
    <div id="modal-updateData" class="hidden fixed inset-0 flex justify-center items-center m-4">
        <div class="bg-white rounded-lg p-6 lg:w-4/12 w-full max-h-[90vh] overflow-y-auto shadow-xl">
            <h2 class="text-lg font-bold mb-4 bg-amber-100 p-2 rounded-xl">Update Transaksi Kembali</h2>
            <form id="updateForm" action="" method="post" class="w-full">
                @csrf
                @method('PATCH')
                <p id="modal-content-update"></p>
                <div class="text-center">
                    <button type="submit" id="submitUpdate" class="bg-blue-800 text-white px-4 py-1 rounded-md">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModalUpdate(event)"
                        class="bg-red-500 text-white px-4 py-1 rounded-md">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DELETE DATA --}}
    <div id="modal-deleteData" class="hidden fixed inset-0 flex justify-center items-center m-4 bg-black/30 z-50">
        <div class="bg-white rounded-lg p-6 lg:w-4/12 w-full shadow-xl">
            <h2 class="text-lg font-bold mb-4 text-red-600">Konfirmasi Hapus</h2>
            <form id="deleteForm" action="" method="post" class="w-full">
                @csrf
                @method('DELETE')
                <p id="delete-message" class="mb-4 text-gray-800"></p>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                    <button type="button" onclick="closeModalDelete()"
                        class="bg-gray-400 text-white px-4 py-2 rounded">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT MODAL ADD --}}
    <script>
        function addData() {
            const modalContent = document.getElementById("modal-content");
            modalContent.innerHTML = `
            <div class="grid grid-cols-1 gap-4">
                <div class="form-group">
                    <label for="pinjam_id">Pilih Pinjaman yang Akan Dikembalikan:</label>
                    <select name="pinjam_id" id="pinjam_id" class="form-control w-full p-2 border rounded" required>
                        <option value="">-- Pilih Pinjaman --</option>
                        @foreach($availableLoans as $loan)
                            <option value="{{ $loan->id }}" 
                                    data-anggota="{{ $loan->anggota ? $loan->anggota->nm_anggota : 'N/A' }}"
                                    data-koleksi="{{ $loan->koleksi ? $loan->koleksi->judul : 'N/A' }}"
                                    data-tgl-pinjam="{{ $loan->tg_pinjam }}"
                                    data-tgl-bts="{{ $loan->tgl_bts_kembali }}">
                                    {{ $loan->no_transaksi_pinjam }} - {{ $loan->anggota ? $loan->anggota->kd_anggota : 'N/A' }} 
                                    - {{ $loan->koleksi ? $loan->koleksi->judul : 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Detail pinjaman -->
                <div id="loanDetails" style="display: none;">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label>Nama Anggota:</label>
                            <input type="text" id="nm_anggota" class="form-control w-full p-2 border rounded" readonly>
                        </div>
                        <div class="form-group">
                            <label>Judul Buku:</label>
                            <input type="text" id="judul" class="form-control w-full p-2 border rounded" readonly>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label>Tanggal Pinjam:</label>
                            <input type="date" id="tg_pinjam" class="form-control w-full p-2 border rounded" readonly>
                        </div>
                        <div class="form-group">
                            <label>Batas Kembali:</label>
                            <input type="date" id="tg_bts_kembali" class="form-control w-full p-2 border rounded" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Kembali:</label>
                            <input type="date" name="tg_kembali" id="tg_kembali" class="form-control w-full p-2 border rounded" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
        
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label>Denda (Rp):</label>
                            <input type="number" name="denda" id="denda" class="form-control w-full p-2 border rounded" 
                                   min="0" step="0.01" value="0" required>
                            <small class="text-gray-500">Denda akan dihitung otomatis jika terlambat</small>
                        </div>
                        <div class="form-group">
                            <label>Keterangan:</label>
                            <textarea name="ket" id="ket" class="form-control w-full p-2 border rounded" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            `;
            
            const modal = document.getElementById("modal-addData");
            modal.classList.remove("hidden");
        
            // Get kebijakan values safely
            const dendaPerHari = @json($kebijakan->denda ?? 1000);
            const maxWktPinjam = @json($kebijakan->max_wkt_pjm ?? 2);
        
            // Add event listener for loan selection
            document.getElementById('pinjam_id').addEventListener('change', function() {
                const loanDetails = document.getElementById('loanDetails');
                
                if (this.value) {
                    // Fetch loan details via AJAX
                    fetch(`/kembali/loan-details/${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                alert(data.error);
                                return;
                            }
                            
                            // Fill form fields safely
                            document.getElementById('nm_anggota').value = data.anggota ? data.anggota.nm_anggota : 'N/A';
                            document.getElementById('judul').value = data.koleksi ? data.koleksi.judul : 'N/A';
                            document.getElementById('tg_pinjam').value = data.tg_pinjam || '';
                            document.getElementById('tg_bts_kembali').value = data.tgl_bts_kembali || '';
                            document.getElementById('denda').value = data.calculated_fine || 0;
                            
                            // Show loan details section
                            loanDetails.style.display = 'block';
                            
                            // Show fine info if applicable
                            if (data.calculated_fine > 0) {
                                document.getElementById('ket').value = `Terlambat ${data.days_late} hari`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Gagal mengambil detail pinjaman');
                        });
                } else {
                    loanDetails.style.display = 'none';
                }
            });
        }
        
        function closeModalAdd() {
            const modal = document.getElementById("modal-addData");
            modal.classList.add("hidden");
        }
        </script>

    {{-- SCRIPT MODAL UPDATE --}}
    <script>
        function updateData(id, kd_anggota, tg_pinjam, tg_bts_kembali, tg_kembali, kd_koleksi, denda, ket, routeUrl) {
            const modal = document.getElementById("modal-updateData");
            modal.classList.remove("hidden");

            const modalContent = document.getElementById("modal-content-update");
            modalContent.innerHTML = `
            <div class="lg:mb-5 mb-2 w-full">
                <label for="kd_anggota" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anggota <span class="text-red-500">*</span></label>
                <select id="kd_anggota" class="form-control lg:w-[387px] w-[280px]" name="kd_anggota"data-placeholder="Pilih Anggota">
                    @foreach ($anggota as $a)
                        <option value="{{ $a->kd_anggota }}" ${kd_anggota === "{{ $a->kd_anggota }}" ? 'selected' : ''}>{{ $a->nm_anggota }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:mb-5 mb-2 w-full">
                <label for="tgl_pinjam" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pinjam</label>
                <input type="date" id="tgl_pinjam" name="tgl_pinjam" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500dark:focus:border-blue-500" value="${tg_pinjam}" />
            </div>
            <div class="lg:mb-5 mb-2 w-full">
                <label for="tgl_bts_kembali" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Batas Kembali</label>
                <input type="date" id="tgl_bts_kembali" name="tgl_bts_kembali" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lgfocus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-whitedark:focus:ring-blue-500 dark:focus:border-blue-500" value="${tg_bts_kembali}"/>
            </div>
            <div class="lg:mb-5 mb-2 w-full">
                <label for="tgl_kembali" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Kembali</label>
                <input type="date" id="tgl_kembali" name="tgl_kembali" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500dark:focus:border-blue-500" value="${tg_kembali}" />
            </div>
            <div class="lg:mb-5 mb-2 w-full">
                <label for="kd_koleksi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Koleksi <span class="text-red-500">*</span></label>
                <select id="kd_koleksi" class="form-control lg:w-[387px] w-[280px]" name="kd_koleksi"data-placeholder="Pilih Koleksi">
                    <option value="">Pilih...</option>
                    @foreach ($koleksi as $k)
                        <option value="{{ $k->kd_koleksi }}" ${kd_koleksi === "{{ $k->kd_koleksi }}" ? 'selected' : ''}>{{ $k->judul }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:mb-5 mb-2 w-full">
                    <label for="denda" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Denda</label>
                    <input type="number" id="denda" name="denda" class="bg-gray-500 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="${denda}" />
                </div>
                <div class="lg:mb-5 mb-2 w-full">
                    <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                    <textarea type="textarea" id="ket" name="ket" class="bg-gray-500 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="${ket}"> 
                    </textarea>
                </div>
            
        `;
            const updateForm = document.getElementById("updateForm");
            updateForm.action = routeUrl;

            const maxBatasPinjam = @json($max_wkt_pjm);
            const inputTglPinjam = document.getElementById('tgl_pinjam');
            const inputTglBtsKembali = document.getElementById('tgl_bts_kembali');

            inputTglPinjam.addEventListener('change', function() {
                const tglPinjam = new Date(this.value);
                if (isNaN(tglPinjam)) return;

                tglPinjam.setDate(tglPinjam.getDate() + maxBatasPinjam);

                const yyyy = tglPinjam.getFullYear();
                const mm = String(tglPinjam.getMonth() + 1).padStart(2, '0');
                const dd = String(tglPinjam.getDate()).padStart(2, '0');
                const formattedDate = `${yyyy}-${mm}-${dd}`;

                inputTglBtsKembali.value = formattedDate;
            });
        }

        function closeModalUpdate(event) {
            event.preventDefault();
            document.getElementById("modal-updateData").classList.add("hidden");
        }
    </script>

    {{-- SCRIPT MODAL DELETE --}}
    <script>
        function deleteData(id, nama, routeUrl) {
            const modal = document.getElementById("modal-deleteData");
            modal.classList.remove("hidden");

            const message = document.getElementById("delete-message");
            message.textContent = `Apakah kamu yakin ingin menghapus trsPinjam dengan id "${id}"?`;

            const deleteForm = document.getElementById("deleteForm");
            deleteForm.action = routeUrl;
        }

        function closeModalDelete() {
            document.getElementById("modal-deleteData").classList.add("hidden");
        }
    </script>


</x-app-layout>
