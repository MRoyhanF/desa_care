<!-- Modal Buat Laporan -->
<div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="isModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <!-- Modal Panel -->
        <div x-show="isModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.away="isModalOpen = false"
             class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-100 dark:border-slate-800">
            
            <!-- Modal Header -->
            <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200" id="modal-title">Buat Pengaduan Baru</h3>
                <button @click="isModalOpen = false" type="button" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Form) -->
            <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-6 space-y-5">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori Laporan</label>
                        <select name="category_id" id="category_id" required
                            class="block w-full bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Laporan</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="block w-full bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors"
                            placeholder="Ringkasan masalah (contoh: Jalan Rusak di Dusun A)">
                        @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Isi Laporan / Kronologi</label>
                        <textarea name="description" id="description" rows="4" required
                            class="block w-full bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors"
                            placeholder="Ceritakan detail masalah yang terjadi...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Photo -->
                    <div>
                        <label for="photo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Lampiran Foto (Opsional)</label>
                        <input type="file" name="photo" id="photo" accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-slate-800 dark:file:text-primary-400 transition-all">
                        <p class="mt-1 text-xs text-slate-400">Format: JPG, PNG, max 2MB.</p>
                        @error('photo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
