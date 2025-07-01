<?php

namespace App\Http\Controllers;

use App\Models\DataKendaraan;
use App\Models\JenisKendaraan;
use App\Models\Survey;
use Illuminate\Http\Request;

class DataKendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = DataKendaraan::with('survey', 'jenisKendaraan')->get();
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function create(Request $request)
    {
        $survey_id = $request->survey;
        $survey = Survey::findOrFail($survey_id);
        $jenis_kendaraan = JenisKendaraan::all();

        return view('kendaraan.form', compact('survey', 'jenis_kendaraan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'kendaraan.*.jenis_kendaraan_id' => 'required|exists:jenis_kendaraan,id',
            'kendaraan.*.jumlah_kendaraan' => 'required|integer|min:0',
        ]);

        foreach ($request->kendaraan as $kendaraan) {
            DataKendaraan::create([
                'survey_id' => $request->survey_id,
                'jenis_kendaraan_id' => $kendaraan['jenis_kendaraan_id'],
                'jumlah_kendaraan' => $kendaraan['jumlah_kendaraan'],
            ]);
        }

        return app(\App\Http\Controllers\SkorController::class)->store(new \Illuminate\Http\Request(['survey_id' => $request->survey_id]));
    }

    public function edit(DataKendaraan $kendaraan)
    {
        $survey = $kendaraan->survey;
        $jenis_kendaraan = JenisKendaraan::all();

        return view('kendaraan.form', compact('kendaraan', 'survey', 'jenis_kendaraan'));
    }

    public function update(Request $request, DataKendaraan $kendaraan)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'jenis_kendaraan_id' => 'required|exists:jenis_kendaraan,id',
            'jumlah_kendaraan' => 'required|integer|min:0',
        ]);

        $kendaraan->update([
            'survey_id' => $request->survey_id,
            'jenis_kendaraan_id' => $request->jenis_kendaraan_id,
            'jumlah_kendaraan' => $request->jumlah_kendaraan,
        ]);

        return app(\App\Http\Controllers\SkorController::class)->store(new \Illuminate\Http\Request(['survey_id' => $request->survey_id]));
    }

    public function destroy(DataKendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('kendaraan.index')->with('success', 'Data kendaraan berhasil dihapus.');
    }
}
