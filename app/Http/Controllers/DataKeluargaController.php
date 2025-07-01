<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use App\Models\StatusPernikahan;
use App\Models\Survey;
use Illuminate\Http\Request;

class DataKeluargaController extends Controller
{
    public function index()
    {
        $keluargas = DataKeluarga::with(['survey', 'statusPernikahan'])->get();
        return view('keluarga.index', compact('keluargas'));
    }

    public function create(Request $request)
    {
        $survey_id = $request->survey;
        $survey = Survey::findOrFail($survey_id);
        $statusPernikahans = StatusPernikahan::all();

        return view('keluarga.form', compact('survey', 'statusPernikahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'status_pernikahan_id' => 'required|exists:status_pernikahan,id',
            'jumlah_anak' => 'required|integer|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
        ]);

        DataKeluarga::create($request->all());
        return redirect()->route('pekerjaan.create', ['survey' => $request->survey_id]);
    }

    public function edit(DataKeluarga $keluarga)
    {
        $survey = $keluarga->survey;
        $statusPernikahans = StatusPernikahan::all();

        return view('keluarga.form', compact('keluarga', 'survey', 'statusPernikahans'));
    }

    public function update(Request $request, DataKeluarga $keluarga)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'status_pernikahan_id' => 'required|exists:status_pernikahan,id',
            'jumlah_anak' => 'required|integer|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
        ]);

        $keluarga->update($request->all());

        $rumah = \App\Models\DataRumah::where('survey_id', $request->survey_id)->first();

        if ($rumah) {
            return redirect()->route('rumah.edit', $rumah->id)
                ->with('success', 'Data keluarga berhasil diperbarui. Silakan edit data rumah.');
        }

        return redirect()->route('rumah.create', ['survey' => $request->survey_id])
            ->with('success', 'Data keluarga berhasil diperbarui. Silakan lengkapi data rumah.');
    }

    public function destroy(DataKeluarga $keluarga)
    {
        $keluarga->delete();
        return redirect()->route('keluarga.index')->with('success', 'Data keluarga berhasil dihapus.');
    }
}
