@extends('base')
@section('title', 'Edit Training center')

@section('content')
<div class="container">
    <h1>Edit training center</h1>

    <form action="{{ route('sites.update', $site->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="designation" class="form-label">Name of training center</label>
            <input type="text" name="designation" class="form-control" value="{{ $site->designation }}" required>
        </div>
        <div class="mb-3">
            <label for="emplacement" class="form-label">Location</label>
            <input type="text" name="emplacement" class="form-control" value="{{ $site->emplacement }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
