@extends('base_student')

@section('content')
    <!-- Welcome Section -->
    <div class="container mt-5">
        <div class="welcome-section text-center">
            <h1>Welcome, {{ $student->first_name }} {{ $student->last_name }}!</h1>
            <p>You are logged in as a student.</p>
        </div>
    </div>

     <!-- Information Cards -->
     <div class="container my-3">
        <div class="row g-4">
            <!-- Card 1: Grades -->
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-icon mb-3">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <h3>My Grades</h3>
                    <p>Check your grades for each subject.</p>
                    <a href="#" class="btn btn-primary">View Grades</a>
                </div>
            </div>

            <!-- Card 2: Timetable -->
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-icon mb-3">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h3>Timetable</h3>
                    <p>Access your weekly timetable.</p>
                    <a href="#" class="btn btn-primary">View Timetable</a>
                </div>
            </div>

            <!-- Card 3: Documents -->
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-icon mb-3">
                        <i class="bi bi-folder"></i>
                    </div>
                    <h3>Documents</h3>
                    <p>Download your academic documents.</p>
                    <a href="#" class="btn btn-primary">Access Documents</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="container">
        <div class="card mb-4">
            <div class="card-body">
                <h2>Personal Information</h2>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>First Name:</strong> {{ $student->first_name }}</p>
                        <p><strong>Last Name:</strong> {{ $student->last_name }}</p>
                        <p><strong>Gender:</strong> {{ $student->sexe }}</p>
                        <p><strong>Marital Status:</strong> {{ $student->situation_matrimoniale ?? 'Not provided' }}</p>
                        <p><strong>Disability Status:</strong> {{ $student->situation_handicape ?? 'Not provided' }}</p>
                        <p><strong>Date of Birth:</strong> {{ $student->date_naissance ? $student->date_naissance->format('d/m/Y') : 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Contact:</strong> {{ $student->contact ?? 'Not provided' }}</p>
                        <p><strong>Contact Person 1:</strong> {{ $student->contact_pers1 ?? 'Not provided' }}</p>
                        <p><strong>Contact Person 2:</strong> {{ $student->contact_pers2 ?? 'Not provided' }}</p>
                        <p><strong>Contact Person 3:</strong> {{ $student->contact_pers3 ?? 'Not provided' }}</p>
                        <p><strong>Contact Person 4:</strong> {{ $student->contact_pers4 ?? 'Not provided' }}</p>
                        <p><strong>Contact Person 5:</strong> {{ $student->contact_pers5 ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="card mb-4">
            <div class="card-body">
                <h2>Location Information</h2>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>State of Origin:</strong> {{ $student->state_of_origin }}</p>
                        <p><strong>State of Residence:</strong> {{ $student->state_of_residence }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>State (Farm Location):</strong> {{ $student->state ?? 'Not provided' }}</p>
                        <p><strong>LGA (Farm Location):</strong> {{ $student->lga ?? 'Not provided' }}</p>
                        <p><strong>Community (Farm Location):</strong> {{ $student->community ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Site Information -->
        <div class="card mb-4">
            <div class="card-body">
                <h2>Training center Information</h2>
                <p><strong>Site:</strong> {{ $student->site->designation }}</p>
                <p><strong>Location:</strong> {{ $student->site->emplacement }}</p>
            </div>
        </div>
    </div>
@endsection
