@extends('base')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1>Statistics</h1>

    <!-- Form to filter students by site and promotion -->
    <form action="{{ route('statistics.filter.students') }}" method="POST" class="mb-4">
        @csrf
        <h3>Filter Students</h3>
        <div class="form-group">
            <label for="site_id">Training center:</label>
            <select name="site_id" id="site_id" class="form-control" onchange="this.form.submit()">
                <option value="">Select a training center</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->designation }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="promotion_id">Promotion:</label>
            <select name="promotion_id" id="promotion_id" class="form-control" onchange="this.form.submit()">
                <option value="">Select a promotion</option>
                @if(isset($promotions))
                    @foreach($promotions as $promotion)
                        <option value="{{ $promotion->id }}" {{ request('promotion_id') == $promotion->id ? 'selected' : '' }}>{{ $promotion->num_promotion }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </form>

    <!-- Form to filter subjects -->
    {{-- <form action="{{ route('statistics.filter.matiers') }}" method="POST" class="mb-4">
        @csrf
        <h3>Filter Subjects</h3>
        <div class="form-group">
            <label for="matier_id">Subject:</label>
            <select name="matier_id" id="matier_id" class="form-control" onchange="this.form.submit()">
                <option value="">Select a subject</option>
                @foreach($matiers as $matier)
                    <option value="{{ $matier->id }}" {{ request('matier_id') == $matier->id ? 'selected' : '' }}>{{ $matier->designation }}</option>
                @endforeach
            </select>
        </div>
    </form> --}}

    <!-- Form to filter users by site -->
    {{-- <form action="{{ route('statistics.filter.users') }}" method="POST" class="mb-4">
        @csrf
        <h3>Filter Users</h3>
        <div class="form-group">
            <label for="site_id">Site:</label>
            <select name="site_id" id="site_id" class="form-control" onchange="this.form.submit()">
                <option value="">Select a site</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->designation }}</option>
                @endforeach
            </select>
        </div>
    </form> --}}

    <!-- Display filtered results -->
    @if(isset($students))
        <h3>Students</h3>
        <ul>
            @foreach($students as $student)
                <li>{{ $student->first_name }} {{ $student->last_name }}</li>
            @endforeach
        </ul>
    @endif

    {{-- @if(isset($matiers))
        <h3>Subjects</h3>
        <ul>
            @foreach($matiers as $matier)
                <li>{{ $matier->designation }} (Coefficient: {{ $matier->coef }})</li>
            @endforeach
        </ul>
    @endif --}}

    @if(isset($users))
        <h3>Users</h3>
        <ul>
            @foreach($users as $user)
                <li>{{ $user->name }} ({{ $user->role }})</li>
            @endforeach
        </ul>
    @endif

    <!-- Link to print -->
    <a href="{{ route('statistics.print', [
        'site_id' => request('site_id'),
        'promotion_id' => request('promotion_id')
    ]) }}" target="_blank" class="btn btn-primary">Print Statistics</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Update promotions when a site is selected
        $('#site_id').on('change', function() {
            var siteId = $(this).val();
            $.ajax({
                url: '/get-promotions',
                type: 'GET',
                data: { site_id: siteId },
                success: function(data) {
                    $('#promotion_id').html(data);
                }
            });
        });
    });
</script>
@endsection
