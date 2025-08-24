@extends('layouts.app')
@section('title', 'Partners — Border Buyers')
@section('meta_description', 'Partner with Border Buyers to bring safer cross-border commerce to your customers.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Partners</h1>
    <p class="mt-4 text-gray-600">We work with logistics providers, marketplaces, and payment platforms. Partner with us: partners@borderbuyers.example.</p>
  </div>
  @include('components.footer')
@endsection