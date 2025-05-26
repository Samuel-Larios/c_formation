@extends('base')
@section('title', 'List of Production Sites')

@section('content')
<div class="container">
    <h1>List of training center</h1>
    <a href="{{ route('sites.create') }}" class="btn btn-primary">Add a Site</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Training center</th>
                <th>Location</th>
                <th style="width: 300px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sites as $site)
            <tr>
                <td>{{ $site->designation }}</td>
                <td>{{ $site->emplacement }}</td>
                <td>
                    <a href="{{ route('sites.edit', $site->id) }}" class="btn btn-warning">Modify</a>
                    <form action="{{ route('sites.destroy', $site->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeletion(event, '{{ $site->designation }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Scripte de supression --}}
@section('scripts')
<script>
    function confirmDeletion(event, siteName) {
        event.preventDefault(); // Empêche la soumission immédiate du formulaire

        if (confirm(`Are you sure you want to delete the training center "${siteName}"?`)) {
            event.target.submit(); // Soumet le formulaire si l'utilisateur confirme
        }
    }
</script>
@endsection

@endsection
