@extends('base_admin')

@section('title', 'List of Business Statuses')

@section('content')
<div class="container">
    <h1>List of Business Statuses</h1>
    <a href="{{ route('business_status.create') }}" class="btn btn-success mb-3">Create a new status</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type of Business</th>
                <th>Status</th>
                <th>Student</th>
                <th>Training center</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($businessStatuses as $businessStatus)
                <tr>
                    <td>{{ $businessStatus->id }}</td>
                    <td>{{ $businessStatus->type_of_business }}</td>
                    <td>{{ $businessStatus->status }}</td>
                    <td>{{ $businessStatus->student->last_name }} {{ $businessStatus->student->first_name }}</td>
                    <td>{{ $businessStatus->site->name }}</td>
                    <td>
                        <a href="{{ route('business_status.show', $businessStatus->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('business_status.edit', $businessStatus->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('business_status.destroy', $businessStatus->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this status?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $businessStatuses->links() }}
</div>
@endsection
