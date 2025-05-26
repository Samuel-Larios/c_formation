@extends('base_admin')

@section('title', 'Edit a Salary')

@section('content')
<div class="container">
    <h1>Edit a Salary</h1>

    <form action="{{ route('salaries.update', $salary->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="entreprise">Company:</label>
            <input type="text" name="entreprise" class="form-control" value="{{ $salary->entreprise }}" required>
        </div>

        <div class="form-group">
            <label for="localisation">Location:</label>
            <input type="text" name="localisation" class="form-control" value="{{ $salary->localisation }}" required>
        </div>

        <div class="form-group">
            <label for="employeur">Employer:</label>
            <input type="text" name="employeur" class="form-control" value="{{ $salary->employeur }}" required>
        </div>

        <div class="form-group">
            <label for="tel">Phone:</label>
            <input type="text" name="tel" class="form-control" value="{{ $salary->tel }}" required>
        </div>

        {{-- <div class="form-group">
            <label for="promotion_id">Promotion:</label>
            <select name="promotion_id" id="promotion_id" class="form-control" required>
                @foreach ($promotions as $promotion)
                    <option value="{{ $promotion->id }}" {{ $salary->student->promotion_id == $promotion->id ? 'selected' : '' }}>
                        {{ $promotion->num_promotion }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        <div class="form-group">
            <label for="student_id">Student:</label>
            <select name="student_id" id="student_id" class="form-control" required>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $salary->student_id == $student->id ? 'selected' : '' }}>
                        {{ $student->last_name }} {{ $student->first_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Update</button>
        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
