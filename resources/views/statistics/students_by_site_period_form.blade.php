@extends('base')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Nombre d'étudiants inscrits par training center et période</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('statistics.students_by_site_period') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="site_id" class="form-label">training center</label>
                <select class="form-select" id="site_id" name="site_id" required>
                    <option value="">Sélectionner un training center</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->designation }} - {{ $site->emplacement }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Date de fin</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Générer le rapport</button>
        </form>
    </div>
</div>
@endsection
