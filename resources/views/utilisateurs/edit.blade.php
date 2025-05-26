@extends('base')

@section('title', 'Edit User')

@section('content')
<div class="container">
    <h1>Edit User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('utilisateurs.update', $utilisateur->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $utilisateur->name) }}">
        </div>

        <div class="form-group">
            <label for="date_naissance">Date of Birth</label>
            <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="{{ old('date_naissance', $utilisateur->date_naissance) }}">
        </div>

        <div class="form-group">
            <label for="tel">Phone Number</label>
            <input type="text" name="tel" id="tel" class="form-control" value="{{ old('tel', $utilisateur->tel) }}">
        </div>

        <div class="form-group">
            <label for="tel2">Secondary Phone Number</label>
            <input type="text" name="tel2" id="tel2" class="form-control" value="{{ old('tel2', $utilisateur->tel2) }}">
        </div>

        <div class="form-group">
            <label for="poste">Position</label>
            <input type="text" name="poste" id="poste" class="form-control" value="{{ old('poste', $utilisateur->poste) }}">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control">
                <option value="admin_principal" {{ old('role', $utilisateur->role) == 'admin_principal' ? 'selected' : '' }}>Admin Principal</option>
                <option value="user" {{ old('role', $utilisateur->role) == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $utilisateur->email) }}">
        </div>

        <div class="form-group">
            <label for="password">New Password (leave blank if unchanged)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label for="site_id">Training center</label>
            <select name="site_id" id="site_id" class="form-control">
                @foreach ($sites as $site)
                    <option value="{{ $site->id }}" {{ old('site_id', $utilisateur->site_id) == $site->id ? 'selected' : '' }}>
                        {{ $site->designation }} - {{ $site->emplacement }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
@endsection
