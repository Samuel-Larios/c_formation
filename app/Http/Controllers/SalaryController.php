<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    // Display the list of salaries
    public function index(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get all promotions for the filter (unique num_promotion, like job-creations)
        $promotions = Promotion::select('num_promotion')->distinct()->orderBy('num_promotion', 'desc')->get();

        // Get the selected promotion
        $promotionId = $request->input('promotion');

        // Build the query for salaries
        $query = Salary::whereHas('student', function ($query) use ($user) {
            $query->where('site_id', $user->site_id);
        })
            ->when($request->filled('entreprise'), function ($q) use ($request) {
                $q->where('entreprise', 'like', '%' . $request->entreprise . '%');
            })
            ->when($request->filled('localisation'), function ($q) use ($request) {
                $q->where('localisation', 'like', '%' . $request->localisation . '%');
            })
            ->when($request->filled('employeur'), function ($q) use ($request) {
                $q->where('employeur', 'like', '%' . $request->employeur . '%');
            })
            ->when($request->filled('tel'), function ($q) use ($request) {
                $q->where('tel', 'like', '%' . $request->tel . '%');
            })
            ->when($request->filled('promotion'), function ($q) use ($request) {
                $promotionId = $request->promotion;
                $q->whereHas('student.promotions', function ($q2) use ($promotionId) {
                    $q2->where('num_promotion', $promotionId);
                });
            })
            ->when($request->filled('sexe'), function ($q) use ($request) {
                $q->whereHas('student', function ($subQ) use ($request) {
                    $subQ->where('sexe', $request->sexe);
                });
            });

        $salaries = $query->with('student')->orderBy('created_at', 'desc')->paginate(10);

        // Calculations for promotion statistics
        $studentGenderCounts = [];
        $totalStudents = 0;
        $totalStudentsInPromotion = 0;
        $studentsWithSalaries = 0;
        $studentsWithoutSalaries = 0;
        $totalSalaries = 0;
        $expectedStudentsWithSalaries = 0;
        $actualPercentage = 0;
        $difference = 0;
        $isReached = false;
        $histogramData = [];
        if ($promotionId) {
            // Gender distribution of students with salaries in the promotion (global across sites)
            $studentGenderCounts = DB::table('salaries')
                ->join('students', 'salaries.student_id', '=', 'students.id')
                ->join('promotion_apprenant', 'students.id', '=', 'promotion_apprenant.student_id')
                ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
                ->where('promotions.num_promotion', $promotionId)
                ->selectRaw('students.sexe, COUNT(DISTINCT salaries.student_id) as count')
                ->groupBy('students.sexe')
                ->pluck('count', 'sexe')
                ->toArray();

            // Total students with salaries (global)
            $totalStudents = array_sum($studentGenderCounts);

            // Total students in promotion (sum across all sites)
            $totalStudentsInPromotion = DB::table('promotion_apprenant')
                ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
                ->where('promotions.num_promotion', $promotionId)
                ->distinct('promotion_apprenant.student_id')
                ->count('promotion_apprenant.student_id');

            // Students with salaries (global)
            $studentsWithSalaries = $totalStudents;

            // Students without salaries
            $studentsWithoutSalaries = $totalStudentsInPromotion - $studentsWithSalaries;

            // Total salaries (global for the promotion)
            $totalSalaries = DB::table('salaries')
                ->join('students', 'salaries.student_id', '=', 'students.id')
                ->join('promotion_apprenant', 'students.id', '=', 'promotion_apprenant.student_id')
                ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
                ->where('promotions.num_promotion', $promotionId)
                ->count();

            // Expected students with salaries (30%)
            $expectedStudentsWithSalaries = intval($totalStudentsInPromotion * 0.3);

            // Actual percentage
            $actualPercentage = $totalStudentsInPromotion > 0 ? round(($studentsWithSalaries / $totalStudentsInPromotion) * 100, 2) : 0;

            // Difference
            $difference = $actualPercentage - 30;

            // Is reached
            $isReached = $actualPercentage >= 30;

            // Histogram data
            $histogramData = [
                'students_with_salaries' => $studentsWithSalaries,
                'students_without_salaries' => $studentsWithoutSalaries,
                'total_students' => $totalStudentsInPromotion,
                'total_salaries' => $totalSalaries,
                'expected_students_with_salaries' => $expectedStudentsWithSalaries,
                'actual_percentage' => $actualPercentage,
                'difference' => $difference,
                'is_reached' => $isReached,
            ];
        }

        return view('salaries.index', compact('salaries', 'promotions', 'studentGenderCounts', 'promotionId', 'totalStudents', 'totalStudentsInPromotion', 'studentsWithSalaries', 'studentsWithoutSalaries', 'totalSalaries', 'expectedStudentsWithSalaries', 'actualPercentage', 'difference', 'isReached', 'histogramData'));
    }

    // Get students by promotion (AJAX)
    public function getStudentsByPromotion(Request $request)
    {
        $promotionId = $request->get('promotion_id');
        $user = Auth::user();

        // Check if promotion exists
        $promotion = Promotion::find($promotionId);
        if (!$promotion) {
            return response()->json(['error' => 'Promotion not found'], 404);
        }

        // Get students from the promotion and user's site
        $students = $promotion->students()
            ->where('site_id', $user->site_id)
            ->get(['id', 'last_name', 'first_name']);

        return response()->json($students);
    }

    // Display the form to create a salary
    public function create()
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get all promotions
        $promotions = Promotion::all();

        // Get students from the user's site
        $students = Student::where('site_id', $user->site_id)->get();

        return view('salaries.create', compact('promotions', 'students'));
    }

    // Save a new salary
    public function store(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Validate data
        $request->validate([
            'entreprise' => 'required|string',
            'localisation' => 'required|string',
            'employeur' => 'required|string',
            'tel' => 'required|string',
            'student_id' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($user) {
                    $student = Student::find($value);
                    if (!$student || $student->site_id !== $user->site_id) {
                        $fail('The selected student does not belong to your site.');
                    }
                },
            ],
        ]);

        // Create a new salary
        Salary::create($request->all());

        return redirect()->route('salaries.index')->with('success', 'Salary created successfully.');
    }

    // Display the form to edit a salary
    public function edit(Salary $salary)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get all promotions
        $promotions = Promotion::all();

        // Get students from the user's site
        $students = Student::where('site_id', $user->site_id)->get();

        return view('salaries.edit', compact('salary', 'promotions', 'students'));
    }

    // Update a salary
    public function update(Request $request, Salary $salary)
    {
        // Validate data
        $request->validate([
            'entreprise' => 'required|string',
            'localisation' => 'required|string',
            'employeur' => 'required|string',
            'tel' => 'required|string',
        ]);

        // Update the salary
        $salary->update($request->only([
            'entreprise',
            'localisation',
            'employeur',
            'tel',
        ]));

        return redirect()->route('salaries.index')->with('success', 'Salary updated successfully.');
    }

    // Display the details of a salary
    public function show($id)
    {
        // Get the salary with associated student
        $salary = Salary::with('student')->findOrFail($id);

        return view('salaries.show', compact('salary'));
    }

    // Delete a salary
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'Salary successfully deleted.');
    }

    // Export salaries to Excel
    public function export(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'entreprise' => 'nullable|string',
            'localisation' => 'nullable|string',
            'employeur' => 'nullable|string',
            'tel' => 'nullable|string',
            'promotion' => 'nullable|exists:promotions,id',
            'sexe' => 'nullable|in:M,F',
        ]);

        $query = Salary::whereHas('student', function ($query) use ($user) {
            $query->where('site_id', $user->site_id);
        })
            ->when($request->filled('entreprise'), function ($q) use ($request) {
                $q->where('entreprise', 'like', '%' . $request->entreprise . '%');
            })
            ->when($request->filled('localisation'), function ($q) use ($request) {
                $q->where('localisation', 'like', '%' . $request->localisation . '%');
            })
            ->when($request->filled('employeur'), function ($q) use ($request) {
                $q->where('employeur', 'like', '%' . $request->employeur . '%');
            })
            ->when($request->filled('tel'), function ($q) use ($request) {
                $q->where('tel', 'like', '%' . $request->tel . '%');
            })
            ->when($request->filled('promotion'), function ($q) use ($request) {
                $promotionId = $request->promotion;
                $q->whereHas('student.promotions', function ($q2) use ($promotionId) {
                    $q2->where('promotion_id', $promotionId);
                });
            })
            ->when($request->filled('sexe'), function ($q) use ($request) {
                $q->whereHas('student', function ($subQ) use ($request) {
                    $subQ->where('sexe', $request->sexe);
                });
            })
            ->with('student')
            ->orderBy('created_at', 'desc');

        $salaries = $query->get();

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SalariesExport($salaries), 'salaries.xlsx');
    }
}
