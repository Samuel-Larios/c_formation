@extends('base_admin')

@section('content')
<div class="container">
    <h1>Gestion des mots de passe étudiants</h1>

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Rechercher par nom, email ou téléphone">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100" wire:click="$set('search', '')">
                        <i class="fas fa-times"></i> Effacer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="card">
        <div class="card-header">
            <h5>Liste des étudiants</h5>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ $student->email ?: 'N/A' }}</td>
                                    <td>{{ $student->contact ?: 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('students.change-password', $student->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-key"></i> Changer mot de passe
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $students->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info">
                    Aucun étudiant trouvé.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
