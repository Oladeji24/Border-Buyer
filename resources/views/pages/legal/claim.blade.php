@extends('layouts.app')
@section('title', 'File a Claim — Border Buyers')
@section('meta_description', 'Learn how to file a claim when an item does not match agreed terms, including steps and timelines.')
@section('content')
  @include('components.navbar')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">File a Claim</h1>
    <p class="mt-4 text-gray-600">If your item doesn’t match the agreed terms, you can file a claim while funds are still in escrow.</p>
    <ol class="mt-6 list-decimal list-inside space-y-2 text-gray-600">
      <li>Go to your transaction details and click “Open dispute”.</li>
      <li>Upload evidence (photos, documents, communications).</li>
      <li>Our reviewers assess within 3–5 business days.</li>
      <li>We may request an agent inspection for an impartial report.</li>
    </ol>
    <p class="mt-6 text-gray-600">Questions? <a class="text-green-700 hover:underline" href="{{ route('contact') }}">Contact support</a>.</p>
  </div>
  @include('components.footer')
@endsection