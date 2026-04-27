<!-- Modal Edit/Detail Laporan -->
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
             class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100 dark:border-slate-800">
            
            <!-- Modal Header -->
            <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200" id="modal-title">Detail & Kelola Laporan</h3>
                <button @click="isEditModalOpen = false" type="button" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Form) -->
            <form method="POST" :action="'{{ url('laporan') }}/' + editData.id">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Info Section -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Status Saat Ini</label>
                                <template x-if="editData.status === 'pending'">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">Menunggu Peninjauan</span>
                                </template>
                                <template x-if="editData.status === 'validated'">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">Tervalidasi</span>
                                </template>
                                <template x-if="editData.status === 'rejected'">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">Ditolak</span>
                                </template>
                                <template x-if="editData.status === 'on_progress'">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50">Sedang Diproses</span>
                                </template>
                                <template x-if="editData.status === 'done'">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800/50">Selesai</span>
                                </template>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pelapor</label>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300" x-text="editData.user_name"></p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kategori</label>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400" x-text="editData.category_name"></p>
                            </div>
                        </div>

                        <!-- Photo Section -->
                        <div x-show="editData.photo">
                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Lampiran Foto</label>
                            <img :src="'{{ asset('') }}' + editData.photo" alt="Lampiran" class="w-full h-32 object-cover rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm cursor-pointer hover:opacity-90 transition-opacity" @click="window.open('{{ asset('') }}' + editData.photo, '_blank')">
                        </div>
                    </div>

                    <!-- Editable Section -->
                    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div>
                            <label for="edit_title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Laporan</label>
                            <input type="text" name="title" id="edit_title" x-model="editData.title" required
                                class="block w-full bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors">
                        </div>

                        <div>
                            <label for="edit_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Isi Laporan</label>
                            <textarea name="description" id="edit_description" rows="3" x-model="editData.description" required
                                class="block w-full bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors"></textarea>
                        </div>
                    </div>

                    <!-- Admin Only: Update Status -->
                    @if(Auth::user()->role === 'admin')
                    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800 bg-primary-50/30 dark:bg-primary-900/10 p-4 rounded-xl ring-1 ring-primary-100 dark:ring-primary-900/30">
                        <h4 class="text-xs font-bold text-primary-700 dark:text-primary-400 uppercase tracking-widest">Kontrol Admin</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="update_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Perbarui Status</label>
                                <select name="status" id="update_status" class="block w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors">
                                    <option value="pending" :selected="editData.status == 'pending'">Pending</option>
                                    <option value="validated" :selected="editData.status == 'validated'">Tervalidasi</option>
                                    <option value="on_progress" :selected="editData.status == 'on_progress'">Proses</option>
                                    <option value="done" :selected="editData.status == 'done'">Selesai</option>
                                    <option value="rejected" :selected="editData.status == 'rejected'">Ditolak</option>
                                </select>
                            </div>
                            <div>
                                <label for="note" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan/Keterangan</label>
                                <input type="text" name="note" id="note" placeholder="Contoh: Sedang dicek ke lokasi"
                                    class="block w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Tutup
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-600 border border-transparent rounded-lg hover:bg-amber-700 shadow-lg shadow-amber-500/30 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
