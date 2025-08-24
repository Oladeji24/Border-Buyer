@extends('layouts.app')

@section('title', 'Edit Marketplace Listing')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Marketplace Listing</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('marketplace.update', $marketplaceListing) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $marketplaceListing->title) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $marketplaceListing->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="category" name="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a category</option>
                            <option value="Electronics" {{ old('category', $marketplaceListing->category) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Textiles" {{ old('category', $marketplaceListing->category) == 'Textiles' ? 'selected' : '' }}>Textiles</option>
                            <option value="Agricultural Products" {{ old('category', $marketplaceListing->category) == 'Agricultural Products' ? 'selected' : '' }}>Agricultural Products</option>
                            <option value="Raw Materials" {{ old('category', $marketplaceListing->category) == 'Raw Materials' ? 'selected' : '' }}>Raw Materials</option>
                            <option value="Manufacturing Equipment" {{ old('category', $marketplaceListing->category) == 'Manufacturing Equipment' ? 'selected' : '' }}>Manufacturing Equipment</option>
                            <option value="Fashion Accessories" {{ old('category', $marketplaceListing->category) == 'Fashion Accessories' ? 'selected' : '' }}>Fashion Accessories</option>
                            <option value="Automotive Parts" {{ old('category', $marketplaceListing->category) == 'Automotive Parts' ? 'selected' : '' }}>Automotive Parts</option>
                            <option value="Construction Materials" {{ old('category', $marketplaceListing->category) == 'Construction Materials' ? 'selected' : '' }}>Construction Materials</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (USD) *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $marketplaceListing->price) }}" min="0" step="0.01" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="country_from" class="block text-sm font-medium text-gray-700 mb-1">From Country *</label>
                        <select id="country_from" name="country_from" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a country</option>
                            <option value="China" {{ old('country_from', $marketplaceListing->country_from) == 'China' ? 'selected' : '' }}>China</option>
                            <option value="India" {{ old('country_from', $marketplaceListing->country_from) == 'India' ? 'selected' : '' }}>India</option>
                            <option value="Vietnam" {{ old('country_from', $marketplaceListing->country_from) == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                            <option value="Thailand" {{ old('country_from', $marketplaceListing->country_from) == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                            <option value="Turkey" {{ old('country_from', $marketplaceListing->country_from) == 'Turkey' ? 'selected' : '' }}>Turkey</option>
                        </select>
                        @error('country_from')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country_to" class="block text-sm font-medium text-gray-700 mb-1">To Country *</label>
                        <select id="country_to" name="country_to" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a country</option>
                            <option value="Nigeria" {{ old('country_to', $marketplaceListing->country_to) == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                            <option value="Kenya" {{ old('country_to', $marketplaceListing->country_to) == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                            <option value="Ghana" {{ old('country_to', $marketplaceListing->country_to) == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                            <option value="South Africa" {{ old('country_to', $marketplaceListing->country_to) == 'South Africa' ? 'selected' : '' }}>South Africa</option>
                            <option value="Egypt" {{ old('country_to', $marketplaceListing->country_to) == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                        </select>
                        @error('country_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Assign Agent (Optional)</label>
                        <select id="agent_id" name="agent_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">No agent assigned</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id', $marketplaceListing->agent_id) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }} ({{ $agent->country }})
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active" {{ old('status', $marketplaceListing->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ old('status', $marketplaceListing->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sold" {{ old('status', $marketplaceListing->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expiration Date (Optional)</label>
                        <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at', $marketplaceListing->expires_at ? $marketplaceListing->expires_at->format('Y-m-d') : '') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $marketplaceListing->is_featured) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                            Featured Listing
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('marketplace.show', $marketplaceListing) }}" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-300">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Update Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
