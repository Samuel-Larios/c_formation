@extends('base_admin')

@section('title', 'Job Creation List')

@section('content')
<div class="container">
    <h1>Job Creation List</h1>
    <a href="{{ route('job-creations.create') }}" class="btn btn-primary mb-3">Add Job Creation</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Student Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobCreations as $jobCreation)
            <tr>
                <td>{{ $jobCreation->nom }}</td>
                <td>{{ $jobCreation->tel }}</td>
                <td>{{ $jobCreation->student->first_name ?? 'N/A' }} {{ $jobCreation->student->last_name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('job-creations.edit', $jobCreation->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('job-creations.destroy', $jobCreation->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this job creation?')">Delete</button>
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
