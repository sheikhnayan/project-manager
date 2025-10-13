<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Internal Task - Project Management</title>

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
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('internal-tasks.index') }}" class="hover:text-gray-700">Internal Tasks</a>
                    <span>/</span>
                    <span class="text-gray-900">Create New Task</span>
                </div>
                <h1 class="text-3xl font-bold">Create Internal Task</h1>
                <p class="text-gray-600 mt-2">Add a new internal work category for time tracking</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow">
                <form action="{{ route('internal-tasks.store') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Task Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Task Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   required 
                                   class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                   placeholder="e.g., Team Meetings, Code Review">
                        </div>

                        <!-- Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select name="department" 
                                    id="department" 
                                    required 
                                    class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->name }}" {{ old('department') == $department->name ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category" 
                                    id="category" 
                                    required 
                                    class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hourly Rate -->
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Hourly Rate (Optional)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="hourly_rate" 
                                       id="hourly_rate" 
                                       value="{{ old('hourly_rate') }}"
                                       step="0.01"
                                       min="0"
                                       max="9999.99"
                                       class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black pl-7"
                                       placeholder="0.00">
                            </div>
                        </div>

                        <!-- Max Hours Per Day -->
                        <div>
                            <label for="max_hours_per_day" class="block text-sm font-medium text-gray-700 mb-2">
                                Daily Hour Limit (Optional)
                            </label>
                            <input type="number" 
                                   name="max_hours_per_day" 
                                   id="max_hours_per_day" 
                                   value="{{ old('max_hours_per_day') }}"
                                   min="1"
                                   max="24"
                                   class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                   placeholder="No limit">
                            <p class="mt-1 text-sm text-gray-500">Maximum hours per day for this task</p>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                      placeholder="Describe what this internal task is for...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Settings -->
                        <div class="md:col-span-2">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="requires_approval" 
                                           id="requires_approval" 
                                           value="1"
                                           {{ old('requires_approval') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-black focus:border-black focus:ring-black">
                                    <label for="requires_approval" class="ml-2 block text-sm text-gray-700">
                                        Requires approval before time can be logged
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           id="is_active" 
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-black focus:border-black focus:ring-black">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                        Active (employees can log time to this task)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                        <a href="{{ route('internal-tasks.index') }}" 
                           class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-black text-white px-6 py-2 rounded-md hover:bg-gray-800">
                            Create Internal Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

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