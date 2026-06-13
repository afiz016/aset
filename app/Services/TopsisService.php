<?php

namespace App\Services;

use App\Models\AsetDigital;
use App\Models\Kriteria;
use App\Models\Penilaian;

class TopsisService
{
    public function hitungTopsis()
    {
        $asetDigitals = AsetDigital::all();
        $kriterias = Kriteria::all();
        
        if ($asetDigitals->isEmpty() || $kriterias->isEmpty()) return [];

        // 1. Matriks Keputusan (X)
        $matriksX = [];
        foreach ($asetDigitals as $aset) {
            foreach ($kriterias as $kriteria) {
                $penilaian = Penilaian::where('aset_digital_id', $aset->id)
                                      ->where('kriteria_id', $kriteria->id)
                                      ->first();
                $matriksX[$aset->id][$kriteria->id] = $penilaian ? $penilaian->nilai : 0;
            }
        }

        // 2. Normalisasi Matriks (R)
        $pembagi = [];
        foreach ($kriterias as $kriteria) {
            $totalKuadrat = 0;
            foreach ($asetDigitals as $aset) {
                $totalKuadrat += pow($matriksX[$aset->id][$kriteria->id], 2);
            }
            $pembagi[$kriteria->id] = sqrt($totalKuadrat);
        }

        $matriksR = [];
        foreach ($asetDigitals as $aset) {
            foreach ($kriterias as $kriteria) {
                $denominasi = $pembagi[$kriteria->id];
                $matriksR[$aset->id][$kriteria->id] = $denominasi > 0 ? ($matriksX[$aset->id][$kriteria->id] / $denominasi) : 0;
            }
        }

        // 3. Bobot Ternormalisasi (V)
        $matriksV = [];
        foreach ($asetDigitals as $aset) {
            foreach ($kriterias as $kriteria) {
                $matriksV[$aset->id][$kriteria->id] = $matriksR[$aset->id][$kriteria->id] * $kriteria->bobot;
            }
        }

        // 4. Solusi Ideal Positif (A+) & Negatif (A-)
        $solusiIdealPositif = [];
        $solusiIdealNegatif = [];
        foreach ($kriterias as $kriteria) {
            $nilaiKolom = array_column($matriksV, $kriteria->id);
            if (strtolower($kriteria->jenis) == 'benefit') {
                $solusiIdealPositif[$kriteria->id] = max($nilaiKolom);
                $solusiIdealNegatif[$kriteria->id] = min($nilaiKolom);
            } else {
                $solusiIdealPositif[$kriteria->id] = min($nilaiKolom);
                $solusiIdealNegatif[$kriteria->id] = max($nilaiKolom);
            }
        }

        // 5. Jarak & Nilai Preferensi (V_i)
        $jarakSolusiIdealPositif = [];
        $jarakSolusiIdealNegatif = [];
        $hasilAkhir = [];
        foreach ($asetDigitals as $aset) {
            $jarakPositif = 0;
            $jarakNegatif = 0;
            foreach ($kriterias as $kriteria) {
                $jarakPositif += pow($matriksV[$aset->id][$kriteria->id] - $solusiIdealPositif[$kriteria->id], 2);
                $jarakNegatif += pow($matriksV[$aset->id][$kriteria->id] - $solusiIdealNegatif[$kriteria->id], 2);
            }
            $dPlus = sqrt($jarakPositif);
            $dMinus = sqrt($jarakNegatif);
            $nilaiPreferensi = ($dMinus + $dPlus) > 0 ? ($dMinus / ($dMinus + $dPlus)) : 0;

            $jarakSolusiIdealPositif[$aset->id] = $dPlus;
            $jarakSolusiIdealNegatif[$aset->id] = $dMinus;

            $hasilAkhir[] = [
                'nama_aset' => $aset->nama_aset,
                'jenis_aset' => $aset->jenis_aset,
                'aset_id' => $aset->id,
                'd_plus' => $dPlus,
                'd_minus' => $dMinus,
                'preferensi' => $nilaiPreferensi
            ];
        }

        usort($hasilAkhir, function ($a, $b) {
            return $b['preferensi'] <=> $a['preferensi'];
        });

        return [
            'hasil_akhir' => $hasilAkhir,
            'matriks_x' => $matriksX,
            'matriks_r' => $matriksR,
            'matriks_v' => $matriksV,
            'solusi_ideal_positif' => $solusiIdealPositif,
            'solusi_ideal_negatif' => $solusiIdealNegatif,
            'jarak_solusi_ideal_positif' => $jarakSolusiIdealPositif,
            'jarak_solusi_ideal_negatif' => $jarakSolusiIdealNegatif,
            'kriterias' => $kriterias,
            'aset_digitals' => $asetDigitals
        ];
    }
}