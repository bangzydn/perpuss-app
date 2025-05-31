<x-app-layout>
    

    <x-slot name="header">
        <div class="flex flex-auto justify-between">
            <h2 class="font-black text-xl text-white dark:text-white">
                {{ __('Data Pengguna') }}
            </h2>
            {{-- <div class="mb-2">
                <button onclick="return addData()" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Tambah Data
                </button>
            </div> --}}
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
                        Nama Pengguna
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Bagian
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Status Pengguna
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
                @php
                $no = 1;
                @endphp
                @forelse ($users as $user)
                    <tr >
                        <td class="px-7 py-3">{{ $user->id }}</td>
                        <td class="px-7 py-3">{{ $user->name }}</td>
                        {{-- @php
                            $label = [
                                'A' => 'Administrator',
                                'Ang' => 'Anggota Perpustakaan',
                            ];
                        @endphp --}}
                        <td class="px-7 py-3">{{ $user->role  }}</td>
                        <td class="px-7 py-3">{{ $user->status_p }}</td>
                        <td class="px-7 py-3">
                            {{\Carbon\Carbon::parse($user->created_at)->format('d M, Y')  }}</td>
                        <td>
                            {{-- <button
                            onclick="return updateData('{{ $user->id }}','{{ $user->name }}',
                            '{{ $user->email }}','{{ $user->password }}',
                            '{{ $user->role }}','{{ $user->status_p }}','{{ route('users.update', $user->id) }}')" 
                            class="bg-gray-600 text-white font-bold px-3 py-1 rounded-lg hover:bg-gray-700 transition">Edit</button> --}}
                            <button
                            onclick="return deleteData('{{ $user->id }}','{{ $user->name }}', '{{ route('users.destroy', $user->id) }}')"
                            class="bg-red-600 text-white font-bold px-3 py-1 rounded-lg hover:bg-red-700 transition">Hapus</button>
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
            {{ $users->links() }}
        </div>
        
    </div>

    {{-- MODAL ADD DATA --}}
    <div id="modal-addData" class="hidden fixed inset-0 flex justify-center items-center m-4">
        <div class="bg-white rounded-lg p-4 w-1/2 shadow-xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4 bg-blue-200 p-3 rounded-xl">Tambah Pengguna</h2>
            <form id="addForm" action="{{ route('users.store') }}" method="post" class="w-full">
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
            <div class="bg-white rounded-lg p-6 lg:w-4/12 w-full shadow-xl max-h-[90vh] overflow-y-auto">
                <h2 class="text-lg font-bold mb-4 bg-blue-200 p-2 rounded-xl">Perbarui Pengguna</h2>
                <form id="updateForm" action="" method="post" class="w-full">
                    @csrf
                    @method('PATCH')
                    <p id="modal-content-update"></p>
                    <button type="submit" id="submitUpdate" class="mt-4 bg-sky-500 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModalUpdate(event)"
                        class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
                        Tutup
                    </button>
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
        {{-- <script>
            function addData() {
                const modalContent = document.getElementById("modal-content");
                modalContent.innerHTML = `
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Nama Pengguna</label>
                        <div class="my-3">
                        <input name="name" type="text" placeholder="Isi nama pengguna" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('name')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Email</label>
                        <div class="my-3">
                        <input name="email" type="email" placeholder="Isi e-mail" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('email')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Password</label>
                        <div class="my-3">
                        <input name="password" type="password" placeholder="Isi password" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('password')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Role<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="id_role" name="id_role" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nama_bagian }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_role')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Status Pengguna<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="status_p" name="status_p" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </div>
                        @error('status_p')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
                `;
                const modal = document.getElementById("modal-addData");
                modal.classList.remove("hidden");
            }

            function closeModalAdd() {
                const modal = document.getElementById("modal-addData");
                modal.classList.add("hidden");
            }
        </script> --}}

    {{-- SCRIPT MODAL UPDATE --}}
        {{-- <script>
            function updateData(id, name, email, password, role, status_p, routeUrl) {
                const modal = document.getElementById("modal-updateData");
                modal.classList.remove("hidden");

                const modalContent = document.getElementById("modal-content-update");
                modalContent.innerHTML = `
                <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Nama Pengguna</label>
                        <div class="my-3">
                        <input value="${name}" id="names" name="name" type="text" placeholder="Isi nama pengguna" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('name')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Email</label>
                        <div class="my-3">
                        <input value="${email}" id="emails" name="email" type="email" placeholder="Isi e-mail" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('email')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Password</label>
                        <div class="my-3">
                        <input value="${password}" id="passwords" name="passwords" type="password" placeholder="Isi password" 
                        class="border-blue-300 shadow-sm w-full rounded-lg">
                            @error('password')
                                p class="text-red-500 font-medium"> {{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Role<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="id_role" name="id_role" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" 
                                        @selected(old('role', $user->role ?? '') == $user->role)>
                                        {{ $user->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_role')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
                    <div class="px-2 py-3">
                        <label for="" class="text-lg font-medium">Role<span class="text-red-500">*</span></label>
                        <div class="my-3">
                            <select id="status_p" name="status_p" class="form-control border-blue-300 shadow-sm w-full rounded-lg"  data-placeholder="Pilih Bagian">
                                <option value="">Pilih...</option>
                                <option value="Aktif" ${status_p === 'Aktif' ? 'selected' : ''}>Aktif</option>
                                <option value="Non-Aktif" ${status_p === 'Non-Aktif' ? 'selected' : ''}>Non-Aktif</option>
                            </select>
                        </div>
                        @error('status_p')
                            p class="text-red-500 font-medium"> {{ $message }} </p>
                        @enderror
                    </div>
            `;
                const updateForm = document.getElementById("updateForm");
                updateForm.action = routeUrl;
            }

            function closeModalUpdate(event) {
                event.preventDefault();
                document.getElementById("modal-updateData").classList.add("hidden");
            }
        </script> --}}

    {{-- SCRIPT MODAL DELETE --}}
        <script>
            function deleteData(id, routeUrl) {
                const modal = document.getElementById("modal-deleteData");
                modal.classList.remove("hidden");

                const message = document.getElementById("delete-message");
                message.textContent = `Apakah kamu yakin ingin menghapus hak akses dengan nama "${name}"?`;

                const deleteForm = document.getElementById("deleteForm");
                deleteForm.action = routeUrl;
            }

            function closeModalDelete() {
                document.getElementById("modal-deleteData").classList.add("hidden");
            }
        </script>


</x-app-layout>