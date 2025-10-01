@extends('base_admin')

@section('title', 'Specialization List')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">
            <i class="fas fa-graduation-cap me-2"></i>Specialization List
        </h1>
        <a href="{{ route('specializations.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Add Specialization
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <div class="card card-round mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-search me-2"></i>Search Specializations
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('specializations.index') }}">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Enter student name..." value="{{ $search }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    @if($search)
                        <a href="{{ route('specializations.index') }}" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    @endif
                </div>
                @if($search)
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Searching for: <strong>{{ $search }}</strong>
                        </small>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th><i class="fas fa-user me-1"></i>Student</th>
                    <th><i class="fas fa-graduation-cap me-1"></i>Specialization</th>
                    <th><i class="fas fa-cogs me-1"></i>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($specializations as $specialization)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-info me-3">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $specialization->student->first_name }} {{ $specialization->student->last_name }}</h6>
                                    <small class="text-muted">Student</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $specialization->specialite->designation }}</td>
                        <td>
                            <a href="{{ route('specializations.edit', $specialization->id) }}" class="btn btn-sm btn-primary me-2">Edit</a>
                            <form action="{{ route('specializations.destroy', $specialization->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this specialization?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Specializations Found</h5>
                            <p class="text-muted">No specializations match your search criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $specializations->links() }}
    </div>
</div>
@endsection
