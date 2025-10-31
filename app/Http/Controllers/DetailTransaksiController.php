<?php
namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $details = DetailTransaksi::with(['transaksi.pelanggan', 'produk', 'transaksi.pembayaran'])->get();
        return view('detail_transaksi.index', compact('details'));

    }

    public function create()
    {
        $transaksis = Transaksi::all();
        $produks = Produk::all();
        return view('detail_transaksi.create', compact('transaksis', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        DetailTransaksi::create($request->all());
        return redirect()->route('detail_transaksi.index')->with('success', 'Detail transaksi berhasil ditambahkan.');
    }

    public function edit(DetailTransaksi $detailTransaksi)
    {
        $transaksis = Transaksi::all();
        $produks = Produk::all();
        return view('detail_transaksi.edit', compact('detailTransaksi', 'transaksis', 'produks'));
    }

    public function update(Request $request, DetailTransaksi $detailTransaksi)
    {
        $detailTransaksi->update($request->all());
        return redirect()->route('detail_transaksi.index');
    }

    public function destroy(DetailTransaksi $detailTransaksi)
    {
        $detailTransaksi->delete();
        return redirect()->route('detail_transaksi.index');
    }

    public function show($id)
    {
        $detail = DetailTransaksi::with(['transaksi.pelanggan', 'produk', 'transaksi.pembayaran'])->findOrFail($id);
        return view('detail_transaksi.show', compact('detail'));
    }
}
