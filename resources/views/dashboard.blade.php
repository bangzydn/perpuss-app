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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                        {{-- Kartu Total Anggota --}}
                        <div class="bg-gray-800 dark:bg-gray-800 rounded-2xl shadow-md p-6 transition transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Total Anggota</h2>
                                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $totalAnggota }}</p>
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75" />
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
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Buku Tersedia</h2>
                                    <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $totalBuku }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
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
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Peminjaman Hari Ini</h2>
                                    <p class="text-4xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalPinjam }}</p>
                                </div>
                                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z" />
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
