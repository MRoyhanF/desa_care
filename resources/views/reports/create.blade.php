<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('report.index') }}" class="p-2 text-slate-400 hover:text-primary-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
                {{ __('Buat Pengaduan Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-2xl shadow-primary-900/5 sm:rounded-3xl">
                <div class="p-8">
                    <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Category Selection -->
                            <div>
                                <label for="category_id" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kategori Laporan</label>
                                <div class="relative">
                                    <select name="category_id" id="category_id" required
                                        class="block w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-primary-500 focus:border-primary-500 py-3 px-4 text-slate-900 dark:text-white transition-all shadow-sm">
                                        <option value="" class="text-slate-900">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="text-slate-900">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Subjek / Judul Laporan</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                    class="block w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-primary-500 focus:border-primary-500 py-3 px-4 text-slate-900 dark:text-white transition-all shadow-sm"
                                    placeholder="Contoh: Lampu Penerangan Jalan Mati">
                                @error('title') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Isi Pengaduan</label>
                                <textarea name="description" id="description" rows="5" required
                                    class="block w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-primary-500 focus:border-primary-500 py-3 px-4 text-slate-900 dark:text-white transition-all shadow-sm"
                                    placeholder="Ceritakan secara detail kronologi atau masalah yang Anda temui...">{{ old('description') }}</textarea>
                                @error('description') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Photo Upload -->
                            <div x-data="{ photoName: null, photoPreview: null }">
                                <label for="photo" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Lampiran Foto (Opsional)</label>
                                
                                <input type="file" id="photo" name="photo" class="hidden"
                                       x-ref="photo"
                                       @change="
                                            photoName = $refs.photo.files[0].name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                photoPreview = e.target.result;
                                            };
                                            reader.readAsDataURL($refs.photo.files[0]);
                                       " />

                                <div class="mt-2 flex items-center gap-4">
                                    <!-- Photo Preview -->
                                    <div class="relative w-32 h-32 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden bg-slate-50 dark:bg-slate-800">
                                        <template x-if="!photoPreview">
                                            <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </template>
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="h-full w-full object-cover">
                                        </template>
                                    </div>

                                    <button type="button" @click="$refs.photo.click()" 
                                            class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm hover:bg-slate-50 transition-all">
                                        Pilih Foto
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-slate-400">Maksimal ukuran file 2MB (JPG, PNG)</p>
                                @error('photo') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                            <a href="{{ route('report.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold text-sm hover:bg-slate-200 transition-all">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 rounded-2xl bg-primary-600 text-white font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                                Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
