@extends('base')

@section('title', 'User List')

@section('content')
<div class="container">
    <h1>User List</h1>

    <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">Add a User</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Training center</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($utilisateurs as $utilisateur)
            <tr>
                <td>{{ $utilisateur->name }}</td>
                <td>{{ $utilisateur->date_naissance }}</td>
                <td>{{ $utilisateur->tel }}</td>
                <td>{{ $utilisateur->role }}</td>
                <td>{{ $utilisateur->site->designation }}</td>
                <td>
                    <a href="{{ route('utilisateurs.edit', $utilisateur->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('utilisateurs.destroy', $utilisateur->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $utilisateurs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
