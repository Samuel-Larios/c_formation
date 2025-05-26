<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\PromotionApprenant;
use Illuminate\Support\Facades\Auth;

class PromotionApprenantController extends Controller
{
    public function index()
    {
        $siteId = Auth::user()->site_id;

        // Charger les promotions avec leurs étudiants triés par first_name
        $promotions = Promotion::with(['students' => function($query) {
            $query->orderBy('first_name');
        }, 'promotionApprenants'])
            ->whereHas('promotionApprenants', function($query) use ($siteId) {
                $query->where('site_id', $siteId);
            })
            ->where('site_id', $siteId)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('promotion_apprenant.index', compact('promotions'));
    }

    public function create()
    {
        $siteId = Auth::user()->site_id;

        $promotions = Promotion::where('site_id', $siteId)
            ->orderByDesc('created_at')
            ->get();

        $assignedStudentIds = PromotionApprenant::where('site_id', $siteId)
            ->pluck('student_id')
            ->toArray();

        $students = Student::where('site_id', $siteId)
            ->whereNotIn('id', $assignedStudentIds)
            ->get();

        return view('promotion_apprenant.create', compact('promotions', 'students'));
    }

    public function getStudentsByPromotion(Request $request)
    {
        $promotionId = $request->input('promotion_id');
        $siteId = Auth::user()->site_id;

        $assignedStudentIds = PromotionApprenant::where('promotion_id', $promotionId)
            ->pluck('student_id')
            ->toArray();

        $students = Student::where('site_id', $siteId)
            ->whereNotIn('id', $assignedStudentIds)
            ->get(['id', 'first_name', 'last_name']);

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
        ]);

        $siteId = Auth::user()->site_id;
        $dataToInsert = [];

        foreach ($request->students as $studentId) {
            $dataToInsert[] = [
                'promotion_id' => $request->promotion_id,
                'student_id' => $studentId,
                'site_id' => $siteId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        PromotionApprenant::insert($dataToInsert);

        return redirect()->route('promotion_apprenant.index')
            ->with('success', 'Les étudiants ont été ajoutés à la promotion avec succès.');
    }

    public function edit($id)
    {
        $promotionApprenant = PromotionApprenant::with(['promotion', 'student'])
            ->where('site_id', Auth::user()->site_id)
            ->findOrFail($id);

        $siteId = Auth::user()->site_id;
        $promotions = Promotion::where('site_id', $siteId)->get();
        $students = Student::where('site_id', $siteId)->get();

        return view('promotion_apprenant.edit', compact('promotionApprenant', 'promotions', 'students'));
    }

    public function update(Request $request, PromotionApprenant $promotionApprenant)
    {
        if ($promotionApprenant->site_id !== Auth::user()->site_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'promotion_id' => 'required|exists:promotions,id,site_id,'.Auth::user()->site_id,
            'student_id' => 'required|exists:students,id,site_id,'.Auth::user()->site_id,
        ]);

        // Vérifier si la relation existe déjà
        $exists = PromotionApprenant::where('promotion_id', $request->promotion_id)
            ->where('student_id', $request->student_id)
            ->where('id', '!=', $promotionApprenant->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['student_id' => 'Cet étudiant est déjà dans cette promotion.']);
        }

        $promotionApprenant->update($request->all());

        return redirect()->route('promotion_apprenant.index')
            ->with('success', 'L\'association a été mise à jour avec succès.');
    }

    public function destroy(PromotionApprenant $promotionApprenant)
    {
        if ($promotionApprenant->site_id !== Auth::user()->site_id) {
            abort(403, 'Accès non autorisé.');
        }

        $promotionApprenant->delete();

        return redirect()->route('promotion_apprenant.index')
            ->with('success', 'L\'étudiant a été retiré de la promotion.');
    }
}
