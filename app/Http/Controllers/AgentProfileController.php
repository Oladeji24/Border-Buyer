<?php

namespace App\Http\Controllers;

use App\Models\AgentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Providers\AppServiceProvider;

class AgentProfileController extends Controller
{
    /**
     * Display a listing of agent profiles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cacheKey = AppServiceProvider::cacheKey(
            AppServiceProvider::CACHE_PREFIX_AGENTS,
            'verified_agents_page_' . request()->get('page', 1)
        );

        $agents = Cache::rememberModel(
            $cacheKey,
            AppServiceProvider::getTtl('agents'),
            function () {
                return AgentProfile::with(['user'])
                    ->where('verification_status', 'verified')
                    ->orderBy('rating', 'desc')
                    ->paginate(12);
            },
            ['agents', 'profiles']
        );

        return view('agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new agent profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', AgentProfile::class);

        // Check if user already has an agent profile
        if (Auth::user()->agentProfile) {
            return redirect()->route('agent.profile.show')
                ->with('info', 'You already have an agent profile.');
        }

        return view('agents.create');
    }

    /**
     * Store a newly created agent profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', AgentProfile::class);

        $validated = $request->validate([
            'bio' => 'required|string|min:50',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:1',
            'languages' => 'required|array|min:1',
            'languages.*' => 'string|max:50',
            'id_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'business_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle file uploads
        $idDocumentPath = $request->file('id_document')->store('agent_documents', 'public');
        $businessDocumentPath = $request->file('business_document')->store('agent_documents', 'public');

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            // Update user profile image
            Auth::user()->update(['profile_image' => $profileImagePath]);
        }

        // Create agent profile
        $agentProfile = AgentProfile::create([
            'user_id' => Auth::id(),
            'bio' => $validated['bio'],
            'specialization' => $validated['specialization'],
            'experience_years' => $validated['experience_years'],
            'languages' => json_encode($validated['languages']),
            'verification_status' => 'pending', // Pending admin approval
            'rating' => 0,
            'completed_transactions' => 0,
            'id_document_path' => $idDocumentPath,
            'business_document_path' => $businessDocumentPath,
        ]);

        return redirect()->route('agent.profile.show')
            ->with('success', 'Your agent profile has been submitted for review. You will receive a notification once it is approved.');
    }

    /**
     * Display the specified agent profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        // If no ID provided, show current user's profile
        if (!$id) {
            $id = Auth::id();
            $this->authorize('view', AgentProfile::class);
        }

        $agent = User::with(['agentProfile', 'reviews'])
            ->where('role', 'agent')
            ->findOrFail($id);

        // Get agent's marketplace listings
        $marketplaceListings = $agent->marketplaceListings()
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->take(4)
            ->get();

        return view('agents.show', compact('agent', 'marketplaceListings'));
    }

    /**
     * Show the form for editing the specified agent profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $this->authorize('update', AgentProfile::class);

        $agentProfile = Auth::user()->agentProfile;

        if (!$agentProfile) {
            return redirect()->route('agent.profile.create')
                ->with('info', 'Please create your agent profile first.');
        }

        return view('agents.edit', compact('agentProfile'));
    }

    /**
     * Update the specified agent profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('update', AgentProfile::class);

        $agentProfile = Auth::user()->agentProfile;

        if (!$agentProfile) {
            return redirect()->route('agent.profile.create')
                ->with('info', 'Please create your agent profile first.');
        }

        $validated = $request->validate([
            'bio' => 'required|string|min:50',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:1',
            'languages' => 'required|array|min:1',
            'languages.*' => 'string|max:50',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            // Update user profile image
            Auth::user()->update(['profile_image' => $profileImagePath]);
        }

        // Update agent profile
        $agentProfile->update([
            'bio' => $validated['bio'],
            'specialization' => $validated['specialization'],
            'experience_years' => $validated['experience_years'],
            'languages' => json_encode($validated['languages']),
        ]);

        return redirect()->route('agent.profile.show')
            ->with('success', 'Your agent profile has been updated successfully.');
    }

    /**
     * Admin approval for agent profiles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $this->authorize('approve', AgentProfile::class);

        $agentProfile = AgentProfile::findOrFail($id);

        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'rejection_reason' => 'required_if:verification_status,rejected|nullable|string|max:500',
        ]);

        $agentProfile->update([
            'verification_status' => $validated['verification_status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        $status = $validated['verification_status'] === 'verified' ? 'approved' : 'rejected';

        return redirect()->route('admin.agents.pending')
            ->with('success', "Agent profile has been {$status}.");
    }

    /**
     * Display pending agent profiles for admin review.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $this->authorize('viewAny', AgentProfile::class);

        $pendingAgents = AgentProfile::with(['user'])
            ->where('verification_status', 'pending')
            ->latest()
            ->get();

        return view('admin.agents.pending', compact('pendingAgents'));
    }

    /**
     * Display the agent directory with search and filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function directory(Request $request)
    {
        $query = $request->input('query');
        $specialization = $request->input('specialization');
        $country = $request->input('country');
        $minRating = $request->input('min_rating');

        // Generate unique cache key based on search parameters
        $searchParams = md5(serialize($request->all()));
        $cacheKey = AppServiceProvider::cacheKey(
            AppServiceProvider::CACHE_PREFIX_AGENTS,
            "directory_{$searchParams}_page_" . request()->get('page', 1)
        );

        $agents = Cache::rememberModel(
            $cacheKey,
            AppServiceProvider::getTtl('agents'),
            function () use ($request, $query, $specialization, $country, $minRating) {
                $agents = AgentProfile::with(['user'])
                    ->where('verification_status', 'verified');

                if ($query) {
                    $agents->where(function($q) use ($query) {
                        $q->where('bio', 'like', "%{$query}%")
                          ->orWhere('specialization', 'like', "%{$query}%")
                          ->orWhereHas('user', function($userQuery) use ($query) {
                              $userQuery->where('name', 'like', "%{$query}%");
                          });
                    });
                }

                if ($specialization) {
                    $agents->where('specialization', $specialization);
                }

                if ($country) {
                    $agents->whereHas('user', function($userQuery) use ($country) {
                        $userQuery->where('country', $country);
                    });
                }

                if ($minRating) {
                    $agents->where('rating', '>=', $minRating);
                }

                return $agents->orderBy('rating', 'desc')->paginate(12);
            },
            ['agents', 'profiles', 'directory']
        );

        // Get available specializations for filter dropdown (cached separately)
        $specializationsCacheKey = AppServiceProvider::cacheKey(
            AppServiceProvider::CACHE_PREFIX_AGENTS,
            'specializations_list'
        );

        $specializations = Cache::rememberModel(
            $specializationsCacheKey,
            AppServiceProvider::getTtl('agents'),
            function () {
                return AgentProfile::where('verification_status', 'verified')
                    ->distinct()
                    ->pluck('specialization');
            },
            ['agents', 'profiles']
        );

        // Get available countries for filter dropdown (cached separately)
        $countriesCacheKey = AppServiceProvider::cacheKey(
            AppServiceProvider::CACHE_PREFIX_AGENTS,
            'countries_list'
        );

        $countries = Cache::rememberModel(
            $countriesCacheKey,
            AppServiceProvider::getTtl('agents'),
            function () {
                return User::where('role', 'agent')
                    ->whereHas('agentProfile', function($query) {
                        $query->where('verification_status', 'verified');
                    })
                    ->distinct()
                    ->pluck('country');
            },
            ['agents', 'profiles']
        );

        return view('agents.directory', compact('agents', 'specializations', 'countries'));
    }

    /**
     * Download agent document for admin review.
     *
     * @param  int  $id
     * @param  string  $documentType
     * @return \Illuminate\Http\Response
     */
    public function downloadDocument($id, $documentType)
    {
        $this->authorize('viewAny', AgentProfile::class);

        $agentProfile = AgentProfile::findOrFail($id);

        $documentPath = null;
        if ($documentType === 'id') {
            $documentPath = $agentProfile->id_document_path;
        } elseif ($documentType === 'business') {
            $documentPath = $agentProfile->business_document_path;
        }

        if (!$documentPath || !Storage::disk('public')->exists($documentPath)) {
            return back()->with('error', 'Document not found.');
        }

        return Storage::disk('public')->download($documentPath);
    }
}