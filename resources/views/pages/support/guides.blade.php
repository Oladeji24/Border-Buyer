@extends('layouts.app')
@section('title', 'Guides — Border Buyers')
@section('meta_description', 'Step-by-step guides and best practices for buyers and sellers using Border Buyers.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Guides</h1>
    <ul class="mt-6 space-y-4">
      <li class="p-5 border rounded-lg bg-white">
        <h2 class="font-semibold">Buyer checklist</h2>
        <p class="text-gray-600">Define clear specs, keep communication on-platform, and use verified agents for inspections.</p>
      </li>
      <li class="p-5 border rounded-lg bg-white">
        <h2 class="font-semibold">Seller best practices</h2>
        <p class="text-gray-600">Provide accurate descriptions, ship promptly, and upload proof-of-shipment and delivery.</p>
      </li>
      <li class="p-5 border rounded-lg bg-white">
        <h2 class="font-semibold">Handling disputes</h2>
        <p class="text-gray-600">Document everything, respond quickly, and collaborate with the reviewer for fair resolution.</p>
      </li>
    </ul>
  </div>
  @include('components.footer')
@endsection