@extends('base_admin')
@section('title', 'Student Details')

@section('content')
<div class="container">
    <h1 class="mb-4">Student Details</h1>

    <!-- Print Button -->
    <div class="text-end mb-3">
        <button onclick="window.print()" class="btn btn-success">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <!-- Card to display student details -->
    <div class="card shadow">
        <div class="card-body">
            <!-- Photo Section -->
            <div class="text-center mb-4">
                @if($student->profile_photo)
                    <img src="{{ asset('storage/' . $student->profile_photo) }}"
                         alt="Photo de {{ $student->first_name }} {{ $student->last_name }}"
                         class="img-fluid rounded-circle"
                         style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #007bff;">
                @else
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 150px; height: 150px; border: 3px solid #6c757d;">
                        <i class="fas fa-user fa-3x text-secondary"></i>
                    </div>
                @endif
                <h5 class="card-title text-primary mt-3">{{ $student->first_name }} {{ $student->last_name }}</h5>
            </div>
            <hr>

            <div class="row">
                <!-- Left column -->
                <div class="col-md-6">
                    <p class="card-text"><strong>Gender:</strong> {{ $student->sexe }}</p>
                    <p class="card-text"><strong>Email:</strong> {{ $student->email }}</p>
                    <p class="card-text"><strong>Campus:</strong> {{ $student->site->designation ?? 'N/A' }}</p>
                    <p class="card-text"><strong>Date of Birth:</strong> {{ $student->date_naissance ?? 'N/A' }}</p>
                </div>

                <!-- Right column -->
                <div class="col-md-6">
                    <p class="card-text"><strong>Contact:</strong> {{ $student->contact ?? 'N/A' }}</p>
                    <p class="card-text"><strong>Marital Status:</strong> {{ $student->situation_matrimoniale ?? 'N/A' }}</p>
                    <p class="card-text"><strong>Disability Status:</strong> {{ $student->situation_handicape ?? 'N/A' }}</p>
                    <p class="card-text"><strong>State of Origin:</strong> {{ $student->state_of_origin }}</p>
                    <p class="card-text"><strong>State of Residence:</strong> {{ $student->state_of_residence }}</p>
                </div>

                <div class="col-md-12">
                    <h6 class="card-title text-success text-7xl">Site of {{ $student->site->designation }}</h6>
                    <hr>
                    <h6 class="card-title text-7xl">Farm location</h6>
                    <p class="card-text"><strong>State:</strong> {{ $student->state ?? 'N/A' }}</p>
                    <p class="card-text"><strong>LGA:</strong> {{ $student->lga ?? 'N/A' }}</p>
                    <p class="card-text"><strong>Community:</strong> {{ $student->community ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        /* Hide unnecessary elements */
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        /* Hide print and back buttons */
        .btn, .text-end {
            display: none;
        }
        /* Adjust print styles */
        .card-title {
            font-size: 24px;
            font-weight: bold;
        }
        .card-text {
            font-size: 18px;
        }
    }
</style>
@endsection
