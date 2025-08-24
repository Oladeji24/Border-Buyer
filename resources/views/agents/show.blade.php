@extends('layouts.app')

@section('title', $agent->name . ' - Agent Profile')

@section('content')
<div class="container py-5">
    <!-- Agent Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="{{ $agent->profile_image ?: asset('images/default-avatar.png') }}" 
                                 alt="{{ $agent->name }}" 
                                 class="rounded-circle mb-3" 
                                 width="150" 
                                 height="150">

                            @if($agent->agentProfile && $agent->agentProfile->isVerified())
                            <div class="alert alert-success py-1">
                                <i class="fas fa-check-circle me-1"></i> Verified Agent
                            </div>
                            @elseif($agent->agentProfile && $agent->agentProfile->isPending())
                            <div class="alert alert-warning py-1">
                                <i class="fas fa-clock me-1"></i> Verification Pending
                            </div>
                            @endif

                            <div class="text-warning mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($agent->agentProfile->rating ?? 0) ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">{{ number_format($agent->agentProfile->rating ?? 0, 1) }}</span>
                            </div>

                            <div class="text-muted mb-3">
                                <small>{{ $agent->agentProfile->completed_transactions ?? 0 }} transactions completed</small>
                            </div>

                            @if(auth()->check() && auth()->user()->role === 'buyer')
                            <button class="btn btn-success w-100 mb-2">
                                <i class="fas fa-handshake me-2"></i> Request Service
                            </button>
                            @endif

                            @if(auth()->check() && auth()->user()->id === $agent->id)
                            <a href="{{ route('agent.profile.edit') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </a>
                            @endif
                        </div>

                        <div class="col-md-9">
                            <h2>{{ $agent->name }}</h2>
                            <div class="mb-3">
                                <span class="badge bg-primary me-2">{{ $agent->agentProfile->specialization ?? 'N/A' }}</span>
                                <span class="badge bg-secondary">{{ $agent->country }}</span>
                                @if($agent->agentProfile && $agent->agentProfile->experience_years)
                                <span class="badge bg-info">{{ $agent->agentProfile->experience_years }} years experience</span>
                                @endif
                            </div>

                            @if($agent->agentProfile)
                            <div class="mb-3">
                                <h5>Bio</h5>
                                <p>{{ $agent->agentProfile->bio }}</p>
                            </div>

                            <div class="mb-3">
                                <h5>Languages</h5>
                                @if($agent->agentProfile->languages)
                                    @foreach($agent->agentProfile->languages as $language)
                                        <span class="badge bg-light text-dark me-1">{{ $language }}</span>
                                    @endforeach
                                @else
                                    <p class="text-muted">No languages specified</p>
                                @endif
                            </div>
                            @endif

                            <div class="mb-3">
                                <h5>Contact Information</h5>
                                <p><i class="fas fa-envelope me-2"></i> {{ $agent->email }}</p>
                                @if($agent->phone)
                                <p><i class="fas fa-phone me-2"></i> {{ $agent->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent's Marketplace Listings -->
    @if($marketplaceListings->count() > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="mb-3">Active Marketplace Listings</h3>
            <div class="row">
                @foreach($marketplaceListings as $listing)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if($listing->is_featured)
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge bg-warning">Featured</span>
                        </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $listing->title }}</h5>
                            <p class="card-text">{{ Str::limit($listing->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">{{ $listing->category }}</span>
                                <span class="fw-bold">${{ number_format($listing->price, 2) }}</span>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-exchange-alt me-1"></i> 
                                    {{ $listing->country_from }} → {{ $listing->country_to }}
                                </small>
                                <a href="{{ route('marketplace.show', $listing) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    @if($agent->receivedReviews && $agent->receivedReviews->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-3">Client Reviews</h3>
            <div class="row">
                @foreach($agent->receivedReviews as $review)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h5>{{ $review->reviewer->name }}</h5>
                                <div class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="card-text">{{ $review->comment }}</p>
                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection