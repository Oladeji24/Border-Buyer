@extends('layouts.app')
@section('title', 'Privacy Policy — Border Buyers')
@section('meta_description', 'Privacy policy describing how Border Buyers collects, uses, and protects your data.')
@section('content')
  @include('components.navbar')
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 prose prose-green">
    <h1>Privacy Policy</h1>
    <p>We respect your privacy. This policy explains what we collect, why we collect it, and how we keep it safe.</p>
    <h2>Information we collect</h2>
    <ul>
      <li>Account data (name, email)</li>
      <li>Transaction data (amounts, participants)</li>
      <li>Log data (IP, device, usage)</li>
    </ul>
    <h2>How we use information</h2>
    <ul>
      <li>Provide escrow and monitoring services</li>
      <li>Detect fraud and keep the platform secure</li>
      <li>Communicate updates about your transactions</li>
    </ul>
    <h2>Your rights</h2>
    <p>You can request access, correction, or deletion of your data as permitted by law.</p>
  </div>
  @include('components.footer')
@endsection