<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-auto justify-between">
            <h2 class="font-black text-xl text-white dark:text-white">
                {{ __('Data Anggota') }}
            </h2>
            @cannot('role-Ang')
            <div class="mb-2">
                <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Tambah Data
                </button>
            </div>  
            @endcannot
            
        </div>
    </x-slot>

    

    <div class="w-auto mx-auto relative overflow-x-auto shadow-sm sm:rounded-lg mt-2 px-4 py-4">
        <x-message></x-message>
        <table style="width:100%" class="w-full text-sm text-center rtl:text-right text-white dark:text-black rounded-md shadow-xl">
            <thead class="text-md font-extrabold text-white uppercase bg-blue-900 dark:bg-blue-900 dark:text-white">
                <tr>
                    <th scope="col" class="px-4 py-3">
                        No
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Kode Koleksi
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Judul
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Pengarang
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Penerbit
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Tahun
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Status 
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Dibuat Pada
                    </th>
                    @cannot('role-Ang')
                    <th scope="col" class="px-4 py-3">
                        Aksi
                    </th> 
                    @endcannot
                    
                </tr>
            </thead>
            <tbody id="tableBody">
                
                @forelse ($data as $d)
                    <tr >
                        <td class="px-7 py-3">{{ $d->id }}</td>
                        <td class="px-7 py-3">{{ $d->kd_koleksi }}</td>
                        <td class="px-7 py-3">{{ $d->judul }}</td>
                        <td class="px-7 py-3">{{ $d->pengarang }}</td>
                        <td class="px-7 py-3">{{ $d->penerbit }}</td>
                        <td class="px-7 py-3">{{ $d->tahun }}</td>
                        <td class="px-7 py-3">{{ $d->status }}</td>
                        {{-- @php
                            $label = [
                                'A' => 'Administrator',
                                'CS' => 'Customer Service',
                                'AO' => 'Account Officer',
                            ];
                        @endphp --}}
                        {{-- <td class="px-7 py-3">{{ $label[$d->role->nama_bagian ?? ''] ?? 'Tidak diketahui' }}</td>
                        <td class="px-7 py-3">{{ $d->status_p }}</td> --}}
                        <td class="px-7 py-3">
                            {{\Carbon\Carbon::parse($d->created_at)->format('d M, Y')  }}</td>
                            @cannot('role-Ang')
                            <td>
                                <button
                                onclick="return updateData('{{ $d->id }}','{{ $d->judul }}'
                                ,'{{ $d->pengarang }}','{{ $d->penerbit }}','{{ $d->tahun }}'
                                ,'{{ $d->status }}','{{ route('koleksi.update', $d->id) }}')" 
                                class="bg-gray-600 text-white font-bold px-3 py-1 rounded-lg hover:bg-gray-700 transition">Edit</button>
                                <button
                                onclick="return deleteData('{{ $d->id }}'
                                ,'{{ $d->judul }}', '{{ route('koleksi.destroy', $d->id) }}')"
                                class="bg-red-600 text-white font-bold px-3 py-1 rounded-lg hover:bg-red-700 transition">Hapus</button>
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
                    <div class="text-lg">Data Koleksi</div>
                    <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">Tambah koleksi</button>
                </div>
                <div class="px-6 text-gray-900 dark:text-gray-100">
                    <table id="myDataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Status</th>
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
                                    <td>{{ $d->kd_koleksi }}</td>
                                    <td>{{ $d->judul }}</td>
                                    <td>{{ $d->pengarang }}</td>
                                    <td>{{ $d->penerbit }}</td>
                                    <td>{{ $d->tahun }}</td>
                                    <td>{{ $d->status }}</td>
                                    <td>
                                        <button
                                            onclick="return updateData('{{ $d->id }}','{{ $d->judul }}'
                                            ,'{{ $d->pengarang }}','{{ $d->penerbit }}','{{ $d->tahun }}'
                                            ,'{{ $d->status }}','{{ route('koleksi.update', $d->id) }}')" 
                                            class="bg-gray-600 text-white font-bold px-2 py-1 rounded-lg hover:bg-gray-700 transition">Edit</button>
                                        <button
                                            onclick="return deleteData('{{ $d->id }}'
                                            ,'{{ $d->judul }}', '{{ route('koleksi.destroy', $d->id) }}')"
                                            class="bg-red-600 text-white font-bold px-2 py-1 rounded-lg hover:bg-red-700 transition">Hapus</button>
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
            <div class="bg-white rounded-lg p-4 w-1/2 shadow-xl">
                <h2 class="text-xl font-bold mb-4 bg-blue-200 p-3 rounded-xl">Tambah Koleksi</h2>
                <form enctype="multipart/form-data" id="addForm" action="{{ route('koleksi.store') }}" method="post" class="w-full">
                    @csrf
                    <p id="modal-content"></p>
                    <div class="text-center">
                        <button type="submit" id="submitAdd" class="bg-blue-800 text-white font-bold hover:bg-blue-600 px-4 py-2 rounded-md">
                            Simpan
                        </button>
                        <button type="button" onclick="closeModalAdd(event)"
                            class="bg-red-800 text-white font-bold hover:bg-red-600 px-4 py-2 rounded-md">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>

    {{-- MODAL UPDATE DATA --}}
        <div id="modal-updateData" class="hidden fixed inset-0 flex justify-center items-center m-4">
            <div class="bg-white rounded-lg p-6 w-1/2  shadow-xl">
                <h2 class="text-lg font-bold bg-blue-200 p-2 rounded-xl">Perbarui Koleksi</h2>
                <form enctype="multipart/form-data" id="updateForm" action="" method="post" class="w-full">
                    @csrf
                    @method('PATCH')
                    <p id="modal-content-update"></p>
                    <div class="text-center">
                        <button type="submit" id="submitUpdate" class="mt-2 bg-blue-800 text-white font-bold hover:bg-blue-600 px-4 py-2 rounded">
                            Simpan
                        </button>
                        <button type="button" onclick="closeModalUpdate(event)"
                            class="mt-2 bg-red-800 text-white font-bold hover:bg-red-600 px-4 py-2 rounded">
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
                            class="bg-gray-400 text-white font-semibold px-4 py-2 rounded">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>


    {{-- KODE DATA --}}
    <script>
        const kodekoleksiBaru = @json($codeData);
    </script>

    {{-- SCRIPT MODAL ADD --}}
    <script>
        function addData() {
            const modalContent = document.getElementById("modal-content");
            modalContent.innerHTML = `
                <div class="grid grid-cols-2"> 
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Kode Koleksi</label>
                        <div class="my-3">
                        <input name="kd_koleksi" id="kd_koleksi" type="text" placeholder="Isi Kode Koleksi" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('kd_koleksi')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Judul Koleksi</label>
                        <div class="my-3">
                        <input name="judul" id="judul" type="text" placeholder="Isi Judul Koleksi" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('judul')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Pengarang Koleksi</label>
                        <div class="my-3">
                        <input name="pengarang" id="pengarang" type="text" placeholder="Isi Pengarang" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('pengarang')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Penerbit Koleksi</label>
                        <div class="my-3">
                        <input name="penerbit" id="penerbit" type="text" placeholder="Isi Penerbit" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('penerbit')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Tahun Koleksi</label>
                        <div class="my-3">
                        <input name="tahun" id="tahun" type="text" placeholder="Isi Tahun" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('tahun')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Status Koleksi<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="status" name="status" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                <option value="TERSEDIA">TERSEDIA</option>
                                <option value="TIDAK TERSEDIA">TIDAK TERSEDIA</option>
                            </select>
                        </div>
                        @error('status')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>
                    `;
            const modal = document.getElementById("modal-addData");
            modal.classList.remove("hidden");
        }

        function closeModalAdd() {
            const modal = document.getElementById("modal-addData");
            modal.classList.add("hidden");
        }
    </script>

    {{-- SCRIPT MODAL UPDATE --}}
    <script>
        function updateData(id, judul, pengarang, penerbit, tahun, status, routeUrl) {
            const modal = document.getElementById("modal-updateData");
            modal.classList.remove("hidden");

            const modalContent = document.getElementById("modal-content-update");
            modalContent.innerHTML = `
            <div class="grid grid-cols-2"> 
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Kode Koleksi</label>
                        <div class="my-3">
                        <input name="kd_koleksi" id="kd_koleksi" type="text" placeholder="Isi Kode Koleksi" value="${kodekoleksiBaru}"
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('kd_koleksi')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Judul Koleksi</label>
                        <div class="my-3">
                        <input name="judul" id="judul" type="text" placeholder="Isi Judul Koleksi" value="${judul}"
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('judul')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Pengarang Koleksi</label>
                        <div class="my-3">
                        <input name="pengarang" id="pengarang" type="text" placeholder="Isi Pengarang" value="${pengarang}"
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('pengarang')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Penerbit Koleksi</label>
                        <div class="my-3">
                        <input name="penerbit" id="penerbit" type="text" placeholder="Isi Penerbit" value="${penerbit}"
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('penerbit')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Tahun Koleksi</label>
                        <div class="my-3">
                        <input name="tahun" id="tahun" type="text" placeholder="Isi Tahun" value="${tahun}" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('tahun')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Status Koleksi<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="status" name="status" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Koleksi">
                                <option value="">Pilih...</option>
                                <option value="TERSEDIA"${status === 'TERSEDIA' ? 'selected' : ''}>TERSEDIA</option>
                                <option value="TIDAK TERSEDIA" ${status === 'TIDAK TERSEDIA' ? 'selected' : ''}>TIDAK TERSEDIA</option>
                            </select>
                        </div>
                        @error('status')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>
        `;
            const updateForm = document.getElementById("updateForm");
            updateForm.action = routeUrl;
        }

        function closeModalUpdate(event) {
            event.preventDefault();
            document.getElementById("modal-updateData").classList.add("hidden");
        }
    </script>

    {{-- SCRIPT MODAL DELETE --}}
    <script>
        function deleteData(id, judul, routeUrl) {
            const modal = document.getElementById("modal-deleteData");
            modal.classList.remove("hidden");

            const message = document.getElementById("delete-message");
            message.textContent = `Apakah kamu yakin ingin menghapus koleksi dengan judul "${judul}"?`;

            const deleteForm = document.getElementById("deleteForm");
            deleteForm.action = routeUrl;
        }

        function closeModalDelete() {
            document.getElementById("modal-deleteData").classList.add("hidden");
        }
    </script>


</x-app-layout>
