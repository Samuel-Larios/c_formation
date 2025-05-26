@extends('base')

@section('title', 'Add a User')

@section('content')
<div class="container">
    <h1>Add a User</h1>

    <form action="{{ route('utilisateurs.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="date_naissance">Date of Birth</label>
            <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="{{ old('date_naissance') }}" required>
        </div>

        <div class="form-group">
            <label for="tel">Phone Number</label>
            <input type="tel" name="tel" id="tel" class="form-control" value="{{ old('tel') }}" required>
        </div>

        <div class="form-group">
            <label for="tel2">Second Phone Number</label>
            <input type="tel" name="tel2" id="tel2" class="form-control" value="{{ old('tel2') }}">
        </div>

        <div class="form-group">
            <label for="poste">Position</label>
            <input type="text" name="poste" id="poste" class="form-control" value="{{ old('poste') }}">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="admin_principal" {{ old('role') == 'admin_principal' ? 'selected' : '' }}>Main Admin</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="text" name="password" id="password" class="form-control" value="{{ old('password', Str::random(10)) }}" required>
            <small class="form-text text-muted">
                You can modify the automatically generated password.
            </small>
        </div>

        <div class="form-group">
            <label for="site_id">Training center</label>
            <select name="site_id" id="site_id" class="form-control" required>
                <option value="">Choose a training center</option>
                @foreach ($sites as $site)
                    <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                        {{ $site->designation }} - {{ $site->emplacement }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
