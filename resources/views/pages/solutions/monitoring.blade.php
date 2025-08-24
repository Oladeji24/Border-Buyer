@extends('layouts.app')
@section('title', 'Monitoring — Border Buyers')
@section('meta_description', 'Real-time transaction monitoring: shipment tracking, alerts, and delivery proofs to keep both parties informed.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Transaction Monitoring</h1>
    <p class="mt-4 text-gray-600">We track key milestones and provide visibility from payment to delivery.</p>
    <div class="mt-8 grid gap-6">
      <div class="bg-white border rounded-lg p-6">
        <h2 class="font-semibold">What we track</h2>
        <ul class="mt-3 list-disc list-inside text-gray-600 space-y-1">
          <li>Payment verification and escrow status</li>
          <li>Shipment creation and live tracking links</li>
          <li>Delivery confirmation and proof-of-delivery</li>
        </ul>
      </div>
      <div class="bg-white border rounded-lg p-6">
        <h2 class="font-semibold">Alerts & exceptions</h2>
        <p class="mt-2 text-gray-600">We surface delays, routing issues, or missing documents early so they can be resolved quickly.</p>
      </div>
    </div>
    <div class="mt-10 flex gap-3">
      <a href="{{ route('services') }}" class="px-5 py-3 rounded-md bg-green-600 text-white hover:bg-green-700">Explore services</a>
      <a href="{{ route('contact') }}" class="px-5 py-3 rounded-md bg-green-100 text-green-700 hover:bg-green-200">Contact us</a>
    </div>
  </div>
  @include('components.footer')
@endsection