@extends('layouts.app')

@section('title', 'Agent Directory')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Agent Directory</h1>
            <p class="lead">Search and filter our directory of vetted border trade agents.</p>
        </div>
    </div>

    <div class="row">
        <!-- Search Filters -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filter Agents</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('agents.directory') }}">
                        <!-- Keyword Search -->
                        <div class="mb-3">
                            <label for="query" class="form-label">Keywords</label>
                            <input type="text" class="form-control" id="query" name="query" 
                                   value="{{ request('query') }}" placeholder="Search by name, bio, etc.">
                        </div>

                        <!-- Specialization Filter -->
                        <div class="mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <select class="form-select" id="specialization" name="specialization">
                                <option value="">All Specializations</option>
                                @foreach ($specializations as $specialization)
                                <option value="{{ $specialization }}" {{ request('specialization') == $specialization ? 'selected' : '' }}>
                                    {{ $specialization }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Country Filter -->
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country">
                                <option value="">All Countries</option>
                                @foreach ($countries as $country)
                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Minimum Rating Filter -->
                        <div class="mb-3">
                            <label for="min_rating" class="form-label">Minimum Rating</label>
                            <select class="form-select" id="min_rating" name="min_rating">
                                <option value="">Any Rating</option>
                                <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ Stars</option>
                                <option value="4.0" {{ request('min_rating') == '4.0' ? 'selected' : '' }}>4.0+ Stars</option>
                                <option value="3.5" {{ request('min_rating') == '3.5' ? 'selected' : '' }}>3.5+ Stars</option>
                                <option value="3.0" {{ request('min_rating') == '3.0' ? 'selected' : '' }}>3.0+ Stars</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i> Apply Filters
                            </button>
                            <a href="{{ route('agents.directory') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Search Results</h4>
                <span class="badge bg-primary">{{ $agents->count() }} agents found</span>
            </div>

            @forelse ($agents as $agent)
            <div class="card mb-4 agent-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <img src="{{ $agent->user->profile_image ?: asset('images/default-avatar.png') }}" 
                                 alt="{{ $agent->user->name }}" 
                                 class="rounded-circle mb-2" 
                                 width="100" 
                                 height="100">
                            <div class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($agent->rating) ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted">{{ number_format($agent->rating, 1) }}</span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h5>{{ $agent->user->name }}</h5>
                            <div class="mb-2">
                                <span class="badge bg-primary me-2">{{ $agent->specialization }}</span>
                                <span class="badge bg-secondary">{{ $agent->user->country }}</span>
                                <span class="badge bg-info">{{ $agent->experience_years }} years experience</span>
                            </div>
                            <p>{{ Str::limit($agent->bio, 200) }}</p>

                            <div class="mb-2">
                                <strong>Languages:</strong>
                                @if($agent->languages)
                                    @foreach($agent->languages as $language)
                                        <span class="badge bg-light text-dark me-1">{{ $language }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <div>
                                <i class="fas fa-briefcase me-1"></i> {{ $agent->completed_transactions }} transactions completed
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="mb-3">
                                <a href="{{ route('agents.show', $agent->user_id) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-user me-2"></i> View Profile
                                </a>
                            </div>

                            @if(auth()->check() && auth()->user()->role === 'buyer')
                            <div>
                                <button class="btn btn-success w-100">
                                    <i class="fas fa-handshake me-2"></i> Request Service
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info">
                No agents match your search criteria. Please try different filters.
            </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $agents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .agent-card {
        transition: transform 0.2s;
    }
    .agent-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush