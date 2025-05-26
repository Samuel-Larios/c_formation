@extends('base_admin')
@section('title', 'Students of the Promotion')

@section('content')
<div class="container">
    <h1>Students of the Promotion: {{ $promotion->num_promotion }}</h1>

    <!-- Back button -->
    <div class="mb-4">
        <a href="{{ route('promotions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to the list of promotions
        </a>
    </div>

    <!-- Students table -->
    <table class="table">
        <!-- Print button -->
        <div class="text-end mb-3">
            <button onclick="window.print()" class="btn btn-success">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Training center</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotion->students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                <td>{{ $student->sexe }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->site->designation ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<!-- Styles for printing -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .table, .table * {
            visibility: visible;
        }
        .table {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn {
            display: none;
        }
    }
</style>
