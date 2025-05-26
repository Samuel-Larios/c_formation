@extends('base_student')
@section('title', 'My Job Creations')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Job Creations</h2>
            <a href="{{ route('student.job_creations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobCreations as $jobCreation)
                        <tr>
                            <td>{{ $jobCreation->nom }}</td>
                            <td>{{ $jobCreation->tel }}</td>
                            <td class="d-flex gap-2">
                                {{-- <h6 class="text-muted">Action not available</h6> --}}
                                <a href="{{ route('student.job_creations.edit', $jobCreation) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                {{-- <form action="{{ route('student.job_creations.destroy', $jobCreation) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No job creations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
