<?php

namespace App\Http\Controllers;

use App\Models\SurveyKepuasan;
use Illuminate\Http\Request;

class AdminSurveyController extends Controller
{
    public function index(Request $request)
    {
        $query = SurveyKepuasan::query();

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        $surveyList = $query->latest()->paginate(20)->withQueryString();

        $totalSurvey = SurveyKepuasan::count();
        $avgKemudahan = round(SurveyKepuasan::avg('nilai_kemudahan') ?: 0, 2);
        $avgKecepatan = round(SurveyKepuasan::avg('nilai_kecepatan') ?: 0, 2);
        $avgKeramahan = round(SurveyKepuasan::avg('nilai_keramahan') ?: 0, 2);
        $avgKemanfaatan = round(SurveyKepuasan::avg('nilai_kemanfaatan') ?: 0, 2);
        $indeksRataRata = round(($avgKemudahan + $avgKecepatan + $avgKeramahan + $avgKemanfaatan) / 4, 2);

        return view('admin.survey.index', compact(
            'surveyList',
            'totalSurvey',
            'avgKemudahan',
            'avgKecepatan',
            'avgKeramahan',
            'avgKemanfaatan',
            'indeksRataRata'
        ));
    }
}
