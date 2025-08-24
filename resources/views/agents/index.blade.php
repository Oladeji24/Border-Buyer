@extends('layouts.app')

@section('title', 'Verified Agents')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Verified Border Agents</h1>
            <p class="lead">Browse our directory of vetted and trusted agents specializing in cross-border trade.</p>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('agents.directory') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i> Advanced Search
                    </a>
                </div>
                @auth
                @if(auth()->user()->role === 'agent' && !auth()->user()->agentProfile)
                <div>
                    <a href="{{ route('agent.profile.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i> Become an Agent
                    </a>
                </div>
                @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="row">
        @forelse ($agents as $agent)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 agent-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $agent->user->profile_image ?: asset('images/default-avatar.png') }}" 
                             alt="{{ $agent->user->name }}" 
                             class="rounded-circle me-3" 
                             width="60" 
                             height="60">
                        <div>
                            <h5 class="mb-0">{{ $agent->user->name }}</h5>
                            <div class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($agent->rating) ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">{{ number_format($agent->rating, 1) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <span class="badge bg-primary mb-2">{{ $agent->specialization }}</span>
                        <span class="badge bg-secondary">{{ $agent->user->country }}</span>
                    </div>

                    <p class="card-text flex-grow-1">{{ Str::limit($agent->bio, 100) }}</p>

                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <small class="text-muted">
                            <i class="fas fa-briefcase me-1"></i> {{ $agent->completed_transactions }} transactions
                        </small>
                        <a href="{{ route('agents.show', $agent->user_id) }}" class="btn btn-sm btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                No verified agents found at the moment. Please check back later.
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $agents->links() }}
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