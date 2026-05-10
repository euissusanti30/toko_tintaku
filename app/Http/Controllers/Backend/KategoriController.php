<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.kategori.index', [
            'judul' => 'Data Kategori',
            'kategori' => $kategori
        ]);
    }

    public function create()
    {
        return view('backend.kategori.create', [
            'judul' => 'Tambah Kategori'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|max:255'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('backend.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('backend.kategori.edit', [
            'judul' => 'Edit Kategori',
            'kategori' => $kategori
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|max:255'
        ]);

        Kategori::where('id', $id)->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('backend.kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('backend.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}