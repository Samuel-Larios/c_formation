@extends('base_admin')

@section('title', 'Salary List')

@section('content')
    <div class="container">
        <h1>Salary List</h1>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('salaries.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="entreprise" class="form-label">Company</label>
                        <input type="text" name="entreprise" value="{{ request('entreprise') }}" class="form-control"
                            id="entreprise" placeholder="Filter by company">
                    </div>
                    <div class="col-md-3">
                        <label for="localisation" class="form-label">Location</label>
                        <input type="text" name="localisation" value="{{ request('localisation') }}" class="form-control"
                            id="localisation" placeholder="Filter by location">
                    </div>
                    <div class="col-md-3">
                        <label for="employeur" class="form-label">Employer</label>
                        <input type="text" name="employeur" value="{{ request('employeur') }}" class="form-control"
                            id="employeur" placeholder="Filter by employer">
                    </div>
                    <div class="col-md-3">
                        <label for="tel" class="form-label">Phone</label>
                        <input type="text" name="tel" value="{{ request('tel') }}" class="form-control"
                            id="tel" placeholder="Filter by phone">
                    </div>
                    <div class="col-md-3">
                        <label for="promotion" class="form-label">Promotion</label>
                        <select name="promotion" class="form-control" id="promotion">
                            <option value="">All promotions</option>
                            @foreach ($promotions as $promotion)
                                <option value="{{ $promotion->id }}"
                                    {{ request('promotion') == $promotion->id ? 'selected' : '' }}>
                                    {{ $promotion->num_promotion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sexe" class="form-label">Gender</label>
                        <select name="sexe" class="form-control" id="sexe">
                            <option value="">All genders</option>
                            <option value="M" {{ request('sexe') == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ request('sexe') == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Button -->
        <div class="mb-3">
            <!-- Button to access the creation page -->
            <div class="mb-3">
                <a href="{{ route('salaries.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create a New Salary
                </a>

                <form method="POST" action="{{ route('salaries.export') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="entreprise" value="{{ request('entreprise') }}">
                    <input type="hidden" name="localisation" value="{{ request('localisation') }}">
                    <input type="hidden" name="employeur" value="{{ request('employeur') }}">
                    <input type="hidden" name="tel" value="{{ request('tel') }}">
                    <input type="hidden" name="promotion" value="{{ request('promotion') }}">
                    <input type="hidden" name="sexe" value="{{ request('sexe') }}">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                </form>
            </div>

        </div>

        <!-- Salaries Table -->
        <div class="card">
            <div class="card-body">
                @if ($salaries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Employer</th>
                                    <th>Phone</th>
                                    <th>Student</th>
                                    <th>Gender</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salaries as $salary)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $salary->entreprise }}</td>
                                        <td>{{ $salary->localisation }}</td>
                                        <td>{{ $salary->employeur }}</td>
                                        <td>{{ $salary->tel }}</td>
                                        <td>{{ $salary->student->last_name }} {{ $salary->student->first_name }}</td>
                                        <td>{{ $salary->student->sexe }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('salaries.show', $salary->id) }}"
                                                    class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('salaries.edit', $salary->id) }}"
                                                    class="btn btn-outline-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('salaries.destroy', $salary->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this salary?');"
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
                        {{ $salaries->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> No salaries found.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
