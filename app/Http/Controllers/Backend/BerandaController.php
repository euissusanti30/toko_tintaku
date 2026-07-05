<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * CONTROLLER: BERANDA BACKEND (DASHBOARD ADMIN)
 *
 * Mengatur tampilan halaman utama panel admin.
 * Menampilkan statistik transaksi, total pendapatan,
 * dan data grafik penjualan 30 hari terakhir.
 */
class BerandaController extends Controller
{
    /**
     * TAMPILKAN DASHBOARD UTAMA ADMIN
     *
     * Mengumpulkan semua data statistik dan grafik lalu
     * mengirimkannya ke tampilan halaman beranda admin.
     *
     * @return \Illuminate\View\View
     */
    public function berandaBackend()
    {
        // Hitung jumlah transaksi per status (belum bayar, sudah bayar, selesai, dll)
        // groupBy('status'): kelompokkan berdasarkan kolom status
        // pluck('total', 'status'): ambil hasil sebagai array [status => jumlah]
        $statusCounts = Transaksi::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Hitung total seluruh transaksi yang ada di database
        $totalTransaksi = Transaksi::count();

        // Hitung total pendapatan hanya dari transaksi berstatus 'sudah bayar' atau 'selesai'
        // whereIn(): filter status yang ada dalam array
        // sum('total_harga'): jumlahkan semua nilai kolom total_harga
        $totalPendapatan = Transaksi::whereIn('status', ['sudah bayar', 'selesai'])
            ->sum('total_harga');

        // Ambil data harian untuk grafik selama 30 hari terakhir
        // DATE(created_at): ambil hanya bagian tanggal (tanpa jam/menit/detik)
        // count(*) as jumlah: jumlah transaksi per hari
        // sum(total_harga) as total: total nominal per hari
        $chartData = Transaksi::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('count(*) as jumlah'),
                DB::raw('sum(total_harga) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30)) // Filter 30 hari ke belakang
            ->groupBy('tanggal')   // Kelompokkan per hari
            ->orderBy('tanggal')   // Urutkan dari paling lama ke paling baru
            ->get();

        // Format label sumbu X grafik menjadi "01 Jun", "02 Jun", dst
        $chartLabels = $chartData->pluck('tanggal')->map(function ($d) {
            return Carbon::parse($d)->format('d M');
        })->toArray();

        // Data jumlah transaksi per hari (untuk grafik batang/garis)
        $chartJumlah = $chartData->pluck('jumlah')->toArray();

        // Data total nominal per hari (untuk grafik pendapatan)
        $chartTotal  = $chartData->pluck('total')->toArray();

        // Kirim semua data ke view backend.beranda.index
        return view('backend.beranda.index', [
            'judul'          => 'Beranda',
            'sub'            => 'Halaman Beranda',
            'statusCounts'   => $statusCounts,    // Jumlah transaksi per status
            'totalTransaksi' => $totalTransaksi,  // Total semua transaksi
            'totalPendapatan'=> $totalPendapatan, // Total pendapatan
            'chartLabels'    => $chartLabels,     // Label tanggal untuk sumbu X grafik
            'chartJumlah'    => $chartJumlah,     // Data jumlah transaksi per hari
            'chartTotal'     => $chartTotal,      // Data total pendapatan per hari
        ]);
    }

    /**
     * TAMPILKAN DAFTAR PRODUK AKTIF DI BERANDA
     *
     * Menampilkan produk dengan status aktif (status = 1),
     * diurutkan dari yang terbaru diperbarui, dengan paginasi 6 per halaman.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil produk yang aktif (status = 1), urutkan dari terbaru, tampilkan 6 per halaman
        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6);

        return view('backend.beranda.index', [
            'judul' => 'Halaman Beranda',
            'produk' => $produk,
        ]);
    }
}
