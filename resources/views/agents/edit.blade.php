@extends('layouts.app')

@section('title', 'Edit Agent Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Your Agent Profile</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('agent.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Status -->
                        @if($agentProfile->isVerified())
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i> Your profile is verified and visible to clients
                        </div>
                        @elseif($agentProfile->isPending())
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-clock me-2"></i> Your profile is under review
                        </div>
                        @elseif($agentProfile->isRejected())
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-times-circle me-2"></i> Your profile was rejected: {{ $agentProfile->rejection_reason }}
                        </div>
                        @endif

                        <!-- Professional Information -->
                        <div class="mb-4">
                            <h5>Professional Information</h5>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Professional Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4" required
                                          placeholder="Tell us about your experience in border trade...">{{ old('bio', $agentProfile->bio) }}</textarea>
                                <div class="form-text">Minimum 50 characters</div>
                                @error('bio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" required
                                       placeholder="e.g. Electronics, Textiles, Agriculture" value="{{ old('specialization', $agentProfile->specialization) }}">
                                @error('specialization')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="experience_years" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                       min="1" required value="{{ old('experience_years', $agentProfile->experience_years) }}">
                                @error('experience_years')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="languages" class="form-label">Languages</label>
                                <select class="form-select" id="languages" name="languages[]" multiple required>
                                    <option value="English" {{ in_array('English', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>English</option>
                                    <option value="Mandarin" {{ in_array('Mandarin', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>Mandarin</option>
                                    <option value="Spanish" {{ in_array('Spanish', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>Spanish</option>
                                    <option value="French" {{ in_array('French', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>French</option>
                                    <option value="Arabic" {{ in_array('Arabic', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>Arabic</option>
                                    <option value="Hindi" {{ in_array('Hindi', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>Hindi</option>
                                    <option value="Portuguese" {{ in_array('Portuguese', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>Portuguese</option>
                                    <option value="Russian" {{ in_array('Russian', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>Russian</option>
                                    <option value="Japanese" {{ in_array('Japanese', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>Japanese</option>
                                    <option value="German" {{ in_array('German', old('languages', json_decode($agentProfile->languages, true) ?? [])) ? 'selected' : '' }}>German</option>
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
                                <label for="profile_image" class="form-label">Update Profile Photo</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <div class="form-text">Recommended: Square image, at least 300x300px</div>
                                @error('profile_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                @if(auth()->user()->profile_image)
                                <div class="mt-2">
                                    <p>Current Profile Image:</p>
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                                         alt="Profile Image" 
                                         class="img-thumbnail" 
                                         width="150">
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Verification Documents -->
                        <div class="mb-4">
                            <h5>Verification Documents</h5>
                            <p class="text-muted">
                                Your documents have been submitted and are under review. 
                                If you need to update your documents, please contact support.
                            </p>

                            <div class="mb-3">
                                <label class="form-label">ID Document</label>
                                <div class="alert alert-info">
                                    <i class="fas fa-file me-2"></i> 
                                    {{ basename($agentProfile->id_document_path) }}
                                    <span class="badge bg-success ms-2">Submitted</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Business Document</label>
                                <div class="alert alert-info">
                                    <i class="fas fa-file me-2"></i> 
                                    {{ basename($agentProfile->business_document_path) }}
                                    <span class="badge bg-success ms-2">Submitted</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection