@extends('layouts.app')

@section('title', 'How It Works — Border Buyers')
@section('meta_title', 'How Border Buyers Works — Step-by-Step Escrow & Monitoring')
@section('meta_description', 'Understand the Border Buyers process: set terms, pay into escrow, ship, verify, and release funds. Learn buyer and seller flows and dispute handling.')

@section('content')
  @include('components.navbar')

  <section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <h1 class="text-4xl font-extrabold text-gray-900">How it works</h1>
      <p class="mt-4 text-lg text-gray-600 max-w-3xl">A simple, transparent flow that protects buyers and sellers.</p>

      <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div>
          <h2 class="text-2xl font-semibold">Step-by-step</h2>
          <ol class="mt-4 space-y-6">
            <li class="flex items-start">
              <span class="h-8 w-8 rounded-full bg-green-600 text-white flex items-center justify-center mr-3">1</span>
              <div>
                <h3 class="font-medium">Agree on terms</h3>
                <p class="text-gray-600">Buyer and seller set item description, price, delivery window, and any inspection requirements.</p>
              </div>
            </li>
            <li class="flex items-start">
              <span class="h-8 w-8 rounded-full bg-green-600 text-white flex items-center justify-center mr-3">2</span>
              <div>
                <h3 class="font-medium">Pay into escrow</h3>
                <p class="text-gray-600">Buyer deposits funds into escrow. We verify funds and notify the seller to proceed.</p>
              </div>
            </li>
            <li class="flex items-start">
              <span class="h-8 w-8 rounded-full bg-green-600 text-white flex items-center justify-center mr-3">3</span>
              <div>
                <h3 class="font-medium">Ship and monitor</h3>
                <p class="text-gray-600">Seller ships the item. We track shipment and monitor milestones, providing updates to both parties.</p>
              </div>
            </li>
            <li class="flex items-start">
              <span class="h-8 w-8 rounded-full bg-green-600 text-white flex items-center justify-center mr-3">4</span>
              <div>
                <h3 class="font-medium">Inspect and accept</h3>
                <p class="text-gray-600">Buyer (or a verified agent) inspects the item. If it matches the agreed terms, buyer confirms acceptance.</p>
              </div>
            </li>
            <li class="flex items-start">
              <span class="h-8 w-8 rounded-full bg-green-600 text-white flex items-center justify-center mr-3">5</span>
              <div>
                <h3 class="font-medium">Release funds</h3>
                <p class="text-gray-600">Upon acceptance, we release funds to the seller. If there’s an issue, a dispute can be opened.</p>
              </div>
            </li>
          </ol>
        </div>
        <div>
          <h2 class="text-2xl font-semibold">Buyer & Seller flows</h2>
          <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border rounded-lg p-5 bg-gray-50">
              <h3 class="font-semibold">Buyer</h3>
              <ul class="mt-2 text-gray-600 space-y-2 list-disc list-inside">
                <li>Set terms, pay into escrow</li>
                <li>Track shipment and updates</li>
                <li>Inspect on arrival (optionally via agent)</li>
                <li>Approve or raise a dispute</li>
              </ul>
            </div>
            <div class="border rounded-lg p-5 bg-gray-50">
              <h3 class="font-semibold">Seller</h3>
              <ul class="mt-2 text-gray-600 space-y-2 list-disc list-inside">
                <li>Confirm order and ship</li>
                <li>Provide tracking & documentation</li>
                <li>Respond to issues promptly</li>
                <li>Receive funds after buyer approval</li>
              </ul>
            </div>
          </div>

          <div class="mt-8">
            <h3 class="text-xl font-semibold">Disputes & protection</h3>
            <p class="mt-2 text-gray-600">If the item doesn’t match the agreed terms, the buyer can open a dispute. Our team reviews evidence from both sides and may request an agent inspection before resolving fairly.</p>
            <a href="{{ url('/disputes') }}" class="mt-3 inline-block text-green-700 hover:text-green-800">Learn about disputes →</a>
          </div>
        </div>
      </div>

      <div class="mt-16 flex flex-wrap items-center gap-3">
        <a href="{{ route('services') }}" class="inline-flex items-center px-5 py-3 rounded-md bg-green-600 text-white hover:bg-green-700">Explore services</a>
        <a href="{{ route('contact') }}" class="inline-flex items-center px-5 py-3 rounded-md bg-green-100 text-green-700 hover:bg-green-200">Talk to us</a>
      </div>
    </div>
  </section>

  @include('components.footer')
@endsection