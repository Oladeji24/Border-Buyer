<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Service Management
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Search and Filter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.services') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               placeholder="Search services..." 
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
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.services') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Services Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Service
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Agent
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Budget
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
                        @forelse($services as $service)
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $service->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" 
                                                 src="{{ $service->user->profile_image ?: asset('images/default-avatar.png') }}" 
                                                 alt="{{ $service->user->name }}">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $service->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $service->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($service->agent)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full" 
                                                     src="{{ $service->agent->user->profile_image ?: asset('images/default-avatar.png') }}" 
                                                     alt="{{ $service->agent->user->name }}">
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $service->agent->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $service->agent->specialization }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $service->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($service->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                           ($service->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($service->budget, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $service->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.services.show', $service) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this service?')">
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
                                    No services found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
