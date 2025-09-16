@extends('base_admin')

@section('title', 'List of Subsidies')

@section('content')
    <div class="container">
        <h1>List of Subsidies</h1>

        <!-- Button to access the creation page -->
        <div class="mb-3">

        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filtering Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('subventions.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_up_kits" class="form-label">Start-up Kits</label>
                        <input type="text" name="start_up_kits" value="{{ request('start_up_kits') }}"
                            class="form-control" id="start_up_kits" placeholder="Filter by kits">
                    </div>
                    <div class="col-md-3">
                        <label for="grants" class="form-label">Grants</label>
                        <input type="text" name="grants" value="{{ request('grants') }}" class="form-control"
                            id="grants" placeholder="Filter by grants">
                    </div>
                    <div class="col-md-3">
                        <label for="loan" class="form-label">Loan</label>
                        <input type="text" name="loan" value="{{ request('loan') }}" class="form-control"
                            id="loan" placeholder="Filter by loan">
                    </div>
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="form-control"
                            id="date">
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
                            document.getElementById('export_promotion').value = this.value;
                        });
                    </script>
                    <div class="col-12 d-flex align-items-center">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('subventions.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Reset Filters
                        </a>
                    </div>
                </form>

            </div>
        </div>

        {{-- Export --}}

        <!-- Button to access the creation page -->
<div class="mb-3 d-flex align-items-center gap-2">
    <a href="{{ route('subventions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create a New Subsidy
    </a>

    <form method="POST" action="{{ route('subventions.export') }}">
        @csrf
        <input type="hidden" name="promotion" id="export_promotion" value="{{ request('promotion') }}">
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-file-excel"></i> Exporter en Excel
        </button>
    </form>
</div>


        <!-- Subsidies Table -->
        <div class="card">
            <div class="card-body">
                @if ($subventions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Start-up Kits</th>
                                    <th>Grants</th>
                                    <th>Loan</th>
                                    <th>Date</th>
                                    <th>Kits Items Received</th>
                                    <th>Farm State / Location</th>
                                    <th>Student</th>
                                    <th>Phone Number</th>
                                    <th>Gender</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subventions as $subvention)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subvention->start_up_kits ?? 'N/A' }}</td>
                                        <td>{{ $subvention->grants ?? 'N/A' }}</td>
                                        <td>{{ $subvention->loan ?? 'N/A' }}</td>
                                        <td>{{ $subvention->date ?? 'N/A' }}</td>
                                        <td>{{ $subvention->start_up_kits_items_received ?? 'N/A' }}</td>
                                        <td>{{ $subvention->state_of_farm_location ?? 'N/A' }}</td>
                                        <td>{{ $subvention->student->last_name ?? 'No student' }}
                                            {{ $subvention->student->first_name ?? '' }}</td>
                                        <td>{{ $subvention->student->contact ?? 'N/A' }}</td>
                                        <td>{{ $subvention->student->sexe == 'M' ? 'Male' : ($subvention->student->sexe == 'F' ? 'Female' : 'N/A') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('subventions.show', $subvention->id) }}"
                                                    class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('subventions.edit', $subvention->id) }}"
                                                    class="btn btn-outline-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('subventions.destroy', $subvention->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this subsidy?');"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Delete">
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
                        {{ $subventions->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> No subsidy found.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
