@extends('base_admin')

@section('title', 'Entities List')

@section('content')
<div class="container">
    <h2 class="mt-3 mb-4">Entities List</h2>

    <!-- Bouton pour ajouter une nouvelle entité -->
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('entities.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Entity
        </a>
    </div>

    <!-- Tableau des entités -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Activity</th>
                <th>Student</th>
                <th>Training center</th> 
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entities as $entity)
                <tr>
                    <td>{{ $entity->activity }}</td>
                    <td>{{ $entity->student->first_name }} {{ $entity->student->last_name }}</td>
                    <td>{{ $entity->student->site->designation }}</td>
                    <td>
                        <a href="{{ route('entities.show', $entity->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('entities.edit', $entity->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('entities.destroy', $entity->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $entities->links() }}
</div>
@endsection
