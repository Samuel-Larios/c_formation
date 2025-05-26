<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Site;
use App\Models\Student;
use App\Models\Promotion;
use App\Models\Subvention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    // Nombre d'étudiants inscrits sur un site pendant une période
    public function studentsBySiteAndPeriod(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $site = Site::findOrFail($request->site_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $count = Student::where('site_id', $request->site_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return view('statistics.students_by_site_period', compact('count', 'site', 'startDate', 'endDate'));
    }

    // Nombre d'étudiants inscrits par site et promotion pendant une période
    public function studentsBySitePromotionAndPeriod(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'promotion_id' => 'required|exists:promotions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $site = Site::findOrFail($request->site_id);
        $promotion = Promotion::findOrFail($request->promotion_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $count = DB::table('promotion_apprenant')
            ->where('site_id', $request->site_id)
            ->where('promotion_id', $request->promotion_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return view('statistics.students_by_site_promotion_period',
            compact('count', 'site', 'promotion', 'startDate', 'endDate'));
    }

    // Nombre d'étudiants ayant reçu une subvention sur un site
    public function studentsWithSubventionBySite(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id'
        ]);

        $site = Site::findOrFail($request->site_id);

        $count = Subvention::where('site_id', $request->site_id)
            ->distinct('student_id')
            ->count('student_id');

        return view('statistics.students_with_subvention_by_site', compact('count', 'site'));
    }

    // Nombre d'étudiants ayant reçu une subvention par site et promotion
    public function studentsWithSubventionBySiteAndPromotion(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'promotion_id' => 'required|exists:promotions,id'
        ]);

        $site = Site::findOrFail($request->site_id);
        $promotion = Promotion::findOrFail($request->promotion_id);

        $count = DB::table('subventions')
            ->join('promotion_apprenant', 'subventions.student_id', '=', 'promotion_apprenant.student_id')
            ->where('subventions.site_id', $request->site_id)
            ->where('promotion_apprenant.promotion_id', $request->promotion_id)
            ->distinct('subventions.student_id')
            ->count('subventions.student_id');

        return view('statistics.students_with_subvention_by_site_promotion',
            compact('count', 'site', 'promotion'));
    }
}
