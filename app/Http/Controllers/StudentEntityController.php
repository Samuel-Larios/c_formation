<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentEntityController extends Controller
{
    /**
     * Affiche la liste des entités de l'étudiant connecté
     */
    public function index(): View
    {
        $student = Auth::guard('student')->user();
        $entities = Entity::where('student_id', $student->id)
                         ->latest()
                         ->paginate(10);

        return view('student.entities.index', compact('entities'));
    }

    /**
     * Affiche le formulaire de création d'entité
     */
    public function create(): View
    {
        // L'étudiant connecté est automatiquement associé
        return view('student.entities.create');
    }

    /**
     * Enregistre une nouvelle entité pour l'étudiant connecté
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'activity' => 'required|string|max:255',
        ]);

        $student = Auth::guard('student')->user();

        Entity::create([
            'activity' => $validated['activity'],
            'student_id' => $student->id,
        ]);

        return redirect()
            ->route('student.entities.index')
            ->with('success', 'Entity successfully created.');
    }

    /**
     * Affiche les détails d'une entité
     */
    public function show(Entity $entity): View
    {
        $this->verifyEntityOwnership($entity);
        return view('student.entities.show', compact('entity'));
    }

    /**
     * Affiche le formulaire d'édition d'entité
     */
    public function edit(Entity $entity): View
    {
        $this->verifyEntityOwnership($entity);
        return view('student.entities.edit', compact('entity'));
    }

    /**
     * Met à jour une entité existante
     */
    public function update(Request $request, Entity $entity): RedirectResponse
    {
        $this->verifyEntityOwnership($entity);

        $validated = $request->validate([
            'activity' => 'required|string|max:255',
        ]);

        $entity->update($validated);

        return redirect()
            ->route('student.entities.index')
            ->with('success', 'Entity updated successfully.');
    }

    /**
     * Supprime une entité
     */
    public function destroy(Entity $entity): RedirectResponse
    {
        $this->verifyEntityOwnership($entity);
        $entity->delete();

        return redirect()
            ->route('student.entities.index')
            ->with('success', 'Entity successfully deleted.');
    }

    /**
     * Vérifie que l'entité appartient à l'étudiant connecté
     */
    protected function verifyEntityOwnership(Entity $entity): void
    {
        if ($entity->student_id !== Auth::guard('student')->id()) {
            abort(403, 'This entity does not belong to you');
        }
    }
}
