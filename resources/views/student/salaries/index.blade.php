@extends('base_student')
@section('title', 'My Employees')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Permanent contract</h2>
            <a href="{{ route('student.salaries.create') }}" class="btn btn-primary">
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
                            <th>Company</th>
                            <th>Location</th>
                            <th>Employer</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $salary)
                        <tr>
                            <td>{{ $salary->entreprise }}</td>
                            <td>{{ $salary->localisation }}</td>
                            <td>{{ $salary->employeur }}</td>
                            <td>{{ $salary->tel }}</td>
                            <td class="d-flex gap-2">
                                {{-- <h6 class="text-muted">Action not available</h6> --}}
                                <a href="{{ route('student.salaries.edit', $salary) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                {{-- <form action="{{ route('student.salaries.destroy', $salary) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this employee?')">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No employees registered</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
