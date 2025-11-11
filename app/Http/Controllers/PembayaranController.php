<?php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('transaksi');

        // Search berdasarkan kode transaksi
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('transaksi', function($q) use ($search) {
                $q->where('kode_transaksi', 'like', '%' . $search . '%');
            });
        }

        $pembayarans = $query->paginate(10);
        return view('pembayaran.index', compact('pembayarans'));
    }

    public function create()
{
    $transaksis = \App\Models\Transaksi::all(); // ambil semua transaksi
    return view('pembayaran.create', compact('transaksis'));
}


public function store(Request $request)
{
    $request->validate([
        'transaksi_id' => 'required',
        'jumlah_bayar' => 'required|numeric',
        'metode' => 'required'
    ]);

    // Ambil transaksi biar bisa hitung kembalian
    $transaksi = Transaksi::findOrFail($request->transaksi_id);

    Pembayaran::create([
        'transaksi_id' => $request->transaksi_id,
        'jumlah_bayar' => $request->jumlah_bayar,
        'metode' => $request->metode,
        'kembalian' => $request->jumlah_bayar - $transaksi->total_harga,
    ]);

    return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil ditambahkan!');
}






    public function edit(Pembayaran $pembayaran)
    {
        $transaksis = Transaksi::all();
        return view('pembayaran.edit', compact('pembayaran', 'transaksis'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $pembayaran->update($request->all());
        return redirect()->route('pembayaran.index');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('pembayaran.index');
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with('transaksi.pelanggan', 'transaksi.detailTransaksis.produk')->findOrFail($id);
        return view('pembayaran.show', compact('pembayaran'));
    }
}
