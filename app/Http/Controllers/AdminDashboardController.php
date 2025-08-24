<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AgentProfile;
use App\Models\MarketplaceListing;
use App\Models\ServiceRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'agents' => AgentProfile::where('verification_status', 'verified')->count(),
            'pending_agents' => AgentProfile::where('verification_status', 'pending')->count(),
            'transactions' => Transaction::count(),
            'marketplace_listings' => MarketplaceListing::count(),
            'service_requests' => ServiceRequest::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Display a listing of agents.
     *
     * @return \Illuminate\Http\Response
     */
    public function agents()
    {
        $agents = AgentProfile::with('user')->where('verification_status', 'verified')->latest()->paginate(20);
        return view('admin.agents', compact('agents'));
    }

    /**
     * Display a listing of services.
     *
     * @return \Illuminate\Http\Response
     */
    public function services()
    {
        $services = ServiceRequest::latest()->paginate(20);
        return view('admin.services', compact('services'));
    }

    /**
     * Display a listing of transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions()
    {
        $transactions = Transaction::with(['buyer', 'seller'])->latest()->paginate(20);
        return view('admin.transactions', compact('transactions'));
    }

    /**
     * Display a listing of disputes.
     *
     * @return \Illuminate\Http\Response
     */
    public function disputes()
    {
        // Assuming you have a Dispute model and a way to track them.
        // This is a placeholder implementation.
        $disputes = []; // Replace with your actual dispute retrieval logic
        return view('admin.disputes', compact('disputes'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:user,agent,admin',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}