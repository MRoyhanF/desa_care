<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Perbarui informasi profil dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
            <input type="file" class="hidden"
                        name="foto"
                        x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        ">

            <x-input-label for="photo" :value="__('Foto Profil')" />

            <div class="mt-2 flex items-center space-x-6">
                <!-- Current Profile Photo -->
                <div class="shrink-0" x-show="! photoPreview">
                    @if($user->foto && file_exists(public_path($user->foto)))
                        <img src="{{ asset($user->foto) }}" alt="{{ $user->nama }}" class="h-24 w-24 object-cover rounded-2xl border-4 border-slate-100 dark:border-slate-800 shadow-xl">
                    @else
                        <div class="h-24 w-24 rounded-2xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center border-4 border-slate-100 dark:border-slate-800 shadow-xl">
                            <span class="text-3xl font-black text-primary-600 dark:text-primary-400 capitalize">{{ substr($user->nama, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <!-- New Profile Photo Preview -->
                <div class="shrink-0" x-show="photoPreview" style="display: none;">
                    <img :src="photoPreview" class="h-24 w-24 object-cover rounded-2xl border-4 border-slate-100 dark:border-slate-800 shadow-xl">
                </div>

                <div class="space-y-2">
                    <x-secondary-button type="button" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Pilih Foto') }}
                    </x-secondary-button>
                    <p class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG, max 2MB.</p>
                </div>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="telepon" :value="__('Nomor Telepon')" />
            <x-text-input id="telepon" name="telepon" type="text" class="mt-1 block w-full" :value="old('telepon', $user->telepon)" placeholder="Contoh: 08123456789" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('telepon')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
