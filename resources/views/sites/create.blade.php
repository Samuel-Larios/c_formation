@extends('base')
@section('title', 'Create a production training center')

@section('content')
<div class="container">
    <h1>Add a training center</h1>

    <form action="{{ route('sites.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="designation" class="form-label">Name of training center</label>
            <input type="text" name="designation" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="emplacement" class="form-label">Location</label>
            <input type="text" name="emplacement" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
