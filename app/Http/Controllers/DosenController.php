<?php
namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{

    public function index()
    {
        $dosen = Dosen::latest()->paginate(5);
        return view('dosen.index', compact('dosen'));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        //validate form
        $validated = $request->validate([
            'nama' => 'required|min:5',
            'nipd' => 'required|min:4',
        ]);

        $dosen       = new dosen();
        $dosen->nama = $request->nama;
        $dosen->nipd = $request->nipd;

        $dosen->save();
        return redirect()->route('dosen.index');

    }

    public function edit($id)
    {
        $dosen = dosen::findOrFail($id);
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|min:5',
            'nipd' => 'required|min:4',
        ]);

        $dosen       = dosen::findOrFail($id);
        $dosen->nama = $request->nama;
        $dosen->nipd = $request->nipd;

        $dosen->save();
        return redirect()->route('dosen.index');

    }

    public function destroy($id)
    {
        $dosen = dosen::findOrFail($id);
        $dosen->delete();
        return redirect()->route('dosen.index');

    }
}
