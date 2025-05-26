@extends('base_admin')

@section('title', 'Add a Subject')

@section('content')
<div class="container">
    <h1>Add a Subject</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('matieres.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="designation">Designation</label>
            <input type="text" name="designation" id="designation" class="form-control" value="{{ old('designation') }}" required>
        </div>

        <div class="form-group">
            <label for="coef">Coefficient</label>
            <input type="number" name="coef" id="coef" class="form-control" value="{{ old('coef') }}" required min="1">
        </div>

        <div class="form-group">
            <label for="site_id">Training center</label>
            <input type="text" class="form-control" value="{{ $site->designation }}" readonly>
            <input type="hidden" name="site_id" value="{{ $site->id }}">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create</button>
        <a href="{{ route('matieres.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
@endsection
