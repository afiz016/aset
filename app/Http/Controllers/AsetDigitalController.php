<?php

namespace App\Http\Controllers;

use App\Models\AsetDigital;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Services\MarketplaceApiService;
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

    public function syncData()
    {
    $asets = AsetDigital::all();
    $successCount = 0;

    foreach ($asets as $aset) {
        $data = null;
        $platform = strtolower($aset->jenis_aset);

        // Deteksi jenis aset berdasarkan platform
        if ($platform === 'opensea') {
            $data = MarketplaceApiService::fetchOpenSeaData($aset->nama_aset);
        } elseif ($platform === 'steam market' || $platform === 'steam') {
            // Kita asumsikan App ID disimpan atau default CS2 (730) untuk kebutuhan instan
            $data = MarketplaceApiService::fetchSteamMarketData('730', $aset->nama_aset);
        }

        // Jika data berhasil ditarik, lakukan pembaharuan matriks kriteria
        if ($data && !isset($data['error'])) {
            $result = MarketplaceApiService::saveAsetFromAPI($data);
            if ($result['success']) {
                $successCount++;
            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => $successCount . ' aset digital berhasil diperbarui dengan data pasar terbaru!'
    ]);
    }
}