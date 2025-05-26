@extends('base_admin')

@section('title', 'Add a Promotion')

@section('content')
<div class="container">
    <h2>Add a Promotion</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('promotions.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Promotion Number:</label>
            <input type="text" name="num_promotion" class="form-control" required>
        </div>

        {{-- <button type="submit" class="btn btn-primary mt-3">Create</button> --}}
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
