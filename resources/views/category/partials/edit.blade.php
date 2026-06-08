<!-- Modal Edit Kategori -->
<div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="isEditModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <!-- Modal Panel -->
        <div x-show="isEditModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.away="isEditModalOpen = false"
             class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100 dark:border-slate-800">
            
            <!-- Modal Header -->
            <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200" id="modal-title">Edit Kategori</h3>
                <button @click="isEditModalOpen = false" type="button" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                    <span class="sr-only">Tutup</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Form) -->
            <form method="POST" :action="'{{ url('kategori') }}/' + editData.id">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-4">
                    <!-- Name Input -->
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Kategori</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                            <input type="text" name="name" id="edit_name" x-model="editData.name" required
                                class="block w-full pl-10 bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama kategori">
                        </div>
                        @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-600 border border-transparent rounded-lg hover:bg-amber-700 shadow-lg shadow-amber-500/30 transition-all">
                        Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
