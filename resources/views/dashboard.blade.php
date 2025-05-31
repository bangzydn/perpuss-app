<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white dark:text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-700 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{Auth::user()->name }} {{ __( "logged in!") }}
                </div>
            </div>
        </div>
        <div class="py-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Kartu Statistik -->
                <div class="bg-gray-900 dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6">
                        {{-- Kartu Total Anggota --}}
                        <div class="bg-gray-800 dark:bg-gray-800 rounded-2xl shadow-md p-6 transition transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Total Anggota</h2>
                                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $totalAnggota }}</p>
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                                        <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                                      </svg>
                                </div>
                            </div>
                            <a href="{{ route('anggota.index') }}" class="mt-4 inline-flex items-center text-sm text-blue-600 hover:underline dark:text-blue-400">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    
                        {{-- Kartu Buku Tersedia --}}
                        <div class="bg-gray-800 dark:bg-gray-800 rounded-2xl shadow-md p-6 transition transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Koleksi Buku</h2>
                                    <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $totalBuku }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                                    </svg>
                                      
                                </div>
                            </div>
                            <a href="{{ route('koleksi.index') }}" class="mt-4 inline-flex items-center text-sm text-green-600 hover:undeline dark:text-green-400">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    
                        {{-- Kartu Transaksi Hari Ini --}}
                        <div class="bg-gray-800 dark:bg-gray-800 rounded-2xl shadow-md p-6 transition transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Total Peminjaman</h2>
                                    <p class="text-4xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalPinjam }}</p>
                                </div>
                                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                        <path fill-rule="evenodd" d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087ZM12 10.5a.75.75 0 0 1 .75.75v4.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l1.72 1.72v-4.94a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <a href="{{ route('trsPinjam.index') }}" class="mt-4 inline-flex items-center text-sm text-yellow-600 hover:underline dark:text-yellow-400">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                        <div class="bg-gray-800 dark:bg-gray-800 rounded-2xl shadow-md p-6 transition transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Total Pengembalian</h2>
                                    <p class="text-4xl font-bold text-red-600 dark:text-red-400">{{ $totalPinjam }}</p>
                                </div>
                                <div class="bg-red-100 dark:bg-red-500 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M11.47 1.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1-1.06 1.06l-1.72-1.72V7.5h-1.5V4.06L9.53 5.78a.75.75 0 0 1-1.06-1.06l3-3ZM11.25 7.5V15a.75.75 0 0 0 1.5 0V7.5h3.75a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9a3 3 0 0 1 3-3h3.75Z" />
                                    </svg>
                                </div>
                            </div>
                            <a href="{{ route('trsPinjam.index') }}" class="mt-4 inline-flex items-center text-sm text-red-600 hover:underline dark:text-red-400">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                </div>
    
                {{-- <!-- Chart Container -->
                <!-- Chart Container -->
                <div class="bg-white dark:bg-white mt-8 p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-black text-center mb-4">Grafik Register SLIK per Bulan</h3>
                    <div class="w-full h-[400px]">
                        <canvas id="loanChart" class="w-full h-full"></canvas>
                    </div>
                </div> --}}
    
            </div>
        </div>
    </div>
</x-app-layout>
