<?php
namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('pelanggan');

        // Search berdasarkan kode transaksi
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('kode_transaksi', 'like', '%' . $search . '%');
        }

        $transaksis = $query->paginate(10);

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
