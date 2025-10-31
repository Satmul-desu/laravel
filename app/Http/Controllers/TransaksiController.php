<?php
namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('pelanggan')->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        return view('transaksi.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        Transaksi::create($request->all());
        return redirect()->route('transaksi.index');
    }

    public function edit(Transaksi $transaksi)
    {
        $pelanggans = Pelanggan::all();
        return view('transaksi.edit', compact('transaksi', 'pelanggans'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $transaksi->update($request->all());
        return redirect()->route('transaksi.index');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('pelanggan', 'detailTransaksis.produk', 'pembayaran')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }
}
