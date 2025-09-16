@extends('base_admin')

@section('title', 'List of promotions with students')

@section('content')
    <div class="container">
        <h1 class="mb-4">List of promotions with students</h1>

        <form method="GET" action="{{ route('promotion_apprenant.index') }}"
            class="mb-3 d-flex gap-3 flex-wrap align-items-center">
            <div>
                <label for="promotion_id" class="form-label">Promotion</label>
                <select name="promotion_id" id="promotion_id" class="form-select">
                    <option value="">All</option>
                    @foreach ($allPromotions as $promo)
                        <option value="{{ $promo->id }}"
                            {{ (string) $promo->id === (string) $promotionFilter ? 'selected' : '' }}>
                            {{ $promo->num_promotion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sexe" class="form-label">Gender</label>
                <select name="sexe" id="sexe" class="form-select">
                    <option value="">All</option>
                    <option value="M" {{ $sexeFilter === 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ $sexeFilter === 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <button type="submit" formaction="{{ route('promotion_apprenant.export') }}" formmethod="POST"
                    class="btn btn-success ms-2">
                    @csrf
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </div>
        </form>

        <a href="{{ route('promotion_apprenant.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Add a student to a promotion
        </a>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($promotions->isEmpty())
            <div class="alert alert-info">
                No promotion found for your site.
            </div>
        @else
            @foreach ($promotions as $promotion)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            Promotion: {{ $promotion->num_promotion }}
                        </h5>
                        <div>
                            <span class="badge bg-light text-dark">
                                {{ $promotion->students->count() }} student(s)
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($promotion->students->isEmpty())
                            <div class="alert alert-warning mb-0">
                                No students in this promotion.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($promotion->paginated_students as $index => $student)
                                            <tr>
                                                <td>{{ $promotion->paginated_students->firstItem() + $index }}</td>
                                                <td>{{ $student->last_name }}</td>
                                                <td>{{ $student->first_name }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->contact }}</td>
                                                <td>
                                                    @if ($student->sexe === 'M')
                                                        <span class="badge bg-primary">M</span>
                                                    @elseif($student->sexe === 'F')
                                                        <span class="badge bg-danger">F</span>
                                                    @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $relation = $promotion->promotionApprenants
                                                            ->where('student_id', $student->id)
                                                            ->first();
                                                    @endphp

                                                    @if ($relation)
                                                        <a href="{{ route('promotion_apprenant.edit', $relation->id) }}"
                                                            class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('promotion_apprenant.destroy', $relation->id) }}"
                                                            method="POST" style="display:inline;"
                                                            onsubmit="return confirm('Remove this student from the promotion?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Delete">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($promotion->paginated_students->hasPages())
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $promotion->paginated_students->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                Created on: {{ $promotion->created_at->format('d/m/Y H:i') }}
                            </small>
                            <div>
                                <a href="{{ route('promotions.edit', $promotion->id) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-cog"></i> Manage promotion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-4">
                {{ $promotions->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
