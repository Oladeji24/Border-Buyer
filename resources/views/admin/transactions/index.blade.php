<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaction Management
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Search and Filter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.transactions') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               placeholder="Search transactions..." 
                               value="{{ request('search') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="disputed" {{ request('status') == 'disputed' ? 'selected' : '' }}>Disputed</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.transactions') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Transaction Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Pending</h3>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($transactions->where('status', 'pending')->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Completed</h3>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($transactions->where('status', 'completed')->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Disputed</h3>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($transactions->where('status', 'disputed')->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($transactions->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transaction ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Buyer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Seller
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ strtoupper(substr($transaction->id, 0, 8)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" 
                                                 src="{{ $transaction->buyer->profile_image ?: asset('images/default-avatar.png') }}" 
                                                 alt="{{ $transaction->buyer->name }}">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->buyer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->buyer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" 
                                                 src="{{ $transaction->seller->profile_image ?: asset('images/default-avatar.png') }}" 
                                                 alt="{{ $transaction->seller->name }}">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->seller->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->seller->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ${{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transaction->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                           ($transaction->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($transaction->status == 'disputed' ? 'bg-red-100 text-red-800' : 
                                           'bg-gray-100 text-gray-800'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($transaction->status == 'disputed')
                                            <a href="{{ route('admin.transactions.resolve', $transaction) }}" 
                                               class="text-orange-600 hover:text-orange-900">
                                                <i class="fas fa-gavel"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.transactions.destroy', $transaction) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
