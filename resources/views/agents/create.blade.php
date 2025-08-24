@extends('layouts.app')

@section('title', 'Become an Agent')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Become a Verified Border Agent</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Join our network of verified border trade agents. Complete the form below and submit required 
                        documents for verification. Our team will review your application and get back to you within 3-5 business days.
                    </p>

                    <form method="POST" action="{{ route('agent.profile.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h5>Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" value="{{ auth()->user()->name }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" value="{{ auth()->user()->country }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" value="{{ auth()->user()->phone }}" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="mb-4">
                            <h5>Professional Information</h5>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Professional Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4" required
                                          placeholder="Tell us about your experience in border trade...">{{ old('bio') }}</textarea>
                                <div class="form-text">Minimum 50 characters</div>
                                @error('bio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" required
                                       placeholder="e.g. Electronics, Textiles, Agriculture" value="{{ old('specialization') }}">
                                @error('specialization')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="experience_years" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                       min="1" required value="{{ old('experience_years') }}">
                                @error('experience_years')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="languages" class="form-label">Languages</label>
                                <select class="form-select" id="languages" name="languages[]" multiple required>
                                    <option value="English" {{ in_array('English', old('languages', [])) ? 'selected' : '' }}>English</option>
                                    <option value="Mandarin" {{ in_array('Mandarin', old('languages', [])) ? 'selected' : '' }}>Mandarin</option>
                                    <option value="Spanish" {{ in_array('Spanish', old('languages', [])) ? 'selected' : '' }}>Spanish</option>
                                    <option value="French" {{ in_array('French', old('languages', [])) ? 'selected' : '' }}>French</option>
                                    <option value="Arabic" {{ in_array('Arabic', old('languages', [])) ? 'selected' : '' }}>Arabic</option>
                                    <option value="Hindi" {{ in_array('Hindi', old('languages', [])) ? 'selected' : '' }}>Hindi</option>
                                    <option value="Portuguese" {{ in_array('Portuguese', old('languages', [])) ? 'selected' : '' }}>Portuguese</option>
                                    <option value="Russian" {{ in_array('Russian', old('languages', [])) ? 'selected' : '' }}>Russian</option>
                                    <option value="Japanese" {{ in_array('Japanese', old('languages', [])) ? 'selected' : '' }}>Japanese</option>
                                    <option value="German" {{ in_array('German', old('languages', [])) ? 'selected' : '' }}>German</option>
                                </select>
                                <div class="form-text">Hold Ctrl/Cmd to select multiple languages</div>
                                @error('languages')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="mb-4">
                            <h5>Profile Image</h5>
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Upload Profile Photo</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <div class="form-text">Recommended: Square image, at least 300x300px</div>
                                @error('profile_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Verification Documents -->
                        <div class="mb-4">
                            <h5>Verification Documents</h5>
                            <p class="text-muted">
                                All documents are securely stored and used only for verification purposes. 
                                They will not be shared with third parties.
                            </p>

                            <div class="mb-3">
                                <label for="id_document" class="form-label">ID Document</label>
                                <input type="file" class="form-control" id="id_document" name="id_document" required
                                       accept="image/jpeg,image/jpg,image/png,application/pdf">
                                <div class="form-text">
                                    Passport, National ID, or Driver's License (PDF, JPG, or PNG, max 2MB)
                                </div>
                                @error('id_document')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="business_document" class="form-label">Business Document</label>
                                <input type="file" class="form-control" id="business_document" name="business_document" required
                                       accept="image/jpeg,image/jpg,image/png,application/pdf">
                                <div class="form-text">
                                    Business License, Certificate of Incorporation, or Trade License (PDF, JPG, or PNG, max 2MB)
                                </div>
                                @error('business_document')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms and Submit -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection