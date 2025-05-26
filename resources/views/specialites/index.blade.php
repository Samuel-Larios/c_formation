@extends('base_admin')
@section('title', 'Specializations')

@section('content')
<div class="container">
    <h2>List of Specializations</h2>
    <a href="{{ route('specialites.create') }}" class="btn btn-primary mb-3">Add a Specialization</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Designation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($specialites as $specialite)
                <tr>
                    <td>{{ $specialite->id }}</td>
                    <td>{{ $specialite->designation }}</td>
                    <td>
                        <a href="{{ route('specialites.edit', $specialite->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('specialites.destroy', $specialite->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this specialization?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
