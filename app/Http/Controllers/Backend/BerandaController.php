<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        // Hitung jumlah transaksi per status
        $statusCounts = Transaksi::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Total semua transaksi
        $totalTransaksi = Transaksi::count();

        // Total pendapatan dari transaksi yang sudah bayar / selesai
        $totalPendapatan = Transaksi::whereIn('status', ['sudah bayar', 'selesai'])
            ->sum('total_harga');

        // Data grafik: transaksi harian selama 30 hari terakhir
        $chartData = Transaksi::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('count(*) as jumlah'),
                DB::raw('sum(total_harga) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $chartLabels = $chartData->pluck('tanggal')->map(function ($d) {
            return Carbon::parse($d)->format('d M');
        })->toArray();

        $chartJumlah = $chartData->pluck('jumlah')->toArray();
        $chartTotal  = $chartData->pluck('total')->toArray();

        return view('backend.beranda.index', [
            'judul'          => 'Beranda',
            'sub'            => 'Halaman Beranda',
            'statusCounts'   => $statusCounts,
            'totalTransaksi' => $totalTransaksi,
            'totalPendapatan'=> $totalPendapatan,
            'chartLabels'    => $chartLabels,
            'chartJumlah'    => $chartJumlah,
            'chartTotal'     => $chartTotal,
        ]);
    }

    public function index()
    {
        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6);
        return view('backend.beranda.index', [
            'judul' => 'Halaman Beranda',
            'produk' => $produk,
        ]);
    }
}
