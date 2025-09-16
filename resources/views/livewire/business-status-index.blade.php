<div>
    {{-- <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Business Status Management</h3>
            <h6 class="op-7 mb-2">Manage and track student business statuses</h6>
        </div>
    </div> --}}

    <!-- Statistics Cards -->
    {{-- <div class="row mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Business Statuses</p>
                                <h4 class="card-title">{{ $totalBusinessStatuses }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-head-row">
                <div class="card-title">
                    <i class="fas fa-filter"></i> Filters
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="type_of_business" class="form-label">Business Type</label>
                    <input type="text" wire:model.live="type_of_business" class="form-control" id="type_of_business"
                        placeholder="Filter by business type">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" wire:model.live="status" class="form-control" id="status"
                        placeholder="Filter by status">
                </div>
                <div class="col-md-2">
                    <label for="sexe" class="form-label">Gender</label>
                    <select wire:model.live="sexe" class="form-control" id="sexe">
                        <option value="">All</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="promotion_id" class="form-label">Promotion</label>
                    <select wire:model.live="promotion_id" class="form-control" id="promotion_id">
                        <option value="">All</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary w-100" wire:click="resetFilters"
                        title="Reset Filters">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <a href="#" wire:click="exportExcel" class="btn btn-primary me-2">
                    <i class="fas fa-download"></i> Export to Excel
                </a>
                <a href="{{ route('business_status.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Create New Status
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($businessStatuses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Business Name</th>
                                <th>Status</th>
                                <th>Student</th>
                                <th>Gender</th>
                                <th>Training Center</th>
                                <th class="text-center" style="width: 130px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($businessStatuses as $businessStatus)
                                <tr>
                                    <td>{{ $businessStatus->id }}</td>
                                    <td>{{ $businessStatus->type_of_business }}</td>
                                    <td>{{ $businessStatus->status }}</td>
                                    <td>{{ $businessStatus->student->last_name }}
                                        {{ $businessStatus->student->first_name }}</td>
                                    <td>{{ $businessStatus->student->sexe == 'M' ? 'Male' : 'Female' }}</td>
                                    <td>{{ $businessStatus->site->designation }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <a href="{{ route('business_status.show', $businessStatus->id) }}"
                                                class="btn btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('business_status.edit', $businessStatus->id) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('business_status.destroy', $businessStatus->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this status?');"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
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

                <div class="d-flex justify-content-center">
                    {{ $businessStatuses->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    No status found.
                </div>
            @endif
        </div>
    </div>
</div>
