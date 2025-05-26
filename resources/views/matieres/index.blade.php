@extends('base_admin')

@section('title', 'List of Subjects')

@section('content')
<div class="container">
    <h1>List of Subjects</h1>
    <a href="{{ route('matieres.create') }}" class="btn btn-primary">Add a Subject</a>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Designation</th>
                <th>Coefficient</th>
                <th>Training center</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matieres as $matiere)
                <tr>
                    <td>{{ $matiere->id }}</td>
                    <td>{{ $matiere->designation }}</td>
                    <td>{{ $matiere->coef }}</td>
                    <td>{{ $matiere->site->designation }}</td>
                    <td>
                        <a href="{{ route('matieres.edit', $matiere->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('matieres.destroy', $matiere->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $matieres->links() }}
</div>
@endsection
