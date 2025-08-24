@extends('layouts.app')
@section('title', 'About — Border Buyers')
@section('meta_description', 'Learn about Border Buyers: our mission to make cross-border trade safer for everyone.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">About Border Buyers</h1>
    <p class="mt-4 text-gray-600">We are on a mission to make cross-border trade safe, simple, and transparent. Our team combines experience in payments, logistics, and risk to protect buyers and sellers worldwide.</p>
    <div class="mt-8 grid md:grid-cols-2 gap-6">
      <div class="bg-white border rounded-lg p-6">
        <h2 class="font-semibold">What we believe</h2>
        <p class="mt-2 text-gray-600">Trust is built by process and transparency. We design workflows that reduce uncertainty and align incentives.</p>
      </div>
      <div class="bg-white border rounded-lg p-6">
        <h2 class="font-semibold">Where we operate</h2>
        <p class="mt-2 text-gray-600">Global coverage with a growing network of verified agents in key markets.</p>
      </div>
    </div>
  </div>
  @include('components.footer')
@endsection