<?php

namespace App\Http\Controllers;

use App\Models\AsetDigital;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class AsetDigitalController extends Controller
{
    public function index()
    {
        $asetDigitals = AsetDigital::with('penilaians.kriteria')->get();
        $kriterias = Kriteria::all();
        return view('aset_digital.index', compact('asetDigitals', 'kriterias'));
    }

    public function create()
    {
        $kriterias = Kriteria::all();
        return view('aset_digital.create', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'jenis_aset' => 'required|string|max:255',
            'nilai' => 'required|array',
        ]);

        $aset = AsetDigital::create([
            'nama_aset' => $request->nama_aset,
            'jenis_aset' => $request->jenis_aset,
        ]);

        foreach ($request->nilai as $kriteriaId => $val) {
            Penilaian::create([
                'aset_digital_id' => $aset->id,
                'kriteria_id' => $kriteriaId,
                'nilai' => $val,
            ]);
        }

        return redirect()->route('aset-digital.index')->with('success', 'Aset digital berhasil ditambahkan.');
    }

    public function show($id)
    {
        $aset = AsetDigital::with('penilaians.kriteria')->findOrFail($id);
        return view('aset_digital.show', compact('aset'));
    }

    public function edit($id)
    {
        $aset = AsetDigital::with('penilaians')->findOrFail($id);
        $kriterias = Kriteria::all();
        return view('aset_digital.edit', compact('aset', 'kriterias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'jenis_aset' => 'required|string|max:255',
            'nilai' => 'required|array',
        ]);

        $aset = AsetDigital::findOrFail($id);
        $aset->update([
            'nama_aset' => $request->nama_aset,
            'jenis_aset' => $request->jenis_aset,
        ]);

        foreach ($request->nilai as $kriteriaId => $val) {
            Penilaian::updateOrCreate(
                [
                    'aset_digital_id' => $aset->id,
                    'kriteria_id' => $kriteriaId,
                ],
                ['nilai' => $val]
            );
        }

        return redirect()->route('aset-digital.index')->with('success', 'Aset digital berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AsetDigital::findOrFail($id)->delete();
        return redirect()->route('aset-digital.index')->with('success', 'Aset digital berhasil dihapus.');
    }
}