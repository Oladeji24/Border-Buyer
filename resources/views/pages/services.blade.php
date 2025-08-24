@extends('layouts.app')

@section('title', 'Services — Border Buyers')
@section('meta_title', 'Border Buyers Services — Escrow, Monitoring, Verified Agents')
@section('meta_description', 'Explore Border Buyers services including secure escrow, real-time monitoring, and verified local agents for safe cross-border purchases.')

@section('content')
  @include('components.navbar')

  <section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <h1 class="text-4xl font-extrabold text-gray-900">Our Services</h1>
      <p class="mt-4 text-lg text-gray-600 max-w-3xl">We help buyers and sellers complete cross-border transactions safely. From holding funds in escrow to monitoring delivery, our platform builds trust every step of the way.</p>

      <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Escrow Service -->
        <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
          <div class="flex items-center">
            <span class="inline-flex items-center justify-center p-3 bg-green-600 rounded-md shadow-lg">
              <i class="fa-solid fa-shield-halved text-white"></i>
            </span>
            <h2 class="ml-4 text-xl font-semibold text-gray-900">Escrow Service</h2>
          </div>
          <p class="mt-4 text-gray-600">We hold the buyer's funds in a secure, segregated account until the seller fulfills agreed conditions (e.g., item delivered and verified). This protects both parties from fraud and misunderstandings.</p>
          <ul class="mt-4 space-y-2 text-gray-600 list-disc list-inside">
            <li>Funds safekeeping until confirmation</li>
            <li>Dispute protection with clear milestones</li>
            <li>Transparent timelines and notifications</li>
          </ul>
          <a href="{{ route('solutions.escrow') }}" class="mt-6 inline-block text-green-700 hover:text-green-800 font-medium">Learn more →</a>
        </div>

        <!-- Monitoring -->
        <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
          <div class="flex items-center">
            <span class="inline-flex items-center justify-center p-3 bg-green-600 rounded-md shadow-lg">
              <i class="fa-solid fa-chart-line text-white"></i>
            </span>
            <h2 class="ml-4 text-xl font-semibold text-gray-900">Transaction Monitoring</h2>
          </div>
          <p class="mt-4 text-gray-600">We track transaction progress from payment to delivery, providing updates and flagging anomalies. You always know where things stand.</p>
          <ul class="mt-4 space-y-2 text-gray-600 list-disc list-inside">
            <li>Real-time status changes</li>
            <li>Delivery tracking and proof-of-delivery</li>
            <li>Alerts for delays or exceptions</li>
          </ul>
          <a href="{{ route('solutions.monitoring') }}" class="mt-6 inline-block text-green-700 hover:text-green-800 font-medium">Learn more →</a>
        </div>

        <!-- Verified Agents -->
        <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
          <div class="flex items-center">
            <span class="inline-flex items-center justify-center p-3 bg-green-600 rounded-md shadow-lg">
              <i class="fa-solid fa-user-check text-white"></i>
            </span>
            <h2 class="ml-4 text-xl font-semibold text-gray-900">Verified Local Agents</h2>
          </div>
          <p class="mt-4 text-gray-600">Work with vetted agents who can inspect goods, help with local communication, and ensure what you ordered matches what you receive.</p>
          <ul class="mt-4 space-y-2 text-gray-600 list-disc list-inside">
            <li>Identity and background checks</li>
            <li>Photo/video inspection reports</li>
            <li>On-site pickup facilitation</li>
          </ul>
          <a href="{{ route('agents.index') }}" class="mt-6 inline-block text-green-700 hover:text-green-800 font-medium">Browse agents →</a>
        </div>
      </div>

      <!-- Why Border Buyers -->
      <div class="mt-16 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white border rounded-xl p-6">
          <h3 class="text-lg font-semibold">Secure by design</h3>
          <p class="mt-2 text-gray-600">Multi-step verification, escrow safeguards, and audit trails protect your funds and reputation.</p>
        </div>
        <div class="bg-white border rounded-xl p-6">
          <h3 class="text-lg font-semibold">Transparent pricing</h3>
          <p class="mt-2 text-gray-600">Clear fees with no surprises. See <a class="text-green-700 hover:underline" href="{{ route('support.pricing') }}">pricing</a> for details.</p>
        </div>
        <div class="bg-white border rounded-xl p-6">
          <h3 class="text-lg font-semibold">Global reach</h3>
          <p class="mt-2 text-gray-600">Operate confidently across borders with local expertise and standardized workflows.</p>
        </div>
      </div>

      <div class="mt-16 flex flex-wrap items-center gap-3">
        <a href="{{ route('howitworks') }}" class="inline-flex items-center px-5 py-3 rounded-md bg-green-600 text-white hover:bg-green-700">See how it works</a>
        <a href="{{ route('contact') }}" class="inline-flex items-center px-5 py-3 rounded-md bg-green-100 text-green-700 hover:bg-green-200">Contact sales</a>
      </div>
    </div>
  </section>

  @include('components.footer')
@endsection