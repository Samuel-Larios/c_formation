@extends('base_student')
@section('title', 'Mes Entités')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">My business</h2>
            <a href="{{ route('student.entities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add an Entity
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entities as $entity)
                        <tr>
                            <td>{{ $entity->activity }}</td>
                            <td>
                                {{-- <h6 class="text-muted">Action not available</h6> --}}
                                <a href="{{ route('student.entities.edit', $entity->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                {{-- <form action="{{ route('student.entities.destroy', $entity->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">No entities registered</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
