<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Student;
use App\Models\Promotion; // Assurez-vous d'avoir un modèle Promotion
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class EntityController extends Controller
{


    public function index()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer uniquement les entités dont l'étudiant appartient au même site que l'utilisateur connecté
        $entities = Entity::whereHas('student', function ($query) use ($user) {
            $query->where('site_id', $user->site_id);
        })->latest()->paginate(10);

        return view('entities.index', compact('entities'));
    }


    public function create()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer les étudiants du site de l'utilisateur connecté
        $students = collect();
        if ($user && $user->site_id) {
            $students = Student::where('site_id', $user->site_id)
                               ->whereDoesntHave('entities')
                               ->orderBy('created_at', 'desc')
                               ->get();
        }

        return view('entities.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'student_id' => 'required|exists:students,id',
        ]);

        Entity::create([
            'activity' => $request->activity,
            'student_id' => $request->student_id,
        ]);

        return redirect()->route('entities.index')->with('success', 'Entity created successfully.');
    }

    public function show($id)
    {
        $entity = Entity::findOrFail($id);
        return view('entities.show', compact('entity'));
    }

    public function edit($id)
    {
        $entity = Entity::findOrFail($id);
        $user = Auth::user();

        $students = collect();
        if ($user && $user->site_id) {
            $students = Student::where('site_id', $user->site_id)
                               ->whereDoesntHave('entities')
                               ->orWhere('id', $entity->student_id)
                               ->orderBy('created_at', 'desc')
                               ->get();
        }

        return view('entities.edit', compact('entity', 'students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'student_id' => 'required|exists:students,id',
        ]);

        $entity = Entity::findOrFail($id);
        $entity->update([
            'activity' => $request->activity,
            'student_id' => $request->student_id,
        ]);

        return redirect()->route('entities.index')->with('success', 'Entity updated successfully.');
    }

    public function destroy(Entity $entity)
    {
        $entity->delete();
        return redirect()->route('entities.index')->with('success', 'Entity deleted successfully.');
    }

    public function getStudentsBySite(Request $request)
    {
        $siteId = $request->input('site_id');
        $students = Student::where('site_id', $siteId)
                           ->whereDoesntHave('entities')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return response()->json($students);
    }


    public function filterView(Request $request)
    {
        $user = Auth::user();
        $promotions = Promotion::where('site_id', $user->site_id)->get();
        $selectedPromotion = $request->input('promotion_id');

        $entities = collect();

        if ($selectedPromotion) {
            $entities = Entity::whereHas('student', function ($query) use ($selectedPromotion) {
                $query->whereHas('promotions', function ($q) use ($selectedPromotion) {
                    $q->where('promotions.id', $selectedPromotion);
                });
            })->with('student')->get();
        }

        return view('entities.filter', compact('promotions', 'entities', 'selectedPromotion'));
    }

}
