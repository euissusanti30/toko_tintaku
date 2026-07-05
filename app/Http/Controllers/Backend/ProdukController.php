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
        try {
            $request->validate([
                'kategori_id' => 'required|exists:kategori,id',
                'nama_produk' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'berat' => 'required|numeric|min:0',
                'detail' => 'required|string',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Ensure the produk directory exists
            $produkPath = public_path('produk');
            if (!file_exists($produkPath)) {
                mkdir($produkPath, 0755, true);
            }

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $namaFoto = time().'.'.$foto->extension();
                $foto->move($produkPath, $namaFoto);
            } else {
                return redirect()->back()
                    ->with('error', 'Foto produk wajib diupload')
                    ->withInput();
            }

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
                ->with('success', 'Produk berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
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