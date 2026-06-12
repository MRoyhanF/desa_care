<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->input('cari');
        $peran = $request->input('peran');
        $perHalaman = $request->input('per_halaman', 5);

        $pengguna = User::query()
            ->when($cari, function ($query, $cari) {
                $query->where(function ($q) use ($cari) {
                    $q->where('nama', 'like', "%{$cari}%")
                      ->orWhere('email', 'like', "%{$cari}%")
                      ->orWhere('telepon', 'like', "%{$cari}%");
                });
            })
            ->when($peran, function ($query, $peran) {
                $query->where('peran', $peran);
            })
            ->latest()
            ->paginate($perHalaman)
            ->onEachSide(1)
            ->withQueryString();

        $users = $pengguna;
        return view('users.page', compact('users', 'pengguna', 'cari', 'peran', 'perHalaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:pengguna,email'],
            'telepon'  => ['nullable', 'string', 'max:20', 'unique:pengguna,telepon'],
            'peran'    => ['required', 'in:admin,pengguna'],
            'kata_sandi' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.unique'          => 'Email sudah digunakan.',
            'telepon.unique'        => 'Nomor telepon sudah digunakan.',
            'peran.required'        => 'Peran wajib dipilih.',
            'kata_sandi.required'   => 'Kata sandi wajib diisi.',
            'kata_sandi.min'        => 'Kata sandi minimal 8 karakter.',
            'kata_sandi.confirmed'  => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        User::create([
            'nama'                   => $request->nama,
            'email'                  => $request->email,
            'telepon'                => $request->telepon,
            'peran'                  => $request->peran,
            'kata_sandi'             => Hash::make($request->kata_sandi),
            'email_terverifikasi_pada' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['laporan.kategori', 'laporan.logLaporan' => function ($q) {
            $q->latest();
        }]);

        return view('users.show', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:pengguna,email,' . $user->id],
            'telepon'  => ['nullable', 'string', 'max:20', 'unique:pengguna,telepon,' . $user->id],
            'peran'    => ['required', 'in:admin,pengguna'],
            'kata_sandi' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required'        => 'Nama wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.unique'         => 'Email sudah digunakan.',
            'telepon.unique'       => 'Nomor telepon sudah digunakan.',
            'peran.required'       => 'Peran wajib dipilih.',
            'kata_sandi.min'       => 'Kata sandi minimal 8 karakter.',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $data = [
            'nama'    => $request->nama,
            'email'   => $request->email,
            'telepon' => $request->telepon,
            'peran'   => $request->peran,
        ];

        if ($request->filled('kata_sandi')) {
            $data['kata_sandi'] = Hash::make($request->kata_sandi);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
