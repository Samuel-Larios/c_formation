@extends('base_admin')

@section('title', 'Specialization List')

@section('content')
<div class="container">
    <h1>Specialization List</h1>
    <a href="{{ route('specializations.create') }}" class="btn btn-primary">Add Specialization</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student</th>
                <th>Specialization</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($specializations as $specialization)
                <tr>
                    <td>{{ $specialization->student->first_name }} {{ $specialization->student->last_name }}</td>
                    <td>{{ $specialization->specialite->designation }}</td>
                    <td>
                        <a href="{{ route('specializations.edit', $specialization->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('specializations.destroy', $specialization->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
