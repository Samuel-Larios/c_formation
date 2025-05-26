@extends('base')

@section('title', 'Liste des Utilisateurs')

@section('content')
<div class="container">
    <h1>Liste des Utilisateurs</h1>

    <!-- Bouton d'exportation -->
    <div class="mb-3">
        <a href="{{ route('export.users') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Exporter en Excel
        </a>
    </div>

    <!-- Formulaire d'importation -->
    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="file">Importer un fichier Excel</label>
            <input type="file" name="file" id="file" class="form-control" required>
            <small class="form-text text-muted">
                Formats acceptés : .xlsx, .xls
            </small>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-upload"></i> Importer
        </button>
    </form>

    <!-- Tableau des utilisateurs -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Date de Naissance</th>
                <th>Poste</th>
                <th>Training center ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($utilisateurs as $utilisateur)
                <tr>
                    <td>{{ $utilisateur->name }}</td>
                    <td>{{ $utilisateur->email }}</td>
                    <td>{{ $utilisateur->tel }}</td>
                    <td>{{ $utilisateur->role }}</td>
                    <td>{{ $utilisateur->date_naissance }}</td>
                    <td>{{ $utilisateur->poste }}</td>
                    <td>{{ $utilisateur->site_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $utilisateurs->links() }}
</div>
@endsection
