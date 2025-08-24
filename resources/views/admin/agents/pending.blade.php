@extends('layouts.app')

@section('title', 'Pending Agent Approvals')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Pending Agent Approvals</h1>
            <p class="lead">Review and approve agent applications from our community members.</p>
        </div>
    </div>

    @if($pendingAgents->count() > 0)
    <div class="row">
        @foreach($pendingAgents as $agent)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $agent->user->name }}'s Application</h5>
                    <span class="badge bg-warning">Pending Review</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.agents.approve', $agent->id) }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="{{ $agent->user->profile_image ?: asset('images/default-avatar.png') }}" 
                                     alt="{{ $agent->user->name }}" 
                                     class="rounded-circle mb-3" 
                                     width="120" 
                                     height="120">

                                <div class="mb-3">
                                    <h6>Contact Information</h6>
                                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $agent->user->email }}</p>
                                    @if($agent->user->phone)
                                    <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ $agent->user->phone }}</p>
                                    @endif
                                    <p class="mb-0"><i class="fas fa-globe me-2"></i> {{ $agent->user->country }}</p>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ $agent->id_document_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-id-card me-2"></i> View ID Document
                                    </a>
                                    <a href="{{ $agent->business_document_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-contract me-2"></i> View Business Document
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Professional Information</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Specialization:</strong></td>
                                                <td>{{ $agent->specialization }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Experience:</strong></td>
                                                <td>{{ $agent->experience_years }} years</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Languages:</strong></td>
                                                <td>
                                                    @if($agent->languages)
                                                        @foreach($agent->languages as $language)
                                                            <span class="badge bg-light text-dark me-1">{{ $language }}</span>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Application Details</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Submitted:</strong></td>
                                                <td>{{ $agent->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td><span class="badge bg-warning">Pending</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6>Professional Bio</h6>
                                    <p>{{ $agent->bio }}</p>
                                </div>

                                <div class="mb-3">
                                    <h6>Review Decision</h6>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="verification_status" id="status_verified" value="verified" required>
                                        <label class="form-check-label" for="status_verified">
                                            <i class="fas fa-check-circle text-success me-1"></i> Approve
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="verification_status" id="status_rejected" value="rejected" required>
                                        <label class="form-check-label" for="status_rejected">
                                            <i class="fas fa-times-circle text-danger me-1"></i> Reject
                                        </label>
                                    </div>

                                    <div class="mt-3" id="rejection_reason_div" style="display: none;">
                                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i> Submit Decision
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> There are no pending agent applications at the moment.
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusVerified = document.getElementById('status_verified');
        const statusRejected = document.getElementById('status_rejected');
        const rejectionReasonDiv = document.getElementById('rejection_reason_div');

        statusRejected.addEventListener('change', function() {
            if (this.checked) {
                rejectionReasonDiv.style.display = 'block';
                document.getElementById('rejection_reason').setAttribute('required', 'required');
            }
        });

        statusVerified.addEventListener('change', function() {
            if (this.checked) {
                rejectionReasonDiv.style.display = 'none';
                document.getElementById('rejection_reason').removeAttribute('required');
            }
        });
    });
</script>
@endpush