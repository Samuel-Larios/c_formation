@extends('base_admin')

@section('title', 'Edit a Promotion')

@section('content')
<div class="container">
    <h2>Edit Promotion</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('promotions.update', $promotion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Promotion Number:</label>
            <input type="text" name="num_promotion" class="form-control" value="{{ $promotion->num_promotion }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
