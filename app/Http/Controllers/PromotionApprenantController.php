<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\PromotionApprenant;
use Illuminate\Support\Facades\Auth;
use App\Exports\PromotionApprenantExport;
use Maatwebsite\Excel\Facades\Excel;

class PromotionApprenantController extends Controller
{
    public function index(Request $request)
    {
        $siteId = Auth::user()->site_id;

        // Récupérer les filtres
        $sexeFilter = $request->get('sexe');
        $promotionFilter = $request->get('promotion_id');

        // Charger les promotions avec leurs étudiants triés par first_name
        $promotionsQuery = Promotion::with(['students' => function($query) use ($sexeFilter) {
            $query->orderBy('first_name');
            if ($sexeFilter) {
                $query->where('sexe', $sexeFilter);
            }
        }, 'promotionApprenants'])
            ->whereHas('promotionApprenants', function($query) use ($siteId) {
                $query->where('site_id', $siteId);
            })
            ->where('site_id', $siteId);

        // Appliquer le filtre par promotion si spécifié
        if ($promotionFilter) {
            $promotionsQuery->where('id', $promotionFilter);
        }

        $promotions = $promotionsQuery->orderByDesc('created_at')->paginate(10);

        // Récupérer toutes les promotions pour le filtre
        $allPromotions = Promotion::where('site_id', $siteId)->orderByDesc('created_at')->get();

        // Pagination des étudiants par promotion
        $studentsPerPage = 5; // Nombre d'étudiants par page dans chaque promotion
        $paginatedPromotions = $promotions->getCollection()->map(function ($promotion) use ($studentsPerPage, $request) {
            $currentPage = $request->get('page_' . $promotion->id, 1);
            $students = $promotion->students;
            $paginatedStudents = new \Illuminate\Pagination\LengthAwarePaginator(
                $students->forPage($currentPage, $studentsPerPage),
                $students->count(),
                $studentsPerPage,
                $currentPage,
                ['path' => $request->url(), 'pageName' => 'page_' . $promotion->id]
            );
            $promotion->paginated_students = $paginatedStudents;
            return $promotion;
        });

        $promotions->setCollection($paginatedPromotions);

        return view('promotion_apprenant.index', compact('promotions', 'allPromotions', 'sexeFilter', 'promotionFilter'));
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
            ->with('success', 'Students have been successfully added to the promotion.');
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
            return back()->withErrors(['student_id' => 'This student is already in this promotion.']);
        }

        $promotionApprenant->update($request->all());

        return redirect()->route('promotion_apprenant.index')
            ->with('success', 'The association has been successfully updated.');
    }

    public function destroy(PromotionApprenant $promotionApprenant)
    {
        if ($promotionApprenant->site_id !== Auth::user()->site_id) {
            abort(403, 'Accès non autorisé.');
        }

        $promotionApprenant->delete();

        return redirect()->route('promotion_apprenant.index')
            ->with('success', 'The student has been removed from the promotion.');
    }

    public function export(Request $request)
    {
        $filters = [
            'sexe' => $request->get('sexe'),
            'promotion_id' => $request->get('promotion_id'),
        ];

        return Excel::download(new PromotionApprenantExport($filters), 'promotion_apprenants_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
