@extends('base_admin')

@section('title', 'Edit Promotion-Student Record')

@section('content')
<div class="container">
    <h2>Edit Promotion-Student Record</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('promotion_apprenant.update', $promotionApprenant->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Promotion:</label>
            <select name="promotion_id" class="form-control" required>
                <option value="">Choose a promotion</option>
                @foreach ($promotions as $promotion)
                    <option value="{{ $promotion->id }}" {{ $promotionApprenant->promotion_id == $promotion->id ? 'selected' : '' }}>
                        {{ $promotion->num_promotion }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Student:</label>
            <select name="student_id" class="form-control" required>
                <option value="">Choose a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $promotionApprenant->student_id == $student->id ? 'selected' : '' }}>
                        {{ $student->first_name }} {{ $student->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Edit</button>
        <a href="{{ route('promotion_apprenant.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
@endsection
