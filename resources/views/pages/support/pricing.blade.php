@extends('layouts.app')
@section('title', 'Pricing — Border Buyers')
@section('meta_description', 'Simple, transparent fees for escrow, monitoring, and agent services.')
@section('content')
  @include('components.navbar')
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Pricing</h1>
    <p class="mt-4 text-gray-600">Transparent fees with no hidden charges. Fees shown are indicative and may vary by region and currency.</p>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="border rounded-xl p-6 bg-white">
        <h2 class="text-xl font-semibold">Escrow</h2>
        <p class="mt-2 text-gray-600">From 1.5% per transaction (min $10)</p>
        <ul class="mt-4 text-gray-600 list-disc list-inside space-y-1">
          <li>Funds safekeeping</li>
          <li>Milestone releases</li>
          <li>Basic dispute support</li>
        </ul>
      </div>
      <div class="border rounded-xl p-6 bg-white">
        <h2 class="text-xl font-semibold">Monitoring</h2>
        <p class="mt-2 text-gray-600">$5 flat per shipment</p>
        <ul class="mt-4 text-gray-600 list-disc list-inside space-y-1">
          <li>Live shipment tracking</li>
          <li>Alerts & exceptions</li>
          <li>Delivery proof archive</li>
        </ul>
      </div>
      <div class="border rounded-xl p-6 bg-white">
        <h2 class="text-xl font-semibold">Agent Services</h2>
        <p class="mt-2 text-gray-600">From $25 per inspection</p>
        <ul class="mt-4 text-gray-600 list-disc list-inside space-y-1">
          <li>Photo/video inspection</li>
          <li>Pickup & handover support</li>
          <li>Localized communication</li>
        </ul>
      </div>
    </div>

    <div class="mt-10 flex gap-3">
      <a href="{{ route('services') }}" class="px-5 py-3 rounded-md bg-green-600 text-white hover:bg-green-700">Compare services</a>
      <a href="{{ route('contact') }}" class="px-5 py-3 rounded-md bg-green-100 text-green-700 hover:bg-green-200">Contact sales</a>
    </div>
  </div>
  @include('components.footer')
@endsection