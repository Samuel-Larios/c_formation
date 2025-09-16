@extends('base_admin')
@section('title', 'Follow-up List')

@section('content')
    <div class="container">
        <h1>Follow-up List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('follow_ups.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="farm_visits" class="form-label">Farm Visits</label>
                        <input type="text" name="farm_visits" value="{{ request('farm_visits') }}" class="form-control"
                            id="farm_visits" placeholder="Filter by farm visits">
                    </div>
                    <div class="col-md-3">
                        <label for="phone_contact" class="form-label">Phone Contact</label>
                        <input type="text" name="phone_contact" value="{{ request('phone_contact') }}"
                            class="form-control" id="phone_contact" placeholder="Filter by phone contact">
                    </div>
                    <div class="col-md-3">
                        <label for="sharing_of_impact_stories" class="form-label">Sharing of Impact Stories</label>
                        <input type="text" name="sharing_of_impact_stories"
                            value="{{ request('sharing_of_impact_stories') }}" class="form-control"
                            id="sharing_of_impact_stories" placeholder="Filter by impact stories">
                    </div>
                    <div class="col-md-3">
                        <label for="back_stopping" class="form-label">Back-stopping</label>
                        <input type="text" name="back_stopping" value="{{ request('back_stopping') }}"
                            class="form-control" id="back_stopping" placeholder="Filter by back-stopping">
                    </div>
                    <div class="col-md-3">
                        <label for="promotion" class="form-label">Promotion</label>
                        <select name="promotion" class="form-control" id="promotion">
                            <option value="">All Promotions</option>
                            @foreach ($promotions as $promotion)
                                <option value="{{ $promotion->id }}"
                                    {{ request('promotion') == $promotion->id ? 'selected' : '' }}>
                                    {{ $promotion->num_promotion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <script>
                        document.getElementById('promotion').addEventListener('change', function() {
                            this.form.submit();
                        });
                    </script>
                    <div class="col-12 d-flex align-items-center">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('follow_ups.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Reset Filters
                        </a>
                    </div>
                </form>

            </div>
        </div>




        <div class="mb-3 d-flex align-items-center gap-2">
            <a href="{{ route('follow_ups.create') }}" class="btn btn-primary">
                Add Follow-up
            </a>

            <form method="POST" action="{{ route('follow_ups.export') }}">
                @csrf
                <input type="hidden" name="promotion" id="export_promotion" value="{{ request('promotion') }}">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </form>
        </div>




        @if ($followUps->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Farm Visits</th>
                            <th>Phone Contact</th>
                            <th>Sharing of Impact Stories</th>
                            <th>Back-stopping</th>
                            <th>Student</th>
                            <th>Phone Number</th>
                            <th>Gender</th>
                            <th>Images Count</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($followUps as $followUp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $followUp->farm_visits }}</td>
                                <td>{{ $followUp->phone_contact }}</td>
                                <td>{{ $followUp->sharing_of_impact_stories }}</td>
                                <td>{{ $followUp->back_stopping }}</td>
                                <td>{{ $followUp->student->last_name ?? 'No student' }}
                                    {{ $followUp->student->first_name ?? '' }}</td>
                                <td>{{ $followUp->student->contact ?? 'N/A' }}</td>
                                <td>{{ $followUp->student->sexe == 'M' ? 'Male' : ($followUp->student->sexe == 'F' ? 'Female' : 'N/A') }}
                                </td>
                                <td>{{ $followUp->images->count() }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('follow_ups.show', $followUp->id) }}"
                                            class="btn btn-outline-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('follow_ups.edit', $followUp->id) }}"
                                            class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('follow_ups.destroy', $followUp->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this follow-up?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $followUps->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> No follow-up found.
            </div>
        @endif
    </div>
@endsection
