@extends('layouts.app')
@section('title', 'Escrow — Border Buyers')
@section('meta_description', 'Border Buyers escrow keeps funds safe until items are delivered and accepted. Learn features, timelines, and protections.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Escrow Service</h1>
    <p class="mt-4 text-gray-600">Funds are held in a secure account and released only when the agreed conditions are met.</p>
    <div class="mt-8 grid gap-6">
      <div class="bg-white border rounded-lg p-6">
        <h2 class="font-semibold">Key features</h2>
        <ul class="mt-3 list-disc list-inside text-gray-600 space-y-1">
          <li>Segregated funds and clear audit trail</li>
          <li>Buyer- and seller-side notifications</li>
          <li>Milestone-based releases (optional)</li>
        </ul>
      </div>
      <div class="bg-white border rounded-lg p-6">
        <h2 class="font-semibold">Timeline</h2>
        <ol class="mt-3 list-decimal list-inside text-gray-600 space-y-1">
          <li>Buyer deposits into escrow</li>
          <li>Seller ships and provides tracking</li>
          <li>Buyer/agent confirms item matches terms</li>
          <li>Funds released to seller</li>
        </ol>
      </div>
    </div>
    <div class="mt-10 flex gap-3">
      <a href="{{ route('support.pricing') }}" class="px-5 py-3 rounded-md bg-green-600 text-white hover:bg-green-700">See pricing</a>
      <a href="{{ route('howitworks') }}" class="px-5 py-3 rounded-md bg-green-100 text-green-700 hover:bg-green-200">How it works</a>
    </div>
  </div>
  @include('components.footer')
@endsection