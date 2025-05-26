@extends('base')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Nombre d'étudiants inscrits par training center et période</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h4>Site: {{ $site->designation }} - {{ $site->emplacement }}</h4>
            <p>Période: du {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}</p>
            <h3>Nombre d'étudiants inscrits: {{ $count }}</h3>
        </div>
        <a href="{{ route('statistics.export.students_by_site_period', [
            'site_id' => $site->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]) }}" class="btn btn-success">Exporter en PDF</a>
        <a href="{{ route('statistics.students_by_site_period.form') }}" class="btn btn-secondary">Nouvelle recherche</a>
    </div>
</div>
@endsection
