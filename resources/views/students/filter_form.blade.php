@extends('base_admin')
@section('title', 'Filter Students')
@section('content')
<div class="container">
    <h1>Filter Students</h1>

    <form method="GET" action="{{ route('students.filter.results') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="promotion_id" class="form-label">Promotion:</label>
                <select name="promotion_id" id="promotion_id" class="form-select">
                    <option value="">All Promotions</option>
                    @foreach($promotions as $promotion)
                        <option value="{{ $promotion->id }}">
                            {{ $promotion->num_promotion }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="specialization_id" class="form-label">Specialization:</label>
                <select name="specialization_id" id="specialization_id" class="form-select">
                    <option value="">All Specializations</option>
                    @foreach($specializations as $specialization)
                        <option value="{{ $specialization->id }}">
                            {{ $specialization->designation }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('students.filter.form') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>
@endsection

