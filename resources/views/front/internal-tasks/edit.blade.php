<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Internal Task - Project Management</title>

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
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Edit Internal Task</h1>
                    <p class="text-gray-600 mt-2">Update internal task settings</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('internal-tasks.show', $internalTask) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        View Task
                    </a>
                    <a href="{{ route('internal-tasks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Back to Tasks
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <form action="{{ route('internal-tasks.update', $internalTask) }}" method="POST" id="internal-task-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Task Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Task Name *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $internalTask->name) }}"
                                   class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                   placeholder="Enter task name"
                                   required>
                        </div>

                        <!-- Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                            <select name="department" 
                                    id="department" 
                                    class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                    required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->name }}" {{ old('department', $internalTask->department) == $department->name ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" 
                                    id="category" 
                                    class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('category', $internalTask->category) == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hourly Rate -->
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="hourly_rate" 
                                       id="hourly_rate" 
                                       value="{{ old('hourly_rate', $internalTask->hourly_rate) }}"
                                       step="0.01"
                                       min="0"
                                       max="9999.99"
                                       class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black pl-7"
                                       placeholder="0.00">
                            </div>
                        </div>

                        <!-- Max Hours Per Day -->
                        <div>
                            <label for="max_hours_per_day" class="block text-sm font-medium text-gray-700 mb-2">Max Hours Per Day</label>
                            <input type="number" 
                                   name="max_hours_per_day" 
                                   id="max_hours_per_day" 
                                   value="{{ old('max_hours_per_day', $internalTask->max_hours_per_day) }}"
                                   min="1"
                                   max="24"
                                   class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                   placeholder="8">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                      placeholder="Enter task description">{{ old('description', $internalTask->description) }}</textarea>
                        </div>

                        <!-- Settings -->
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="requires_approval" 
                                           id="requires_approval" 
                                           value="1"
                                           {{ old('requires_approval', $internalTask->requires_approval) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="requires_approval" class="ml-2 text-sm text-gray-700">Requires approval</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           id="is_active" 
                                           value="1"
                                           {{ old('is_active', $internalTask->is_active) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                        <a href="{{ route('internal-tasks.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-black text-white rounded-md hover:bg-gray-800">
                            Update Task
                        </button>
                    </div>
                </form>
            </div>

            <!-- Task Statistics -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold mb-4">Task Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="text-sm text-gray-600">Total Time Entries</div>
                        <div class="text-2xl font-bold">{{ $internalTask->timeEntries()->count() }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="text-sm text-gray-600">Total Hours</div>
                        <div class="text-2xl font-bold">{{ number_format($internalTask->timeEntries()->sum('hours'), 1) }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="text-sm text-gray-600">Created</div>
                        <div class="text-sm font-medium">{{ $internalTask->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">by {{ $internalTask->creator->name ?? 'System' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Initialize Lucide icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // Settings dropdown functionality (for nav bar)
        function toggleSettings(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('settingsDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('settingsDropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>