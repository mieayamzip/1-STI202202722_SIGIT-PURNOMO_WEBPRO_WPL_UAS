<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Skor;
use App\Models\DataKeluarga;
use App\Models\DataRumah;
use App\Models\DataKendaraan;
use App\Models\StatusRumah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSurvey = Survey::count();
        $surveyLayak = Skor::where('kelayakan', 'Layak')->count();
        $surveyTidakLayak = Skor::where('kelayakan', 'Tidak Layak')->count();
        $surveyBelumLengkap = $totalSurvey - ($surveyLayak + $surveyTidakLayak);

        // tambahan untuk dashboard clean & kompleks
        $keluarga_count = DataKeluarga::count();
        $rumah_count = DataRumah::count();
        $kendaraan_count = DataKendaraan::count();

        // data status rumah untuk chart
        $status_rumah_labels = StatusRumah::pluck('status');
        $status_rumah_counts = DataRumah::selectRaw('status_rumah_id, count(*) as total')
            ->groupBy('status_rumah_id')
            ->pluck('total');

        // survey terbaru untuk tabel
        $latest_surveys = Survey::latest()->take(5)->with('skor')->get();

        return view('dashboard.index', compact(
            'totalSurvey',
            'surveyLayak',
            'surveyTidakLayak',
            'surveyBelumLengkap',
            'keluarga_count',
            'rumah_count',
            'kendaraan_count',
            'status_rumah_labels',
            'status_rumah_counts',
            'latest_surveys'
        ));
    }
}
