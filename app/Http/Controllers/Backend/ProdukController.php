<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    /*
    |--------------------------------
    | INDEX
    |--------------------------------
    */
    public function index()
    {
        $judul = 'Data Produk';

        $index = Produk::with('kategori')
            ->latest()
            ->get();

        return view('backend.produk.index', compact(
            'judul',
            'index'
        ));
    }

    /*
    |--------------------------------
    | CREATE
    |--------------------------------
    */
    public function create()
    {
        $judul = 'Tambah Produk';

        $kategori = Kategori::all();

        return view('backend.produk.create', compact(
            'judul',
            'kategori'
        ));
    }

    /*
    |--------------------------------
    | STORE
    |--------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'berat' => 'required',
            'detail' => 'required',
            'foto' => 'required|image'
        ]);

        $foto = $request->file('foto');
        $namaFoto = time().'.'.$foto->extension();
        $foto->move(public_path('produk'), $namaFoto);

        Produk::create([
            'kategori_id' => $request->kategori_id,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'detail' => $request->detail,
            'foto' => $namaFoto,
            'status' => 1
        ]);

        return redirect()->route('backend.produk.index')
            ->with('success', 'Produk berhasil ditambah');
    }

    /*
    |--------------------------------
    | EDIT
    |--------------------------------
    */
    public function edit($id)
    {
        $judul = 'Edit Produk';

        // 🔥 PENTING: HARUS $produk (bukan $edit)
        $produk = Produk::findOrFail($id);

        $kategori = Kategori::all();

        return view('backend.produk.edit', compact(
            'judul',
            'produk',
            'kategori'
        ));
    }

    /*
    |--------------------------------
    | UPDATE
    |--------------------------------
    */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time().'.'.$foto->extension();
            $foto->move(public_path('produk'), $namaFoto);
            $produk->foto = $namaFoto;
        }

        $produk->kategori_id = $request->kategori_id;
        $produk->nama_produk = $request->nama_produk;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->berat = $request->berat ?? 0;
        $produk->detail = $request->detail ?? '-';
        $produk->status = $request->status ?? 1;

        $produk->save();

        return redirect()->route('backend.produk.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    /*
    |--------------------------------
    | DELETE
    |--------------------------------
    */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        $produk->delete();

        return redirect()->route('backend.produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}