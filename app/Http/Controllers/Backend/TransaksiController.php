<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Midtrans\Config;
use Midtrans\Snap;

/**
 * CONTROLLER: MANAJEMEN TRANSAKSI (BACKEND/ADMIN)
 *
 * Mengatur pengelolaan data transaksi dari sisi admin.
 * Fitur: lihat semua transaksi, detail, ubah status,
 * hapus, integrasi payment Midtrans, dan ekspor CSV.
 */
class TransaksiController extends Controller
{
    /**
     * INISIALISASI KONFIGURASI MIDTRANS
     *
     * Dipanggil otomatis saat controller diakses.
     * Mengatur kredensial Midtrans untuk memproses pembayaran.
     */
    public function __construct()
    {
        Config::$serverKey    = 'Mid-server-yewGUKcBcQnfOoFZy28beotu'; // Kunci rahasia server
        Config::$clientKey    = 'Mid-client-pjdY_1lOi8EBrRUF';          // Kunci publik browser
        Config::$isProduction = false;  // false = sandbox/testing, true = live
        Config::$isSanitized  = true;   // Midtrans membersihkan input berbahaya
        Config::$is3ds        = true;   // Aktifkan keamanan 3D Secure
    }

    /**
     * TAMPILKAN DAFTAR SEMUA TRANSAKSI
     *
     * Mengambil semua transaksi beserta relasi detail & produk,
     * lalu ditampilkan di halaman daftar transaksi admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // with('detailTransaksi.produk'): eager loading relasi bersarang agar tidak N+1 query
        // latest(): urutkan dari yang paling baru
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->latest()
            ->get();

        return view('backend.transaksi.index', [
            'judul'     => 'Data Transaksi',
            'transaksi' => $transaksi
        ]);
    }

    /**
     * TAMPILKAN DETAIL SATU TRANSAKSI
     *
     * Menampilkan info lengkap satu transaksi termasuk daftar produk yang dibeli.
     *
     * @param  int  $id  ID transaksi
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // findOrFail(): jika ID tidak ditemukan -> otomatis return 404
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->findOrFail($id);

        return view('backend.transaksi.show', [
            'judul'     => 'Detail Transaksi',
            'transaksi' => $transaksi
        ]);
    }

    /**
     * PERBARUI STATUS TRANSAKSI
     *
     * Admin mengubah status ke salah satu:
     * belum bayar | sudah bayar | pending | proses | selesai | batal
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi: status harus salah satu nilai yang diizinkan
        $request->validate([
            'status' => 'required|in:belum bayar,sudah bayar,pending,proses,selesai,batal'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = $request->status;
        $transaksi->save();

        return redirect()->route('backend.transaksi.index')
            ->with('success', 'Status transaksi berhasil diupdate');
    }

    /**
     * HAPUS TRANSAKSI BESERTA DETAIL-NYA
     *
     * Hapus detail_transaksi dahulu (child) lalu baru hapus transaksi utama (parent)
     * untuk menjaga integritas foreign key database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Hapus child (detail) dulu, baru hapus parent (transaksi)
        $transaksi->detailTransaksi()->delete();
        $transaksi->delete();

        return redirect()->route('backend.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * CHECKOUT LANGSUNG SATU PRODUK (TANPA KERANJANG)
     *
     * Buat Snap Token Midtrans untuk checkout produk secara langsung.
     *
     * @param  int  $id  ID produk
     * @return \Illuminate\View\View
     */
    public function checkoutLangsung($id)
    {
        $produk = \App\Models\Produk::findOrFail($id);

        // Siapkan parameter transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . rand(), // ID pesanan unik
                'gross_amount' => $produk->harga,    // Total = harga produk
            ],
        ];

        // Minta Snap Token dari API Midtrans (digunakan membuka popup pembayaran)
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('v_checkout.index', [
            'checkoutLangsung' => true,
            'produk'           => $produk,
            'snapToken'        => $snapToken
        ]);
    }

    /**
     * HALAMAN PEMBAYARAN DEMO
     *
     * Method uji coba integrasi Midtrans Snap dengan nominal tetap Rp 90.000.
     *
     * @return \Illuminate\View\View
     */
    public function payment()
    {
        $total = 90000; // Nominal demo

        $params = [
            'transaction_details' => [
                'order_id'     => rand(),  // ID acak untuk demo
                'gross_amount' => $total,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('v_checkout.index', compact('snapToken'));
    }

    /**
     * EKSPOR DATA TRANSAKSI KE FILE CSV (EXCEL)
     *
     * Menghasilkan unduhan file CSV semua transaksi beserta detail produk.
     * Menggunakan streaming response agar hemat memori server.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportExcel()
    {
        // Ambil semua transaksi dengan eager loading relasi
        $transaksi = Transaksi::with('detailTransaksi.produk')->latest()->get();

        // Nama file dinamis berdasarkan tanggal & waktu
        $fileName = 'data_transaksi_' . date('Y-m-d_H-i-s') . '.csv';

        // Header HTTP untuk memicu browser mendownload file
        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Nama kolom di header spreadsheet
        $columns = array('No', 'Kode Transaksi', 'Nama Customer', 'Email', 'Telepon', 'Alamat', 'Daftar Produk', 'Total Harga', 'Status', 'Tanggal Transaksi');

        // Streaming callback: tulis data langsung ke output buffer
        $callback = function() use($transaksi, $columns) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar Excel membaca karakter Indonesia dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Tulis baris header kolom (delimiter titik koma = standar Excel Indonesia)
            fputcsv($file, $columns, ';');

            foreach ($transaksi as $index => $row) {
                // Susun daftar produk: "Kaos Polos (2x), Celana (1x)"
                $produkList = [];
                foreach ($row->detailTransaksi as $detail) {
                    $produkList[] = ($detail->produk->nama_produk ?? 'Produk tidak ditemukan') . ' (' . $detail->qty . 'x)';
                }
                $produkStr = implode(', ', $produkList);

                fputcsv($file, array(
                    $index + 1,
                    '#' . str_pad($row->id, 6, '0', STR_PAD_LEFT), // Format ID: #000001
                    $row->nama_customer,
                    $row->email,
                    $row->telepon,
                    $row->alamat,
                    $produkStr,
                    $row->total_harga,
                    $row->status,
                    $row->created_at->format('Y-m-d H:i:s')
                ), ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}