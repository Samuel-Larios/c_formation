@extends('base_admin')

@section('title', 'Business Status Details')

@section('content')
<div class="container">
    <h1>Business Status Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Type of Business: {{ $businessStatus->type_of_business }}</h5>
            <p class="card-text">Status: {{ $businessStatus->status }}</p>
            <p class="card-text">Student: {{ $businessStatus->student->last_name }} {{ $businessStatus->student->first_name }}</p>
            <p class="card-text">Site: {{ $businessStatus->site->designation }}</p>
        </div>
    </div>

    <a href="{{ route('business_status.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
