@extends('base_admin')
@section('title', 'List of Grants')

@section('content')
<div class="container">
    <h1>List of Grants</h1>
    <a href="{{ route('subventions.create') }}" class="btn btn-primary">Add a Grant</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Start-up Kits</th>
                <th>Grants</th>
                <th>Loan</th>
                <th>Date</th>
                <th>Student</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subventions as $subvention)
            <tr>
                <td>{{ $subvention->start_up_kits ?? 'N/A' }}</td>
                <td>{{ $subvention->grants ?? 'N/A' }}</td>
                <td>{{ $subvention->loan ?? 'N/A' }}</td>
                <td>{{ $subvention->date ?? 'N/A' }}</td>
                <td>
                    {{ $subvention->student->first_name ?? 'No student' }}
                    {{ $subvention->student->last_name ?? 'No student' }}
                </td>
                <td>
                    <a href="{{ route('subventions.edit', $subvention->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('subventions.destroy', $subvention->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this grant?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $subventions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
