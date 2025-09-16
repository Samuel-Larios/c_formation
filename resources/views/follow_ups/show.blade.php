@extends('base_admin')
@section('title', 'Follow-up Details')

@section('content')
<div class="container my-4">
    <a href="{{ route('follow_ups.index') }}" class="btn btn-secondary mb-4">Back to List</a>
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0">Follow-up Details</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Farm Visits</dt>
                <dd class="col-sm-9">{{ $followUp->farm_visits }}</dd>

                <dt class="col-sm-3">Phone Contact</dt>
                <dd class="col-sm-9">{{ $followUp->phone_contact }}</dd>

                <dt class="col-sm-3">Sharing of Impact Stories</dt>
                <dd class="col-sm-9">{{ $followUp->sharing_of_impact_stories }}</dd>

                <dt class="col-sm-3">Back-stopping</dt>
                <dd class="col-sm-9">{{ $followUp->back_stopping }}</dd>

                <dt class="col-sm-3">Student</dt>
                <dd class="col-sm-9">{{ $followUp->student->first_name }} {{ $followUp->student->last_name }}</dd>

                <dt class="col-sm-3">Site</dt>
                <dd class="col-sm-9">{{ $followUp->site->name }}</dd>

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $followUp->created_at->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Updated At</dt>
                <dd class="col-sm-9">{{ $followUp->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>

            <h5 class="mt-4">Images ({{ $followUp->images->count() }})</h5>
            @if($followUp->images->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($followUp->images as $image)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Follow-up Image" class="img-fluid" style="max-width: 200px; max-height: 200px; object-fit: contain;">
                                </td>
                                <td>Image {{ $loop->iteration }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No images available.</p>
            @endif
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('follow_ups.edit', $followUp->id) }}" class="btn btn-warning me-2">Edit</a>
            <form action="{{ route('follow_ups.destroy', $followUp->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this follow-up?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
