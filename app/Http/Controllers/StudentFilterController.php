<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Promotion;
use App\Models\Specialite;
use App\Models\PromotionApprenant;
use Illuminate\Support\Facades\Auth;

class StudentFilterController extends Controller
{
    public function showFilterForm()
    {
        $siteId = session('site_id');

        return view('students.filter_form', [ // Assurez-vous que c'est le bon nom de fichier
            'promotions' => Promotion::where('site_id', $siteId)->get(),
            'specializations' => Specialite::where('site_id', $siteId)->get()
        ]);
    }

    public function filterResults(Request $request)
    {
        $siteId = session('site_id');

        $query = Student::with(['site', 'specializations'])
            ->where('site_id', $siteId)
            ->orderBy('created_at', 'desc');

        if ($request->promotion_id) {
            $studentIds = PromotionApprenant::where('promotion_id', $request->promotion_id)
                ->pluck('student_id');
            $query->whereIn('id', $studentIds);
        }

        if ($request->specialization_id) {
            $query->whereHas('specializations', function($q) use ($request) {
                $q->where('specialite_id', $request->specialization_id);
            });
        }

        return view('students.filter_results', [ // Assurez-vous que c'est le bon nom de fichier
            'students' => $query->paginate(12)->appends($request->query()),
            'promotions' => Promotion::where('site_id', $siteId)->get(),
            'specializations' => Specialite::where('site_id', $siteId)->get(),
            'promotionId' => $request->promotion_id,
            'specializationId' => $request->specialization_id
        ]);
    }
}
