@extends('base_admin')

@section('title', 'Job Creation List')

@section('content')
<div class="container">
    <h1>Job Creation List</h1>
    <a href="{{ route('job-creations.create') }}" class="btn btn-primary mb-3">Add Job Creation</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Filter by Promotion</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('jobcreations.index') }}">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                            <select name="promotion_id" id="promotion_id" class="form-select" onchange="this.form.submit()">
                                <option value="">All promotions</option>
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->id }}" {{ (isset($promotionId) && $promotionId == $promotion->id) ? 'selected' : '' }}>
                                        {{ $promotion->num_promotion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if(isset($genderCounts) && count($genderCounts) > 0)
        <div class="col-md-6">
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie"></i> Gender Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-male fa-2x text-primary mb-2"></i>
                                <h4 class="text-primary">{{ $genderCounts['Homme'] ?? 0 }}</h4>
                                <small class="text-muted">Men</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-female fa-2x text-danger mb-2"></i>
                                <h4 class="text-danger">{{ $genderCounts['Femme'] ?? 0 }}</h4>
                                <small class="text-muted">Women</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <table class="table table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Student Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobCreations as $jobCreation)
            <tr>
                <td>{{ $jobCreation->nom }}</td>
                <td>{{ $jobCreation->tel }}</td>
                <td>{{ $jobCreation->sexe }}</td>
                <td>{{ $jobCreation->student->first_name ?? 'N/A' }} {{ $jobCreation->student->last_name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('job-creations.edit', $jobCreation->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('job-creations.destroy', $jobCreation->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this job creation?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $jobCreations->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
