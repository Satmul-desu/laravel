<?php
namespace App\Http\Controllers;

use App\Models\HistoryReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Statistik utama
        $totalPelanggan = \App\Models\Pelanggan::count();
        $totalProduk = \App\Models\Produk::count();
        $totalTransaksiBulanIni = \App\Models\Transaksi::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalPendapatanBulanIni = \App\Models\Transaksi::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('total_harga');

        // Data untuk grafik transaksi per bulan (12 bulan terakhir)
        $transaksiPerBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = \App\Models\Transaksi::whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year)->count();
            $transaksiPerBulan[] = [
                'bulan' => $date->format('M Y'),
                'jumlah' => $count
            ];
        }

        return view('home', compact('totalPelanggan', 'totalProduk', 'totalTransaksiBulanIni', 'totalPendapatanBulanIni', 'transaksiPerBulan'));
    }

    /**
     * Reset all data in the system.
     */
    public function resetAllData(Request $request)
    {
        // Log the reset action
        HistoryReset::create([
            'reset_at' => now(),
            'table_name' => 'all',
            'user_id' => auth()->id(),
        ]);

        // Truncate tables in correct order to avoid foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_transaksis')->truncate();
        DB::table('pembayarans')->truncate();
        DB::table('transaksis')->truncate();
        DB::table('produks')->truncate();
        DB::table('pelanggans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', 'Semua data telah direset.');
    }

    /**
     * Reset data for a specific table.
     */
    public function resetTableData(Request $request, $table)
    {
        $allowedTables = ['pelanggans', 'produks', 'transaksis', 'detail_transaksis', 'pembayarans'];

        if (!in_array($table, $allowedTables)) {
            return redirect()->back()->with('error', 'Tabel tidak valid.');
        }

        // Log the reset action
        HistoryReset::create([
            'reset_at' => now(),
            'table_name' => $table,
            'user_id' => auth()->id(),
        ]);

        // Truncate the specific table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tableNames = [
            'pelanggans' => 'Pelanggan',
            'produks' => 'Produk',
            'transaksis' => 'Transaksi',
            'detail_transaksis' => 'Detail Transaksi',
            'pembayarans' => 'Pembayaran',
        ];

        return redirect()->back()->with('success', 'Data ' . $tableNames[$table] . ' telah direset.');
    }
}
