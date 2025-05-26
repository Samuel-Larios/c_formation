@extends('base_admin')

@section('title', 'Add a Student')

<!-- Include intl-tel-input CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
<!-- Include FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@section('content')
<div class="container">
    <h2 class="mt-3 mb-4 text-center">Add a New Student</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <!-- Section: Personal Information -->
        <div class="mb-4">
            <h5 class="mb-3"><i class="fas fa-user-circle me-2"></i>Personal Information</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name: <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name: <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="sexe" class="form-label">Gender: <span class="text-danger">*</span></label>
                    <select name="sexe" class="form-select" required>
                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="situation_matrimoniale" class="form-label">Marital Status:</label>
                    <select name="situation_matrimoniale" class="form-select">
                        <option value="">Select a status</option>
                        <option value="Single" {{ old('situation_matrimoniale') == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ old('situation_matrimoniale') == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Divorced" {{ old('situation_matrimoniale') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="Widowed" {{ old('situation_matrimoniale') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="Separated" {{ old('situation_matrimoniale') == 'Separated' ? 'selected' : '' }}>Separated</option>
                        <option value="In a domestic partnership" {{ old('situation_matrimoniale') == 'In a domestic partnership' ? 'selected' : '' }}>In a domestic partnership</option>
                        <option value="In a civil union" {{ old('situation_matrimoniale') == 'In a civil union' ? 'selected' : '' }}>In a civil union</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="situation_handicape" class="form-label">Handicap Situation:</label>
                    <select name="situation_handicape" class="form-select">
                        <option value="">Select a status</option>
                        <option value="None" {{ old('situation_handicape') == 'None' ? 'selected' : '' }}>None</option>
                        <option value="Physical" {{ old('situation_handicape') == 'Physical' ? 'selected' : '' }}>Physical</option>
                        <option value="Visual" {{ old('situation_handicape') == 'Visual' ? 'selected' : '' }}>Visual</option>
                        <option value="Hearing" {{ old('situation_handicape') == 'Hearing' ? 'selected' : '' }}>Hearing</option>
                        <option value="Intellectual" {{ old('situation_handicape') == 'Intellectual' ? 'selected' : '' }}>Intellectual</option>
                        <option value="Mental" {{ old('situation_handicape') == 'Mental' ? 'selected' : '' }}>Mental</option>
                        <option value="Other" {{ old('situation_handicape') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_naissance" class="form-label">Date of Birth:</label>
                    <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance') }}" max="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>

        <!-- Section: Contact Information -->
        <div class="mb-4">
            <h5 class="mb-3"><i class="fas fa-address-book me-2"></i>Contact Information</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact" class="form-label">Phone Number:</label>
                    <input type="tel" name="contact" class="form-control phone-input" value="{{ old('contact') }}">
                    <input type="hidden" name="contact_full" id="contact_full">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="state_of_origin" class="form-label">State of Origin: <span class="text-danger">*</span></label>
                    <input type="text" name="state_of_origin" class="form-control" required value="{{ old('state_of_origin') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="state_of_residence" class="form-label">State of Residence: <span class="text-danger">*</span></label>
                    <input type="text" name="state_of_residence" class="form-control" required value="{{ old('state_of_residence') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="site_id" class="form-label">Training center:</label>
                    <input type="text" class="form-control" value="{{ $site->designation }}" readonly>
                    <input type="hidden" name="site_id" value="{{ $site->id }}">
                </div>
            </div>
        </div>

        <!-- Section: Emergency Contacts -->
        <div class="mb-4">
            <h5 class="mb-3"><i class="fas fa-users me-2"></i>Emergency Contacts</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact_pers1" class="form-label">Contact Person 1:</label>
                    <input type="tel" name="contact_pers1" class="form-control phone-input" value="{{ old('contact_pers1') }}">
                    <input type="hidden" name="contact_pers1_full" id="contact_pers1_full">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_pers2" class="form-label">Contact Person 2:</label>
                    <input type="tel" name="contact_pers2" class="form-control phone-input" value="{{ old('contact_pers2') }}">
                    <input type="hidden" name="contact_pers2_full" id="contact_pers2_full">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact_pers3" class="form-label">Contact Person 3:</label>
                    <input type="tel" name="contact_pers3" class="form-control phone-input" value="{{ old('contact_pers3') }}">
                    <input type="hidden" name="contact_pers3_full" id="contact_pers3_full">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_pers4" class="form-label">Contact Person 4:</label>
                    <input type="tel" name="contact_pers4" class="form-control phone-input" value="{{ old('contact_pers4') }}">
                    <input type="hidden" name="contact_pers4_full" id="contact_pers4_full">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact_pers5" class="form-label">Contact Person 5:</label>
                    <input type="tel" name="contact_pers5" class="form-control phone-input" value="{{ old('contact_pers5') }}">
                    <input type="hidden" name="contact_pers5_full" id="contact_pers5_full">
                </div>
            </div>
        </div>

        <!-- Section: Farm Location -->
        <div class="mb-4">
            <h5 class="mb-3"><i class="fas fa-tractor me-2"></i>Farm Location</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="state" class="form-label">State:</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="lga" class="form-label">LGA:</label>
                    <input type="text" name="lga" class="form-control" value="{{ old('lga') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="community" class="form-label">Community:</label>
                    <input type="text" name="community" class="form-control" value="{{ old('community') }}">
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-success me-md-2"><i class="fas fa-save me-2"></i>Save</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
        </div>
    </form>
</div>

<!-- Include JS files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize all phone inputs with Nigeria as default country
        const phoneInputs = document.querySelectorAll(".phone-input");

        phoneInputs.forEach(input => {
            const iti = window.intlTelInput(input, {
                initialCountry: "ng", // Nigeria as default country
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            // Store the full number (with country code) in a hidden field before form submission
            input.addEventListener('blur', function() {
                const hiddenField = document.getElementById(input.name + '_full');
                if (iti.isValidNumber()) {
                    hiddenField.value = iti.getNumber();
                } else {
                    hiddenField.value = '';
                }
            });
        });

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let valid = true;

            phoneInputs.forEach(input => {
                const iti = window.intlTelInputGlobals.getInstance(input);
                if (input.value && !iti.isValidNumber()) {
                    alert('Please enter a valid phone number for ' + input.name);
                    valid = false;
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
