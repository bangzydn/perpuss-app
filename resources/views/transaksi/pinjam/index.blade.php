<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-auto justify-between">
            <h2 class="font-black text-xl text-white dark:text-white">
                {{ __('Transaksi Pinjam') }}
            </h2>
            @cannot('role-A')
            <div class="mb-2">
                <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Pinjam Buku
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
            <a href="{{ route('pinjam-export', ['no_transaksi_pinjam' => request('no_transaksi_pinjam')]) }}" type="submit" class="bg-yellow-600 text-white px-6 py-1 rounded-md hover:bg-yellow-700">
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
                        No Transaksi Pinjam
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
                        Status Peminjaman
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Tanggal Pengajuan
                    </th>
                    @cannot('role-A')
                    <th scope="col" class="px-4 py-3">
                        Aksi
                    </th>
                    @endcannot
                </tr>
            </thead>
            <tbody id="tableBody">
                @php
                $no = 1;
                @endphp
                @forelse ($data as $d)
                    <tr >
                        <td class="px-7 py-3">{{ $d->id }}</td>
                        <td class="px-7 py-3">{{ $d->no_transaksi_pinjam }}</td>
                        <td class="px-7 py-3">{{ $d->kd_anggota }}</td>
                        <td class="px-7 py-3">
                            {{\Carbon\Carbon::parse($d->tg_pinjam)->format('d M, Y') }}
                        </td>
                        <td class="px-7 py-3">
                            {{\Carbon\Carbon::parse($d->tgl_bts_kembali)->format('d M, Y') }}
                        </td>
                        <td>
                            <span class="px-7 py-3 badge bg-{{ $d->status_badge }}">
                                {{ $d->status_pinjam }}
                            </span>
                            @if($d->status_pinjam === 'DITOLAK' && $d->alasan_penolakan)
                                <br><small class="text-danger">{{ $d->alasan_penolakan }}</small>
                            @endif
                        </td>
                        <td class="px-7 py-3">
                            {{\Carbon\Carbon::parse($d->tgl_pengajuan)->format('d M, Y') }}
                        </td>
                        @cannot('role-A')
                        <td>
                            <button
                            onclick="return updateData('{{ $d->id }}','{{ $d->kd_anggota }}'
                            ,'{{ $d->tg_pinjam }}','{{ $d->tgl_bts_kembali }}'
                            ,'{{ $d->kd_koleksi }}','{{ route('trsPinjam.update', $d->id) }}')" 
                            class="bg-gray-600 text-white font-bold px-3 py-1 rounded-lg
                             hover:bg-gray-700 transition">Edit</button>
                            {{-- Tombol Detail --}}
                            <a href="{{ route('trsPinjam.show', $d->id) }}" 
                                class="bg-green-600 text-white font-bold px-3 py-1 rounded-lg
                             hover:bg-green-700 transition">
                                Detail
                            </a>
                            {{-- <button
                            onclick="return deleteData('{{ $d->id }}','{{ $d->no_transaksi_pinjam }}', '{{ route('trsPinjam.destroy', $d->id) }}')"
                            class="bg-red-600 text-white font-bold px-3 py-1 rounded-lg hover:bg-red-700 transition">Hapus</button> --}}
                        </td>
                        @endcannot
                    </tr>
                    <!-- forelse empty row mimic -->
                    <tr class="empty-row" style="display:none;">
                    <td colspan="3">No matching records found.</td>
                    </tr>
                @empty
                    <tr>
                        <td>Data Not Found</td>
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-4 flex justify-between items-center">
                    <div>Data trsPinjam</div>
                    <button onclick="return addData()">Tambah trsPinjam</button>
                </div>
                <div class="px-6 text-gray-900 dark:text-gray-100">
                    <table id="myDataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Transaksi Pinjam</th>
                                <th>Kode Anggota</th>
                                <th>Anggota</th>
                                <th>Tanggal Pinjam</th>
                                <th>Batas Pinjam</th>
                                <th>Kode Koleksi</th>
                                <th>Judul</th>
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
                                    <td>{{ $d->no_transaksi_pinjam }}</td>
                                    <td>{{ $d->kd_anggota }}</td>
                                    <td>{{ $d->anggota->nm_anggota }}</td>
                                    <td>{{ $d->tg_pinjam }}</td>
                                    <td>{{ $d->tgl_bts_kembali }}</td>
                                    <td>{{ $d->kd_koleksi }}</td>
                                    <td>{{ $d->koleksi->judul }}</td>
                                    <td>
                                        <button
                                            onclick="return updateData('{{ $d->id }}','{{ $d->kd_anggota }}'
                                            ,'{{ $d->tg_pinjam }}','{{ $d->tgl_bts_kembali }}'
                                            ,'{{ $d->kd_koleksi }}','{{ route('trsPinjam.update', $d->id) }}')">Edit</button>
                                        <button
                                            onclick="return deleteData('{{ $d->id }}','{{ $d->no_transaksi_pinjam }}', '{{ route('trsPinjam.destroy', $d->id) }}')">Hapus</button>
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
            <h2 class="text-lg font-bold mb-4 bg-blue-200 p-2 rounded-xl">Pinjam Buku</h2>
            <form id="addForm" action="{{ route('trsPinjam.store') }}" method="POST" class="w-full">
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
            <h2 class="text-lg font-bold mb-4 bg-amber-100 p-2 rounded-xl">Update Pinjam</h2>
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
             <div class="grid grid-cols-2"> 
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Kode Anggota</label>
                        <div class="my-3">
                        <input name="kd_anggota" id="kd_anggota" type="text" placeholder="" value="{{Auth::user()->name }}" readonly
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('kd_anggota')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Tanggal Pinjam</label>
                        <div class="my-3">
                        <input name="tg_pinjam" id="tg_pinjam" type="date" placeholder="" required
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('tg_pinjam')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Batas Pinjam</label>
                        <div class="my-3">

                        <input name="tgl_bts_kembali" id="tgl_bts_kembali" type="date" placeholder="" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('tgl_bts_kembali')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                     <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Buku</label>
                        <div class="my-3">
                            <select id="kd_koleksi" name="kd_koleksi" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                @foreach ($koleksi as $k)
                                <option value="{{ $k->kd_koleksi }}">{{ $k->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('kd_koleksi')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>
            `;
            const modal = document.getElementById("modal-addData");
            modal.classList.remove("hidden");

            const maxBatasPinjam = @json($max_wkt_pjm);
            const inputTglPinjam = document.getElementById('tg_pinjam');
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

        function closeModalAdd() {
            const modal = document.getElementById("modal-addData");
            modal.classList.add("hidden");
        }
    </script>

    {{-- SCRIPT MODAL UPDATE --}}
    <script>
        function updateData(id, kd_anggota, tg_pinjam, tgl_bts_kembali, kd_koleksi, routeUrl) {
            const modal = document.getElementById("modal-updateData");
            modal.classList.remove("hidden");

            const modalContent = document.getElementById("modal-content-update");
            modalContent.innerHTML = `
            <div class="grid grid-cols-2"> 
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Kode Koleksi</label>
                        <div class="my-3">
                        <input name="kd_anggota" id="kd_anggota" type="text" placeholder="" value="{{Auth::user()->name }}" readonly
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('kd_anggota')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Judul Koleksi</label>
                        <div class="my-3">
                        <input name="tg_pinjam" id="tg_pinjam" type="date" placeholder="" value="${tg_pinjam}"
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('tg_pinjam')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Judul Koleksi</label>
                        <div class="my-3">
                        <input name="tgl_bts_kembali" id="tgl_bts_kembali" type="date" placeholder="" value="${tgl_bts_kembali}"
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('tgl_bts_kembali')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                     <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Status Koleksi<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="kd_koleksi" name="kd_koleksi" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                @foreach ($koleksi as $k)
                                    <option value="{{ $k->kd_koleksi }}" ${kd_koleksi === "{{ $k->kd_koleksi }}" ? 'selected' : ''}>{{ $k->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('kd_koleksi')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
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
            message.textContent = `Apakah kamu yakin ingin menghapus Transaksi Pinjam dengan id "${id}"?`;

            const deleteForm = document.getElementById("deleteForm");
            deleteForm.action = routeUrl;
        }

        function closeModalDelete() {
            document.getElementById("modal-deleteData").classList.add("hidden");
        }
    </script>




</x-app-layout>
