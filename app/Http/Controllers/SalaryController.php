<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    // Display the list of salaries
    public function index(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get all promotions for the filter
        $promotions = \App\Models\Promotion::where('site_id', $user->site_id)->get();

        // Get salaries with students from the user's site and filters
        $salaries = Salary::whereHas('student', function ($query) use ($user) {
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
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('salaries.index', compact('salaries', 'promotions'));
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
