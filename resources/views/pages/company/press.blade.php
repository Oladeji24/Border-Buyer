@extends('layouts.app')
@section('title', 'Press — Border Buyers')
@section('meta_description', 'Press resources, logos, and media contact for Border Buyers.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Press</h1>
    <p class="mt-4 text-gray-600">For media inquiries, contact press@borderbuyers.example. Brand assets and founder bios available upon request.</p>
  </div>
  @include('components.footer')
@endsection