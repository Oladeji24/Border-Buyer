<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agent Management
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Search and Filter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.agents') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               placeholder="Search agents by name or specialization..." 
                               value="{{ request('search') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.agents') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Agents Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Agent
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Specialization
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Experience
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rating
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($agents as $agent)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" 
                                                 src="{{ $agent->user->profile_image ?: asset('images/default-avatar.png') }}" 
                                                 alt="{{ $agent->user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $agent->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $agent->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $agent->specialization }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $agent->experience_years }} years
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-900">{{ number_format($agent->rating, 1) }}/5</div>
                                        <div class="ml-1 text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $agent->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $agent->verification_status == 'verified' ? 'bg-green-100 text-green-800' : 
                                           ($agent->verification_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($agent->verification_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $agent->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('agents.show', $agent->user->id) }}" 
                                           class="text-blue-600 hover:text-blue-900" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.agents.download-document', [$agent->id, 'id']) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-id-card"></i>
                                        </a>
                                        <a href="{{ route('admin.agents.download-document', [$agent->id, 'business']) }}" 
                                           class="text-purple-600 hover:text-purple-900">
                                            <i class="fas fa-file-contract"></i>
                                        </a>
                                        @if($agent->verification_status === 'pending')
                                            <a href="{{ route('admin.agents.pending') }}" 
                                               class="text-orange-600 hover:text-orange-900">
                                                <i class="fas fa-gavel"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No agents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4">
                {{ $agents->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
