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
            'status' => 'required|in:pending,proses,selesai,batal'
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

}