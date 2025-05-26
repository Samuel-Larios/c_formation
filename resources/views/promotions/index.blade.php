@extends('base_admin')

@section('title', 'List of Promotions')

@section('content')
<div class="container">
    <h1>List of Promotions</h1>
    <a href="{{ route('promotions.create') }}" class="btn btn-primary">Add a Promotion</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Promotion Number</th>
                <th>Creation Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $promotion->num_promotion }}</td>
                <td>{{ $promotion->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('promotions.students', $promotion->id) }}" class="btn btn-info">View</a> <!-- View button -->
                    <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this promotion?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $promotions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
