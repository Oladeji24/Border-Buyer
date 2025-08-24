@extends('layouts.app')

@section('title', $marketplaceListing->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-start mb-6">
                <div>
                    @if($marketplaceListing->is_featured)
                        <span class="bg-yellow-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded mb-2 inline-block">
                            FEATURED
                        </span>
                    @endif

                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $marketplaceListing->title }}</h1>

                    <div class="flex items-center mb-4">
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-2.5 py-0.5 rounded mr-2">
                            {{ $marketplaceListing->category }}
                        </span>
                        <span class="text-gray-600">
                            {{ $marketplaceListing->country_from }} → {{ $marketplaceListing->country_to }}
                        </span>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-3xl font-bold text-gray-900 mb-2">${{ number_format($marketplaceListing->price, 2) }}</div>
                    <div class="text-sm text-gray-500">
                        Listed on {{ $marketplaceListing->created_at->format('M d, Y') }}
                        @if($marketplaceListing->expires_at)
                            • Expires on {{ $marketplaceListing->expires_at->format('M d, Y') }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                <p class="text-gray-600 whitespace-pre-line">{{ $marketplaceListing->description }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Seller Information</h3>
                    <div class="flex items-center mb-3">
                        <img src="{{ $marketplaceListing->seller->profile_image ?: 'https://ui-avatars.com/api/?name=' . urlencode($marketplaceListing->seller->name) }}" alt="{{ $marketplaceListing->seller->name }}" class="w-12 h-12 rounded-full mr-3">
                        <div>
                            <div class="font-medium text-gray-800">{{ $marketplaceListing->seller->name }}</div>
                            <div class="text-sm text-gray-600">{{ $marketplaceListing->seller->country }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div class="mb-1">Email: {{ $marketplaceListing->seller->email }}</div>
                        <div>Phone: {{ $marketplaceListing->seller->phone }}</div>
                    </div>
                </div>

                @if($marketplaceListing->agent)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Assigned Agent</h3>
                        <div class="flex items-center mb-3">
                            <img src="{{ $marketplaceListing->agent->profile_image ?: 'https://ui-avatars.com/api/?name=' . urlencode($marketplaceListing->agent->name) }}" alt="{{ $marketplaceListing->agent->name }}" class="w-12 h-12 rounded-full mr-3">
                            <div>
                                <div class="font-medium text-gray-800">{{ $marketplaceListing->agent->name }}</div>
                                <div class="text-sm text-gray-600">{{ $marketplaceListing->agent->country }}</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <div class="mb-1">Email: {{ $marketplaceListing->agent->email }}</div>
                            <div>Phone: {{ $marketplaceListing->agent->phone }}</div>
                            @if($marketplaceListing->agent->agentProfile)
                                <div class="mt-2">
                                    <div class="font-medium">Specialization: {{ $marketplaceListing->agent->agentProfile->specialization }}</div>
                                    <div>Experience: {{ $marketplaceListing->agent->agentProfile->experience_years }} years</div>
                                    <div class="flex items-center mt-1">
                                        <span class="text-yellow-500 mr-1">★</span>
                                        <span>{{ $marketplaceListing->agent->agentProfile->rating }}/5</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $marketplaceListing->agent->agentProfile->completed_transactions }} transactions</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap gap-3">
                @if(auth()->check() && auth()->user()->role === 'buyer')
                    <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        Contact Seller
                    </button>
                    <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300">
                        Make an Offer
                    </button>
                @endif

                @if(auth()->check() && (auth()->user()->id === $marketplaceListing->seller_id || auth()->user()->role === 'admin'))
                    <a href="{{ route('marketplace.edit', $marketplaceListing) }}" class="bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition duration-300">
                        Edit Listing
                    </a>

                    <form action="{{ route('marketplace.destroy', $marketplaceListing) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300" onclick="return confirm('Are you sure you want to delete this listing?')">
                            Delete Listing
                        </button>
                    </form>
                @endif

                <a href="{{ route('marketplace.index') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-300">
                    Back to Listings
                </a>
            </div>
        </div>
    </div>

    <!-- Similar Listings Section -->
    @if($similarListings->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Similar Listings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($similarListings as $listing)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        @if($listing->is_featured)
                            <div class="bg-yellow-500 text-white text-center py-1 text-sm font-semibold">
                                FEATURED
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $listing->title }}</h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $listing->category }}
                                </span>
                            </div>

                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $listing->description }}</p>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl font-bold text-gray-900">${{ number_format($listing->price, 2) }}</span>
                                <span class="text-sm text-gray-500">
                                    {{ $listing->country_from }} → {{ $listing->country_to }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <img src="{{ $listing->seller->profile_image ?: 'https://ui-avatars.com/api/?name=' . urlencode($listing->seller->name) }}" alt="{{ $listing->seller->name }}" class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm text-gray-600">{{ $listing->seller->name }}</span>
                                </div>

                                <a href="{{ route('marketplace.show', $listing) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
