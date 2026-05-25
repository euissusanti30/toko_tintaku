<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    public function __construct()
{
    Config::$serverKey = 'Mid-server-yewGUKcBcQnfOoFZy28beotu';
    Config::$clientKey = 'Mid-client-pjdY_1lOi8EBrRUF';
    Config::$isProduction = false;
    Config::$isSanitized = true;
    Config::$is3ds = true;
}
    public function index()
    {
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->latest()
            ->get();

        return view('backend.transaksi.index', [
            'judul' => 'Data Transaksi',
            'transaksi' => $transaksi
        ]);
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk')
            ->findOrFail($id);

        return view('backend.transaksi.show', [
            'judul' => 'Detail Transaksi',
            'transaksi' => $transaksi
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum bayar,sudah bayar,pending,proses,selesai,batal'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = $request->status;
        $transaksi->save();

        return redirect()->route('backend.transaksi.index')
            ->with('success', 'Status transaksi berhasil diupdate');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->detailTransaksi()->delete();
        $transaksi->delete();

        return redirect()->route('backend.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    public function checkoutLangsung($id)
{
    $produk = \App\Models\Produk::findOrFail($id);

    $params = [
        'transaction_details' => [
            'order_id' => 'ORDER-' . rand(),
            'gross_amount' => $produk->harga,
        ],
    ];

    $snapToken = \Midtrans\Snap::getSnapToken($params);

    return view('v_checkout.index', [
        'checkoutLangsung' => true,
        'produk' => $produk,
        'snapToken' => $snapToken
    ]);
}

    public function payment()
{
    $total = 90000;

    $params = [
        'transaction_details' => [
            'order_id' => rand(),
            'gross_amount' => $total,
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    return view('v_checkout.index', compact('snapToken'));
}

    /**
     * EKSPOR DATA TRANSAKSI KE EXCEL (CSV)
     * 
     * Method ini menarik semua data transaksi yang ada di database beserta rincian item produknya,
     * lalu menyajikannya dalam bentuk unduhan file spreadsheet CSV yang sepenuhnya kompatibel dengan Microsoft Excel.
     * 
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportExcel()
    {
        // 1. Ambil semua data transaksi, gunakan Eager Loading (with) untuk relasi detailTransaksi dan produk agar performa cepat
        $transaksi = Transaksi::with('detailTransaksi.produk')->latest()->get();

        // 2. Tentukan nama file unduhan yang dinamis menggunakan tanggal & waktu saat ini
        $fileName = 'data_transaksi_' . date('Y-m-d_H-i-s') . '.csv';

        // 3. Siapkan header response HTTP untuk memicu unduhan file CSV
        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // 4. Definisikan nama kolom header spreadsheet
        $columns = array('No', 'Kode Transaksi', 'Nama Customer', 'Email', 'Telepon', 'Alamat', 'Daftar Produk', 'Total Harga', 'Status', 'Tanggal Transaksi');

        // 5. Gunakan callback streaming agar data ditulis langsung ke output buffer tanpa memakan banyak memori server
        $callback = function() use($transaksi, $columns) {
            // Membuka output stream PHP
            $file = fopen('php://output', 'w');
            
            // Tambahkan UTF-8 BOM (Byte Order Mark) di awal file agar Excel otomatis mengenali encoding UTF-8 dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Tulis baris header kolom dengan delimiter titik koma (;) yang merupakan standar Excel regional Indonesia/Eropa
            fputcsv($file, $columns, ';');

            // 6. Ulangi setiap transaksi dan tulis datanya ke file
            foreach ($transaksi as $index => $row) {
                // Susun string berisi daftar produk dan kuantitasnya
                $produkList = [];
                foreach ($row->detailTransaksi as $detail) {
                    $produkList[] = ($detail->produk->nama_produk ?? 'Produk tidak ditemukan') . ' (' . $detail->qty . 'x)';
                }
                $produkStr = implode(', ', $produkList);

                // Tulis satu baris transaksi ke file CSV
                fputcsv($file, array(
                    $index + 1,
                    '#' . str_pad($row->id, 6, '0', STR_PAD_LEFT), // Format ID Pesanan agar seragam 6 digit
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

            // Tutup output stream
            fclose($file);
        };

        // Kembalikan response streaming dengan file CSV
        return response()->stream($callback, 200, $headers);
    }

}