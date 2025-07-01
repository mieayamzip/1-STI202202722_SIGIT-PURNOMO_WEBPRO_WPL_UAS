<?php

namespace App\Http\Controllers;

use App\Models\DataRumah;
use App\Models\Survey;
use App\Models\StatusRumah;
use App\Models\JenisRumah;
use App\Models\KondisiRumah;
use Illuminate\Http\Request;

class DataRumahController extends Controller
{
    public function index()
    {
        $rumahs = DataRumah::with(['statusRumah', 'jenisRumah', 'kondisiRumah'])->get();
        return view('rumah.index', compact('rumahs'));
    }

    public function create(Request $request)
    {
        $survey_id = $request->survey; // dari ?survey=xxx
        $survey = Survey::findOrFail($survey_id);
        $status_rumah = StatusRumah::all();
        $jenis_rumah = JenisRumah::all();
        $kondisi_rumah = KondisiRumah::all();

        return view('rumah.form', compact('survey', 'status_rumah', 'jenis_rumah', 'kondisi_rumah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'status_rumah_id' => 'required|exists:status_rumah,id',
            'jenis_rumah_id' => 'required|exists:jenis_rumah,id',
            'kondisi_rumah_id' => 'required|exists:kondisi_rumah,id',
            'luas_rumah' => 'required|numeric|min:0',
        ]);

        DataRumah::create($request->all());

        return redirect()->route('kendaraan.create', ['survey' => $request->survey_id])
            ->with('success', 'Data rumah berhasil disimpan. Silakan lengkapi data kendaraan.');
    }

    public function edit(DataRumah $rumah)
    {
        $survey = $rumah->survey;
        $status_rumah = StatusRumah::all();
        $jenis_rumah = JenisRumah::all();
        $kondisi_rumah = KondisiRumah::all();

        return view('rumah.form', compact('rumah', 'survey', 'status_rumah', 'jenis_rumah', 'kondisi_rumah'));
    }

    public function update(Request $request, DataRumah $rumah)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'status_rumah_id' => 'required|exists:status_rumah,id',
            'jenis_rumah_id' => 'required|exists:jenis_rumah,id',
            'kondisi_rumah_id' => 'required|exists:kondisi_rumah,id',
            'luas_rumah' => 'required|numeric|min:0',
        ]);

        $rumah->update($request->all());

        $kendaraan = \App\Models\DataKendaraan::where('survey_id', $request->survey_id)->first();

        if ($kendaraan) {
            return redirect()->route('kendaraan.edit', $kendaraan->id)
                ->with('success', 'Data rumah berhasil diperbarui. Silakan edit data kendaraan.');
        }

        return redirect()->route('kendaraan.create', ['survey' => $request->survey_id])
            ->with('success', 'Data rumah berhasil diperbarui. Silakan lengkapi data kendaraan.');
    }

    public function show($id)
    {
        $rumah = DataRumah::with(['survey', 'statusRumah', 'jenisRumah', 'kondisiRumah'])->findOrFail($id);
        return view('rumah.show', compact('rumah'));
    }

    public function destroy(DataRumah $rumah)
    {
        $rumah->delete();
        return redirect()->route('rumah.index')->with('success', 'Data rumah berhasil dihapus.');
    }
}
