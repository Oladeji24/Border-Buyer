@extends('layouts.app')
@section('title', 'Blog — Border Buyers')
@section('meta_description', 'Insights on cross-border commerce, safety, and logistics from the Border Buyers team.')
@section('content')
  @include('components.navbar')
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900">Blog</h1>
    <p class="mt-4 text-gray-600">Stories and insights from our team. Posts coming soon.</p>
  </div>
  @include('components.footer')
@endsection