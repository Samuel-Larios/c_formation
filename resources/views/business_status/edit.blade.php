@extends('base_admin')

@section('title', 'Edit Business Status')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Business Status</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('business_status.update', $businessStatus->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="type_of_business" class="form-label">Business Type</label>
                        <input type="text" class="form-control @error('type_of_business') is-invalid @enderror" id="type_of_business" name="type_of_business" value="{{ old('type_of_business', $businessStatus->type_of_business) }}" required>
                        @error('type_of_business')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @php($currentStatus = old('status', $businessStatus->status))
                            <option value="">-- Select status --</option>
                            <option value="Registered" {{ $currentStatus === 'Registered' ? 'selected' : '' }}>Registered</option>
                            <option value="Non registered" {{ $currentStatus === 'Non registered' ? 'selected' : '' }}>Non registered</option>
                            <option value="Cooperative" {{ $currentStatus === 'Cooperative' ? 'selected' : '' }}>Cooperative</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="student_id" class="form-label">Student</label>
                        <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                            <option value="">-- Select student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ (string) old('student_id', $businessStatus->student_id) === (string) $student->id ? 'selected' : '' }}>
                                    {{ $student->last_name }} {{ $student->first_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="site_id" class="form-label">Training Center</label>
                        <select class="form-select @error('site_id') is-invalid @enderror" id="site_id" name="site_id">
                            <option value="">-- Select training center --</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ (string) old('site_id', $businessStatus->site_id) === (string) $site->id ? 'selected' : '' }}>
                                    {{ $site->designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('site_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('business_status.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to list
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
