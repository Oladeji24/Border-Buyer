@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text display-4">{{ $stats['users'] }}</p>
                    <a href="{{ route('admin.users') }}" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Verified Agents</h5>
                    <p class="card-text display-4">{{ $stats['agents'] }}</p>
                    <a href="{{ route('admin.agents') }}" class="btn btn-primary">Manage Agents</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Pending Agents</h5>
                    <p class="card-text display-4">{{ $stats['pending_agents'] }}</p>
                    <a href="{{ route('admin.agents.pending') }}" class="btn btn-warning">Review Pending</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Transactions</h5>
                    <p class="card-text display-4">{{ $stats['transactions'] }}</p>
                    <a href="{{ route('admin.transactions') }}" class="btn btn-primary">Manage Transactions</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Marketplace Listings</h5>
                    <p class="card-text display-4">{{ $stats['marketplace_listings'] }}</p>
                    <a href="#" class="btn btn-primary">Manage Listings</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Service Requests</h5>
                    <p class="card-text display-4">{{ $stats['service_requests'] }}</p>
                    <a href="{{ route('admin.services') }}" class="btn btn-primary">Manage Services</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection