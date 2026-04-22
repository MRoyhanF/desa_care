<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Dashboard Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-2xl shadow-primary-900/5 sm:rounded-3xl">
                <div class="p-8 text-slate-900 dark:text-slate-100">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/40 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-slate-500 text-sm">Anda berhasil masuk ke sistem pelayanan pengaduan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
