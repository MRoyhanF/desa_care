<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->input('cari');
        $perHalaman = $request->input('per_halaman', 5);

        $kategori = Category::query()
            ->when($cari, function ($query, $cari) {
                $query->where('nama', 'like', "%{$cari}%");
            })
            ->latest()
            ->paginate($perHalaman)
            ->onEachSide(1)
            ->withQueryString();

        return view('category.page', compact('kategori', 'cari', 'perHalaman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Nama kategori sudah digunakan.',
            'nama.max'      => 'Nama kategori maksimal 255 karakter.',
        ]);

        Category::create(['nama' => $validated['nama'], 'aktif' => true]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama,' . $category->id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Nama kategori sudah digunakan.',
            'nama.max'      => 'Nama kategori maksimal 255 karakter.',
        ]);

        $category->update(['nama' => $validated['nama']]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function toggle(Category $category)
    {
        $category->update(['aktif' => !$category->aktif]);

        $pesan = $category->aktif ? 'Kategori berhasil diaktifkan.' : 'Kategori berhasil dinonaktifkan.';

        return redirect()->route('category.index')->with('success', $pesan);
    }

    public function destroy(Category $category)
    {
        if ($category->laporan()->count() > 0) {
            return redirect()->route('category.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki laporan.');
        }

        $category->delete();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
