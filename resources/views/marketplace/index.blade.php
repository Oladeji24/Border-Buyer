@extends('layouts.app')

@section('title', 'Marketplace Listings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Marketplace Listings</h1>
        <div class="flex space-x-4">
            <form action="{{ route('marketplace.search') }}" method="GET" class="flex">
                <input type="text" name="query" placeholder="Search listings..." class="px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition duration-300">
                    Search
                </button>
            </form>
            @auth
                @if (auth()->user()->role === 'seller' || auth()->user()->role === 'admin')
                    <a href="{{ route('marketplace.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                        Create Listing
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Filter Listings</h2>
        <form action="{{ route('marketplace.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Textiles">Textiles</option>
                    <option value="Agricultural Products">Agricultural Products</option>
                    <option value="Raw Materials">Raw Materials</option>
                    <option value="Manufacturing Equipment">Manufacturing Equipment</option>
                    <option value="Fashion Accessories">Fashion Accessories</option>
                    <option value="Automotive Parts">Automotive Parts</option>
                    <option value="Construction Materials">Construction Materials</option>
                </select>
            </div>

            <div>
                <label for="country_from" class="block text-sm font-medium text-gray-700 mb-1">From Country</label>
                <select name="country_from" id="country_from" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Countries</option>
                    <option value="China">China</option>
                    <option value="India">India</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Turkey">Turkey</option>
                </select>
            </div>

            <div>
                <label for="country_to" class="block text-sm font-medium text-gray-700 mb-1">To Country</label>
                <select name="country_to" id="country_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Countries</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Ghana">Ghana</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Egypt">Egypt</option>
                </select>
            </div>

            <div class="flex space-x-4">
                <div class="w-1/2">
                    <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                    <input type="number" name="min_price" id="min_price" placeholder="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="w-1/2">
                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                    <input type="number" name="max_price" id="max_price" placeholder="10000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="md:col-span-2 lg:col-span-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    Apply Filters
                </button>
                <a href="{{ route('marketplace.index') }}" class="ml-2 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition duration-300">
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Listings Grid -->
    @if($listings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($listings as $listing)
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

        <div class="mt-8">
            {{ $listings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No marketplace listings found</h3>
            <p class="text-gray-600 mb-4">Try adjusting your filters or search terms</p>
            <a href="{{ route('marketplace.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                View All Listings
            </a>
        </div>
    @endif
</div>
@endsection
