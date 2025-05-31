<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-auto justify-between">
            <h2 class="font-black text-xl text-white dark:text-white">
                {{ __('Data Anggota') }}
            </h2>
            <div class="mb-2">
                @cannot('role-Ang')
                <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Tambah Data
                </button>
                @endcannot
            </div>
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
                    @cannot('role-Ang')
                    <th scope="col" class="px-4 py-3">
                        Kode Anggota
                    </th>
                    @endcannot
                    <th scope="col" class="px-4 py-3">
                        Nama Anggota
                    </th>
                    @cannot('role-Ang')
                    <th scope="col" class="px-4 py-3">
                        Alamat Anggota
                    </th>
                    @endcannot
                    <th scope="col" class="px-4 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Jumlah Pinjam
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Dibuat Pada
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody id="tableBody">
                
                @forelse ($data as $d)
                    <tr >
                        <td class="px-7 py-3">{{ $d->id }}</td>
                        @cannot('role-Ang')
                        <td class="px-7 py-3">{{ $d->kd_anggota }}</td>
                        @endcannot
                        <td class="px-7 py-3">{{ $d->nm_anggota }}</td>
                        @cannot('role-Ang')
                        <td class="px-7 py-3">{{ $d->alamat }}</td>
                        @endcannot
                        <td class="px-7 py-3">{{ $d->status }}</td>
                        <td class="px-7 py-3">{{ $d->jml_pinjam }}</td>
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
                        <td>
                            @cannot('role-Ang')
                            <button
                            onclick="return updateData('{{ $d->id }}','{{ $d->nm_anggota }}'
                            ,'{{ $d->jk }}','{{ $d->alamat }}','{{ $d->status }}'
                            ,'{{ route('anggota.update', $d->id) }}')" 
                            class="bg-gray-600 text-white font-bold px-3 py-1 rounded-lg hover:bg-gray-700 transition">Edit</button>
                            <button
                            onclick="return deleteData('{{ $d->id }}','{{ $d->nm_anggota }}'
                            ,'{{ route('anggota.destroy', $d->id) }}')"
                            class="bg-red-600 text-white font-bold px-3 py-1 rounded-lg hover:bg-red-700 transition">Hapus</button>
                            @endcannot
                        </td>
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
                    <div>Data Anggota</div>
                    <button onclick="return addData()">Tambah Anggota</button>
                </div>
                <div class="px-6 text-gray-900 dark:text-gray-100">
                    <table id="myDataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Jumlah Pinjam</th>
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
                                    <td>{{ $d->kd_anggota }}</td>
                                    <td>{{ $d->nm_anggota }}</td>
                                    <td>{{ $d->jk }}</td>
                                    <td>{{ $d->alamat }}</td>
                                    <td>{{ $d->status }}</td>
                                    <td>{{ $d->jml_pinjam }}</td>
                                    <td>
                                        <button
                                            onclick="return updateData('{{ $d->id }}','{{ $d->nm_anggota }}'
                                            ,'{{ $d->jk }}','{{ $d->alamat }}','{{ $d->status }}'
                                            ,'{{ route('anggota.update', $d->id) }}')">Edit</button>
                                        <button
                                            onclick="return deleteData('{{ $d->id }}','{{ $d->nm_anggota }}', '{{ route('anggota.destroy', $d->id) }}')">Hapus</button>
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
            <h2 class="text-xl font-bold mb-4 bg-blue-200 p-3 rounded-xl">Tambah Anggota</h2>
            <form enctype="multipart/form-data" id="addForm" action="{{ route('anggota.store') }}" method="post" class="w-full">
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
            <h2 class="text-lg font-bold bg-blue-200 p-2 rounded-xl">Perbarui Anggota</h2>
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
                        class="bg-gray-400 text-white px-4 py-2 rounded">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- KODE DATA --}}
    <script>
        const kodeAnggotaBaru = @json($codeData);
    </script>

    {{-- SCRIPT MODAL ADD --}}
    <script>
        function addData() {
            const modalContent = document.getElementById("modal-content");
            modalContent.innerHTML = `
                <div class="grid grid-cols-2"> 
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Kode Anggota</label>
                        <div class="my-3">
                        <input name="kd_anggota" id="kd_anggota" type="text" placeholder="Isi Kode Anggota" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('kd_anggota')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Nama Anggota</label>
                        <div class="my-3">
                        <input name="nm_anggota" id="nm_anggota" type="text" placeholder="Isi Nama Anggota" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('nm_anggota')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Jenis Kelamin</label>
                        <div class="my-3">
                            <select id="jk" name="jk" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="">
                                <option value="">Pilih...</option>
                                <option value="L">Laki Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        @error('jk')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Alamat</label>
                        <div class="my-3">
                        <input name="alamat" id="alamat" type="text" placeholder="Isi Alamat" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('alamat')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Status Anggota<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="status" name="status" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
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
        function updateData(id, nama, jk, alamat, status, routeUrl) {
            const modal = document.getElementById("modal-updateData");
            modal.classList.remove("hidden");

            const modalContent = document.getElementById("modal-content-update");
            modalContent.innerHTML = `
            <div class="mb-4 w-full">
                <label for="nm_anggota" class="block mb-2 text-sm font-medium text-gray-900">Nama Anggota</label>
                <input type="text" id="nm_anggota" name="nm_anggota" value="${nama}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" />
            </div>
            <div class="mb-4 w-full">
                <label for="jk" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin</label>
                <select id="jk" name="jk" class="form-control w-full">
                    <option value="">Pilih...</option>
                    <option value="L" ${jk === 'L' ? 'selected' : ''}>LAKI-LAKI</option>
                    <option value="P" ${jk === 'P' ? 'selected' : ''}>PEREMPUAN</option>
                </select>
            </div>
            <div class="mb-4 w-full">
                <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat</label>
                <textarea id="alamat" name="alamat" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">${alamat}</textarea>
            </div>
            <div class="mb-4 w-full">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin</label>
                <select id="status" name="status" class="form-control w-full">
                    <option value="">Pilih...</option>
                    <option value="AKTIF" ${status === 'AKTIF' ? 'selected' : ''}>AKTIF</option>
                    <option value="TIDAK AKTIF" ${status === 'TIDAK AKTIF' ? 'selected' : ''}>TIDAK AKTIF</option>
                </select>
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
        function deleteData(id, nama, routeUrl) {
            const modal = document.getElementById("modal-deleteData");
            modal.classList.remove("hidden");

            const message = document.getElementById("delete-message");
            message.textContent = `Apakah kamu yakin ingin menghapus anggota dengan nama "${nama}"?`;

            const deleteForm = document.getElementById("deleteForm");
            deleteForm.action = routeUrl;
        }

        function closeModalDelete() {
            document.getElementById("modal-deleteData").classList.add("hidden");
        }
    </script>


</x-app-layout>
