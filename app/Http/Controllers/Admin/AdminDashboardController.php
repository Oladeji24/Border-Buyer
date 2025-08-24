<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgentProfile;
use App\Models\ServiceRequest;
use App\Models\MarketplaceListing;
use App\Models\Transaction;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_agents' => AgentProfile::where('verification_status', 'verified')->count(),
            'pending_agents' => AgentProfile::where('verification_status', 'pending')->count(),
            'total_services' => ServiceRequest::count(),
            'active_listings' => MarketplaceListing::where('status', 'active')->count(),
            'total_transactions' => Transaction::count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'pending_disputes' => 0, // Will implement disputes later
        ];

        // Recent activities
        $recent_activities = [
            'latest_users' => User::latest()->take(5)->get(),
            'latest_agents' => AgentProfile::with('user')->latest()->take(5)->get(),
            'recent_transactions' => Transaction::with(['buyer', 'seller'])->latest()->take(5)->get(),
        ];

        // Monthly statistics for charts
        $monthly_stats = $this->getMonthlyStats();

        return view('admin.dashboard', compact('stats', 'recent_activities', 'monthly_stats'));
    }

    /**
     * Get monthly statistics for charts
     *
     * @return array
     */
    private function getMonthlyStats()
    {
        $months = collect();
        $userGrowth = collect();
        $transactionGrowth = collect();
        $revenueGrowth = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $months->push($date->format('M Y'));
            
            $userGrowth->push(User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count());
            $transactionGrowth->push(Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count());
            $revenueGrowth->push(Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('status', 'completed')
                ->sum('amount'));
        }

        return [
            'months' => $months,
            'user_growth' => $userGrowth,
            'transaction_growth' => $transactionGrowth,
            'revenue_growth' => $revenueGrowth,
        ];
    }

    /**
     * Display user management interface
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        $users = User::with(['agentProfile'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display service management interface
     *
     * @return \Illuminate\Http\Response
     */
    public function services()
    {
        $services = ServiceRequest::with(['user', 'agent'])
            ->latest()
            ->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Display transaction management interface
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions()
    {
        $transactions = Transaction::with(['buyer', 'seller', 'service'])
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display agent management interface
     *
     * @return \Illuminate\Http\Response
     */
    public function agents()
    {
        $agents = AgentProfile::with(['user'])
            ->latest()
            ->paginate(20);

        return view('admin.agents.index', compact('agents'));
    }
}
