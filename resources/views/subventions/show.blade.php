@extends('base_admin')

@section('title', 'Subvention Details')

@section('content')
<div class="container">
    <h1>Subvention Details</h1>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Start-up Kits</th>
                        <td>{{ $subvention->start_up_kits ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Grants</th>
                        <td>{{ $subvention->grants ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Loan</th>
                        <td>{{ $subvention->loan ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $subvention->date ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Start-up Kits Items Received</th>
                        <td>{{ $subvention->start_up_kits_items_received ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>State of Farm / Location</th>
                        <td>{{ $subvention->state_of_farm_location ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Student</th>
                        <td>{{ $subvention->student->last_name ?? 'No student' }} {{ $subvention->student->first_name ?? '' }}</td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('subventions.index') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection
