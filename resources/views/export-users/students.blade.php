@extends('base')

@section('title', 'Liste des Utilisateurs')

@section('content')
<div class="container">
    <!-- resources/views/students.blade.php -->

    <form action="{{ route('students.export') }}" method="GET">
        @csrf
        <label for="site_id">Training center:</label>
        <select name="site_id" id="site_id">
            @foreach($sites as $site)
                <option value="{{ $site->id }}">{{ $site->designation }}</option>
            @endforeach
        </select>

        <label for="promotion_id">Promotion:</label>
        <select name="promotion_id" id="promotion_id">
            @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
            @endforeach
        </select>

        <button type="submit">Exporter</button>
    </form>

    <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Importer</button>
    </form>
</div>
@endsection
