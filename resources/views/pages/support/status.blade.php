@extends('layouts.app')
@section('title', 'Status — Border Buyers')
@section('meta_description', 'Current service status and uptime information for Border Buyers.')
@section('content')
  @include('components.navbar')
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Service Status</h1>
    <p class="mt-4 text-gray-600">All systems operational.</p>
    <div class="mt-6 grid gap-4">
      <div class="p-4 bg-white border rounded-lg flex items-center justify-between">
        <span>Web App</span>
        <span class="text-green-700">Operational</span>
      </div>
      <div class="p-4 bg-white border rounded-lg flex items-center justify-between">
        <span>Escrow Service</span>
        <span class="text-green-700">Operational</span>
      </div>
      <div class="p-4 bg-white border rounded-lg flex items-center justify-between">
        <span>Monitoring</span>
        <span class="text-green-700">Operational</span>
      </div>
    </div>
  </div>
  @include('components.footer')
@endsection