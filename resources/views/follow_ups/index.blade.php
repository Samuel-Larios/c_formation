@extends('base_admin')
@section('title', 'Follow-up List')

@section('content')
<div class="container">
    <h1>Follow-up List</h1>
    <a href="{{ route('follow_ups.create') }}" class="btn btn-primary">Add Follow-up</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Farm Visits</th>
                <th>Phone Contact</th>
                <th>Sharing of Impact Stories</th>
                <th>Back-stopping</th>
                <th>Student</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($followUps as $followUp)
            <tr>
                <td>{{ $followUp->farm_visits }}</td>
                <td>{{ $followUp->phone_contact }}</td>
                <td>{{ $followUp->sharing_of_impact_stories }}</td>
                <td>{{ $followUp->back_stopping }}</td>
                <td>{{ $followUp->student->first_name }} {{ $followUp->student->last_name }}</td>
                <td>
                    <a href="{{ route('follow_ups.edit', $followUp->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('follow_ups.destroy', $followUp->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this follow-up?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $followUps->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
