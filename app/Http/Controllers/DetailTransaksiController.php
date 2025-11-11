<?php
namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailTransaksi::with(['transaksi.pelanggan', 'produk', 'transaksi.pembayaran']);

        // Search berdasarkan ID
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('id', 'like', '%' . $search . '%');
        }

        $details = $query->paginate(10);
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

        $produk = Produk::findOrFail($request->produk_id);

        // Cek apakah stok cukup
        if ($produk->stok < $request->jumlah) {
            return redirect()->back()->withErrors(['produk_id' => 'Maaf, produk yang Anda inginkan telah kehabisan stok atau stok tidak mencukupi.'])->withInput();
        }

        // Kurangi stok produk
        $produk->stok -= $request->jumlah;
        $produk->save();

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
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        // Hitung perubahan jumlah
        $jumlahLama = $detailTransaksi->jumlah;
        $jumlahBaru = $request->jumlah;
        $perubahan = $jumlahBaru - $jumlahLama;

        // Cek apakah stok cukup untuk perubahan
        if ($produk->stok < $perubahan) {
            return redirect()->back()->withErrors(['produk_id' => 'Maaf, produk yang Anda inginkan telah kehabisan stok atau stok tidak mencukupi.'])->withInput();
        }

        // Update stok produk
        $produk->stok -= $perubahan;
        $produk->save();

        $detailTransaksi->update($request->all());
        return redirect()->route('detail_transaksi.index')->with('success', 'Detail transaksi berhasil diperbarui.');
    }

    public function destroy(DetailTransaksi $detailTransaksi)
    {
        // Kembalikan stok produk saat detail transaksi dihapus
        $produk = $detailTransaksi->produk;
        $produk->stok += $detailTransaksi->jumlah;
        $produk->save();

        $detailTransaksi->delete();
        return redirect()->route('detail_transaksi.index')->with('success', 'Detail transaksi berhasil dihapus.');
    }

    public function show($id)
    {
        $detail = DetailTransaksi::with(['transaksi.pelanggan', 'produk', 'transaksi.pembayaran'])->findOrFail($id);
        return view('detail_transaksi.show', compact('detail'));
    }
}
