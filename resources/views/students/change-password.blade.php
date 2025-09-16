@extends('base_admin')
@section('title', 'Change Student Password')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Change Student Password</h4>
                </div>
                <div class="card-body">
                    @livewire('student-password-change', ['studentId' => $studentId])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
