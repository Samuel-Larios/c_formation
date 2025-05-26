@extends('base_student')
@section('title', 'Edit an Entity')

@section('content')
<div class="container">
    <h1>Edit an Entity</h1>
    <form action="{{ route('student.entities.update', $entity->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="activity">Activity</label>
            <input type="text" name="activity" id="activity" class="form-control" value="{{ $entity->activity }}" required>
        </div>
        <button type="submit" class="btn btn-warning">Edit</button>
        <a href="{{ route('student.entities.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
