@extends('base_admin')

@section('title', 'Filter Entities by Promotion')

@section('content')
<div class="container mt-4">
    <h2>Filter Entities by Promotion</h2>

    <!-- Formulaire de filtrage -->
    <form action="{{ route('entities.filter') }}" method="GET" class="mb-4">
        <div class="row align-items-end">
            <div class="col-md-6">
                <label for="promotion_id" class="form-label">Choose a promotion:</label>
                <select name="promotion_id" id="promotion_id" class="form-select" required>
                    <option value="">-- Select promotion --</option>
                    @foreach($promotions as $promotion)
                        <option value="{{ $promotion->id }}" {{ $promotion->id == $selectedPromotion ? 'selected' : '' }}>
                            {{ $promotion->num_promotion }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Résultats -->
    @if($selectedPromotion && $entities->count())
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Sexe</th>
                        <th>Activity</th>
                        <th>Phone</th>
                        <th>State</th>
                        <th>LGA</th>
                        <th>Community</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entities as $entity)
                        <tr>
                            <td>{{ $entity->student->first_name }} {{ $entity->student->last_name }}</td>
                            <td>{{ $entity->student->sexe }}</td>
                            <td>{{ $entity->activity }}</td>
                            <td>{{ $entity->student->contact }}</td>
                            <td>{{ $entity->student->state }}</td>
                            <td>{{ $entity->student->lga }}</td>
                            <td>{{ $entity->student->community }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($selectedPromotion)
        <p class="text-danger">No data found for the selected promotion.</p>
    @endif
</div>
@endsection
