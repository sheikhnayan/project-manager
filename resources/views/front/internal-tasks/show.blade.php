<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $internalTask->name }} - Internal Task Details</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold">{{ $internalTask->name }}</h1>
                    <p class="text-gray-600 mt-2">Internal Task Details</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('internal-tasks.edit', $internalTask) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit Task
                    </a>
                    <a href="{{ route('internal-tasks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Back to Tasks
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Task Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Task Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Task Name</label>
                                <p class="text-gray-900">{{ $internalTask->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $internalTask->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $internalTask->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <p class="text-gray-900">{{ $internalTask->department }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <p class="text-gray-900">{{ $internalTask->category }}</p>
                            </div>

                            @if($internalTask->hourly_rate)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate</label>
                                    <p class="text-gray-900">${{ number_format($internalTask->hourly_rate, 2) }}</p>
                                </div>
                            @endif

                            @if($internalTask->max_hours_per_day)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Hours Per Day</label>
                                    <p class="text-gray-900">{{ $internalTask->max_hours_per_day }} hours</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Requires Approval</label>
                                <p class="text-gray-900">{{ $internalTask->requires_approval ? 'Yes' : 'No' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                                <p class="text-gray-900">{{ $internalTask->created_at->format('M d, Y') }}</p>
                                @if($internalTask->creator)
                                    <p class="text-sm text-gray-500">by {{ $internalTask->creator->name }}</p>
                                @endif
                            </div>
                        </div>

                        @if($internalTask->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $internalTask->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Time Entries -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold mb-4">Recent Time Entries</h2>
                        
                        @if($internalTask->timeEntries->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($internalTask->timeEntries->take(10) as $entry)
                                            <tr>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $entry->user->name }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $entry->date->format('M d, Y') }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ number_format($entry->hours, 2) }}h</div>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900">{{ Str::limit($entry->notes, 50) }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($internalTask->timeEntries->count() > 10)
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500">Showing latest 10 entries of {{ $internalTask->timeEntries->count() }} total</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <i data-lucide="clock" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                <p class="text-gray-500">No time entries found for this task</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold mb-4">Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Hours</span>
                                <span class="text-lg font-semibold">{{ number_format($internalTask->timeEntries->sum('hours'), 1) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Time Entries</span>
                                <span class="text-lg font-semibold">{{ $internalTask->timeEntries->count() }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Active Users</span>
                                <span class="text-lg font-semibold">{{ $internalTask->timeEntries->pluck('user_id')->unique()->count() }}</span>
                            </div>

                            @if($internalTask->hourly_rate)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Estimated Cost</span>
                                    <span class="text-lg font-semibold">${{ number_format($internalTask->timeEntries->sum('hours') * $internalTask->hourly_rate, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Company Info -->
                    @if($internalTask->company)
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="text-lg font-semibold mb-4">Company</h3>
                            <div class="space-y-2">
                                <p class="font-medium">{{ $internalTask->company->name }}</p>
                                <p class="text-sm text-gray-600">Task scope limited to this company</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="text-lg font-semibold mb-4">Scope</h3>
                            <p class="text-sm text-gray-600">Global task - Available to all companies</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold mb-4">Actions</h3>
                        <div class="space-y-3">
                            <button onclick="toggleTaskStatus({{ $internalTask->id }})" 
                                    class="w-full px-4 py-2 text-sm {{ $internalTask->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-md">
                                {{ $internalTask->is_active ? 'Deactivate Task' : 'Activate Task' }}
                            </button>
                            
                            <form action="{{ route('internal-tasks.destroy', $internalTask) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-4 py-2 text-sm bg-red-100 text-red-700 hover:bg-red-200 rounded-md">
                                    Delete Task
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Settings dropdown functionality
        function toggleSettings(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('settingsDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('settingsDropdown');
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    </script>

    <!-- Initialize Lucide icons -->
    <script>

        function toggleTaskStatus(taskId) {
            if (!confirm('Are you sure you want to change the task status?')) {
                return;
            }

            fetch(`/internal-tasks/${taskId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update task status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the task status');
            });
        }
    </script>
</body>
</html>