@extends('base_admin')

@section('title', 'Salary List')

@section('content')
<div class="container">
    <h1>Salary List</h1>

    <!-- Button to access the creation page -->
    <div class="mb-3">
        <a href="{{ route('salaries.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create a New Salary
        </a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Company</th>
                <th>Location</th>
                <th>Employer</th>
                <th>Phone</th>
                <th>Student</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salaries as $salary)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $salary->entreprise }}</td>
                    <td>{{ $salary->localisation }}</td>
                    <td>{{ $salary->employeur }}</td>
                    <td>{{ $salary->tel }}</td>
                    <td>{{ $salary->student->last_name }} {{ $salary->student->first_name }}</td>
                    <td>
                        <a href="{{ route('salaries.edit', $salary->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $salaries->links() }}
</div>
@endsection
