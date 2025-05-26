@extends('base_admin')

@section('title', 'Liste des Entités')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Liste des Entités</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('entities.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Ajouter une Entité
        </a>
        <form action="{{ route('entities.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Suivi</th>
                    <th>Étudiant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entities as $entity)
                    <tr>
                        <td>{{ $entity->id }}</td>
                        <td>{{ $entity->nom }}</td>
                        <td>{{ $entity->statut }}</td>
                        <td>{{ $entity->tel }}</td>
                        <td>{{ $entity->email }}</td>
                        <td>
                            @if ($entity->suivit)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-danger">Non</span>
                            @endif
                        </td>
                        <td>{{ $entity->student->first_name ?? 'N/A' }} {{ $entity->student->last_name ?? '' }}</td>
                        <td>
                            <a href="{{ route('entities.show', $entity->id) }}" class="btn btn-info btn-sm" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('entities.edit', $entity->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('entities.destroy', $entity->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Voulez-vous vraiment supprimer cette entité ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $entities->links() }} <!-- Pagination -->
</div>
@endsection
