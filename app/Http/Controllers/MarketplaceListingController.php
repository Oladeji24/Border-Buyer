<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Providers\AppServiceProvider;

class MarketplaceListingController extends Controller
{
    /**
     * Display a listing of the marketplace listings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cacheKey = AppServiceProvider::cacheKey(
            AppServiceProvider::CACHE_PREFIX_LISTINGS,
            'active_listings_page_' . request()->get('page', 1)
        );

        $listings = Cache::rememberModel(
            $cacheKey,
            AppServiceProvider::getTtl('listings'),
            function () {
                return MarketplaceListing::with(['seller', 'agent'])
                    ->where('status', 'active')
                    ->where(function($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    })
                    ->latest()
                    ->paginate(12);
            },
            ['listings', 'marketplace']
        );

        return view('marketplace.index', compact('listings'));
    }

    /**
     * Show the form for creating a new marketplace listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', MarketplaceListing::class);

        $agents = User::where('role', 'agent')->get();
        return view('marketplace.create', compact('agents'));
    }

    /**
     * Store a newly created marketplace listing in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', MarketplaceListing::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'country_from' => 'required|string|max:255',
            'country_to' => 'required|string|max:255',
            'agent_id' => 'nullable|exists:users,id',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $validated['seller_id'] = Auth::id();
        $validated['status'] = 'active';

        MarketplaceListing::create($validated);

        return redirect()->route('marketplace.index')
            ->with('success', 'Marketplace listing created successfully!');
    }

    /**
     * Display the specified marketplace listing.
     *
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Http\Response
     */
    public function show(MarketplaceListing $marketplaceListing)
    {
        // Load related data
        $marketplaceListing->load(['seller', 'agent']);

        // Get similar listings with caching
        $similarCacheKey = AppServiceProvider::cacheKey(
            AppServiceProvider::CACHE_PREFIX_LISTINGS,
            "similar_{$marketplaceListing->category}_{$marketplaceListing->id}"
        );

        $similarListings = Cache::rememberModel(
            $similarCacheKey,
            AppServiceProvider::getTtl('listings'),
            function () use ($marketplaceListing) {
                return MarketplaceListing::where('category', $marketplaceListing->category)
                    ->where('id', '!=', $marketplaceListing->id)
                    ->where('status', 'active')
                    ->where(function($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    })
                    ->limit(4)
                    ->get();
            },
            ['listings', 'marketplace']
        );

        return view('marketplace.show', compact('marketplaceListing', 'similarListings'));
    }

    /**
     * Show the form for editing the specified marketplace listing.
     *
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketplaceListing $marketplaceListing)
    {
        $this->authorize('update', $marketplaceListing);

        $agents = User::where('role', 'agent')->get();
        return view('marketplace.edit', compact('marketplaceListing', 'agents'));
    }

    /**
     * Update the specified marketplace listing in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketplaceListing $marketplaceListing)
    {
        $this->authorize('update', $marketplaceListing);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'country_from' => 'required|string|max:255',
            'country_to' => 'required|string|max:255',
            'agent_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,pending,sold',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $marketplaceListing->update($validated);

        return redirect()->route('marketplace.show', $marketplaceListing)
            ->with('success', 'Marketplace listing updated successfully!');
    }

    /**
     * Remove the specified marketplace listing from storage.
     *
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketplaceListing $marketplaceListing)
    {
        $this->authorize('delete', $marketplaceListing);

        $marketplaceListing->delete();

        return redirect()->route('marketplace.index')
            ->with('success', 'Marketplace listing deleted successfully!');
    }

    /**
     * Search marketplace listings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');
        $countryFrom = $request->input('country_from');
        $countryTo = $request->input('country_to');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        // Generate unique cache key based on search parameters
        $searchParams = md5(serialize($request->all()));
        $cacheKey = AppServiceProvider::cacheKey(
            AppServiceProvider::CACHE_PREFIX_LISTINGS,
            "search_{$searchParams}_page_" . request()->get('page', 1)
        );

        $listings = Cache::rememberModel(
            $cacheKey,
            AppServiceProvider::getTtl('listings'),
            function () use ($request, $query, $category, $countryFrom, $countryTo, $minPrice, $maxPrice) {
                $listings = MarketplaceListing::with(['seller', 'agent'])
                    ->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });

                if ($query) {
                    $listings->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    });
                }

                if ($category) {
                    $listings->where('category', $category);
                }

                if ($countryFrom) {
                    $listings->where('country_from', $countryFrom);
                }

                if ($countryTo) {
                    $listings->where('country_to', $countryTo);
                }

                if ($minPrice) {
                    $listings->where('price', '>=', $minPrice);
                }

                if ($maxPrice) {
                    $listings->where('price', '<=', $maxPrice);
                }

                return $listings->latest()->paginate(12);
            },
            ['listings', 'marketplace', 'search']
        );

        return view('marketplace.index', compact('listings'));
    }
}
