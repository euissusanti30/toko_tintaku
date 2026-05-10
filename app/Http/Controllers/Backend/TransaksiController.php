<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class TransaksiController extends Controller
{
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
        
        // Delete related detail transaksi first
        $transaksi->detailTransaksi()->delete();
        $transaksi->delete();

        return redirect()->route('backend.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}
