<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Kategori;

/**
 * CONTROLLER: KATEGORI PRODUK (BACKEND/ADMIN)
 *
 * Mengatur operasi CRUD (Create, Read, Update, Delete)
 * untuk data kategori produk di panel admin.
 */
class KategoriController extends Controller
{
    /**
     * TAMPILKAN DAFTAR SEMUA KATEGORI
     *
     * Mengambil semua kategori dari database, diurutkan
     * berdasarkan nama secara abjad (A-Z), lalu ditampilkan ke view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua kategori, urutkan dari A-Z berdasarkan nama_kategori
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('backend.kategori.index', [
            'judul'    => 'Data Kategori',
            'kategori' => $kategori
        ]);
    }

    /**
     * TAMPILKAN FORM TAMBAH KATEGORI BARU
     *
     * Menampilkan halaman form kosong untuk mengisi data kategori baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('backend.kategori.create', [
            'judul' => 'Tambah Kategori'
        ]);
    }

    /**
     * SIMPAN KATEGORI BARU KE DATABASE
     *
     * Memvalidasi input lalu menyimpan kategori baru.
     * Slug dibuat otomatis dari nama kategori menggunakan method makeSlug().
     *
     * @param  \Illuminate\Http\Request  $request  Data dari form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi: nama_kategori wajib diisi dan maksimal 255 karakter
        $request->validate([
            'nama_kategori' => 'required|max:255'
        ]);

        // Simpan kategori baru ke database
        // slug dibuat otomatis dari nama_kategori (contoh: "Kaos Polos" -> "kaos-polos")
        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'slug'          => $this->makeSlug($request->nama_kategori),
        ]);

        // Kembali ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('backend.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * TAMPILKAN FORM EDIT KATEGORI
     *
     * Mencari kategori berdasarkan ID lalu menampilkan form yang sudah terisi data.
     * Jika ID tidak ditemukan, otomatis return 404.
     *
     * @param  int  $id  ID kategori yang akan diedit
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // findOrFail(): cari kategori by ID, jika tidak ada -> throw 404
        $kategori = Kategori::findOrFail($id);

        return view('backend.kategori.edit', [
            'judul'    => 'Edit Kategori',
            'kategori' => $kategori
        ]);
    }

    /**
     * PERBARUI DATA KATEGORI DI DATABASE
     *
     * Memvalidasi input lalu memperbarui nama dan slug kategori.
     * Slug diperbarui otomatis, dengan mengabaikan ID kategori saat ini
     * agar tidak dianggap duplikat dengan dirinya sendiri.
     *
     * @param  \Illuminate\Http\Request  $request  Data baru dari form
     * @param  int  $id  ID kategori yang akan diperbarui
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi: nama_kategori wajib diisi dan maksimal 255 karakter
        $request->validate([
            'nama_kategori' => 'required|max:255'
        ]);

        // Update data kategori. makeSlug() dipanggil dengan $id agar tidak cek duplikat dengan dirinya sendiri
        Kategori::where('id', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            'slug'          => $this->makeSlug($request->nama_kategori, $id),
        ]);

        // Kembali ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('backend.kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * HAPUS KATEGORI DARI DATABASE
     *
     * Mencari dan menghapus kategori berdasarkan ID.
     * Jika ID tidak ditemukan, otomatis return 404.
     *
     * @param  int  $id  ID kategori yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // findOrFail(): cari kategori by ID, jika tidak ada -> throw 404
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('backend.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }

    /**
     * BUAT SLUG UNIK DARI NAMA KATEGORI
     *
     * Mengubah nama menjadi format slug (huruf kecil, tanda hubung),
     * lalu memastikan slug tersebut unik di database.
     * Jika sudah ada, tambahkan angka di belakang (contoh: kaos-polos-2).
     *
     * @param  string    $nama      Nama kategori (contoh: "Kaos Polos")
     * @param  int|null  $ignoreId  ID yang dikecualikan dari pengecekan duplikat (saat edit)
     * @return string               Slug unik (contoh: "kaos-polos")
     */
    protected function makeSlug(string $nama, $ignoreId = null)
    {
        // Str::slug(): ubah nama menjadi slug URL-friendly (contoh: "Kaos Polos" -> "kaos-polos")
        $slug  = Str::slug($nama);
        $base  = $slug;
        $count = 1;

        // Loop: cek apakah slug sudah dipakai di database
        // Jika iya, tambahkan angka di belakang (-2, -3, dst) sampai unik
        while (Kategori::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId)) // Abaikan ID saat ini (untuk edit)
            ->exists()) {
            $count++;
            $slug = $base . '-' . $count; // Contoh: kaos-polos-2, kaos-polos-3, dst
        }

        return $slug;
    }
}