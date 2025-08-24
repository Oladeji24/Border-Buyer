@extends('layouts.app')
@section('title', 'Documentation — Border Buyers')
@section('meta_description', 'Get started guides, FAQs, and best practices for using Border Buyers.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Documentation</h1>
    <p class="mt-4 text-gray-600">Learn how to use Border Buyers effectively. Explore guides below.</p>

    <div class="mt-8 space-y-6">
      <a class="block p-5 border rounded-lg bg-white hover:shadow" href="{{ route('howitworks') }}">
        <h2 class="font-semibold">How it works</h2>
        <p class="text-gray-600">Understand the full escrow and monitoring lifecycle.</p>
      </a>
      <a class="block p-5 border rounded-lg bg-white hover:shadow" href="{{ route('solutions.escrow') }}">
        <h2 class="font-semibold">Escrow basics</h2>
        <p class="text-gray-600">Deposits, releases, and timelines.</p>
      </a>
      <a class="block p-5 border rounded-lg bg-white hover:shadow" href="{{ route('solutions.monitoring') }}">
        <h2 class="font-semibold">Monitoring</h2>
        <p class="text-gray-600">Tracking shipments and resolving exceptions.</p>
      </a>
    </div>
  </div>
  @include('components.footer')
@endsection