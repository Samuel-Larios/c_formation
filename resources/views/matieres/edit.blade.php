@extends('base_admin')

@section('title', 'Edit a Subject')

@section('content')
<div class="container">
    <h1>Edit a Subject</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('matieres.update', $matier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="designation">Designation</label>
            <input type="text" name="designation" id="designation" class="form-control" value="{{ $matier->designation }}" required>
        </div>

        <div class="form-group">
            <label for="coef">Coefficient</label>
            <input type="number" name="coef" id="coef" class="form-control" value="{{ $matier->coef }}" required min="1">
        </div>

        <div class="form-group">
            <label for="site_id">Training center</label>
            <input type="text" class="form-control" value="{{ $site->designation }}" readonly>
            <input type="hidden" name="site_id" value="{{ $site->id }}">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
