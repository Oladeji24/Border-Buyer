@extends('layouts.app')
@section('title', 'Terms of Service — Border Buyers')
@section('meta_description', 'Terms governing the use of Border Buyers escrow and monitoring services.')
@section('content')
  @include('components.navbar')
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 prose prose-green">
    <h1>Terms of Service</h1>
    <p>By using Border Buyers, you agree to these terms.</p>
    <h2>Accounts</h2>
    <p>You are responsible for maintaining the confidentiality of your account credentials.</p>
    <h2>Transactions</h2>
    <p>Escrow funds are held until conditions are met. Disputes are resolved based on evidence provided by both parties.</p>
    <h2>Prohibited activities</h2>
    <p>No illegal items, money laundering, or activities that violate applicable laws.</p>
  </div>
  @include('components.footer')
@endsection