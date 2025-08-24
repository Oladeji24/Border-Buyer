@extends('layouts.app')
@section('title', 'Jobs — Border Buyers')
@section('meta_description', 'Join Border Buyers and help make cross-border trade safer and easier.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Careers</h1>
    <p class="mt-4 text-gray-600">We’re not actively hiring right now, but we love meeting talented people. Send your profile to careers@borderbuyers.example.</p>
  </div>
  @include('components.footer')
@endsection