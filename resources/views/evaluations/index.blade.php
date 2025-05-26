@extends('base_admin')

@section('title', 'Evaluation List')

@section('content')
<div class="container">
    <h1>Evaluation List</h1>

    <!-- Link to add an evaluation -->
    <a href="{{ route('evaluations.create') }}" class="btn btn-primary">Add an Evaluation</a>

    <!-- Filter form by promotion -->
    <form action="{{ route('evaluations.index') }}" method="GET" class="mt-3">
        <div class="form-group">
            <label for="promotion_id">Filter by Promotion</label>
            <select name="promotion_id" id="promotion_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Promotions</option>
                @foreach ($promotions as $promotion)
                    <option value="{{ $promotion->id }}" {{ $selectedPromotionId == $promotion->id ? 'selected' : '' }}>
                        {{ $promotion->num_promotion }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Evaluations Table -->
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($evaluations as $evaluation)
                <tr>
                    <td>{{ $evaluation->student->first_name }} {{ $evaluation->student->last_name }}</td>
                    <td>{{ $evaluation->matier->designation }}</td>
                    <td>{{ $evaluation->note }}</td>
                    <td>
                        <a href="{{ route('evaluations.edit', $evaluation->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('evaluations.destroy', $evaluation->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $evaluations->links() }}
</div>
@endsection
