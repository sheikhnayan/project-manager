<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Internal Tasks Management - Project Management</title>

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
    
    <!-- Alpine.js x-cloak styling -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <meta name="csrf-                        const result = window.currentSort.direction === 'asc' ? aText.localeCompare(bText) : bText.localeCompare(aText);oken" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Internal Tasks Management</h1>
                    <p class="text-gray-600 mt-2">Manage internal work categories for time tracking</p>
                </div>
                <a href="{{ route('internal-tasks.create') }}" class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Internal Task
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabbed Interface -->
            <div x-data="{ 
                activeTab: 'tasks', 
                taskStatus: 'active',
                init() {
                    console.log('Alpine.js initialized, activeTab:', this.activeTab);
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                }
            }" x-cloak class="mb-6">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'tasks'; console.log('Clicked tasks tab')" :class="{ 'border-black text-black': activeTab === 'tasks', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'tasks' }" class="py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                            <i data-lucide="briefcase" class="w-4 h-4 inline mr-2"></i>
                            Internal Tasks
                        </button>
                        <button @click="activeTab = 'departments'; console.log('Clicked departments tab'); loadDepartments();" :class="{ 'border-black text-black': activeTab === 'departments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'departments' }" class="py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                            <i data-lucide="building" class="w-4 h-4 inline mr-2"></i>
                            Departments
                        </button>
                        <button @click="activeTab = 'categories'; console.log('Clicked categories tab'); loadCategories(); loadDepartments();" :class="{ 'border-black text-black': activeTab === 'categories', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'categories' }" class="py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                            <i data-lucide="tag" class="w-4 h-4 inline mr-2"></i>
                            Categories
                        </button>
                    </nav>
                </div>

                <!-- Tasks Tab -->
                <div x-show="activeTab === 'tasks'" x-cloak class="mt-6">
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <div class="mb-4 flex gap-2">
                                <button @click="taskStatus = 'active'; console.log('Switched to active tasks')" :class="{ 'bg-black text-white': taskStatus === 'active', 'bg-gray-200 text-black': taskStatus !== 'active' }" class="px-4 py-2 rounded">Active Tasks</button>
                                <button @click="taskStatus = 'inactive'; console.log('Switched to inactive tasks')" :class="{ 'bg-black text-white': taskStatus === 'inactive', 'bg-gray-200 text-black': taskStatus !== 'inactive' }" class="px-4 py-2 rounded">Inactive Tasks</button>
                            </div>

                            <div x-show="taskStatus === 'active'" x-cloak class="overflow-x-auto" id="activeTasksTab">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(0)">
                                                Task Name
                                                <span class="sort-indicator" data-column="0">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                                Department
                                                <span class="sort-indicator" data-column="1">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                                Category
                                                <span class="sort-indicator" data-column="2">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(3)">
                                                Daily Limit
                                                <span class="sort-indicator" data-column="3">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(4)">
                                                Hourly Rate
                                                <span class="sort-indicator" data-column="4">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(5)">
                                                Total Hours
                                                <span class="sort-indicator" data-column="5">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($internalTasks as $task)
                                            @if ($task->is_active)
                                            <tr class="border-b">
                                                <td class="py-3 px-4">
                                                    <div class="font-medium">{{ $task->name }}</div>
                                                    @if($task->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->department }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->category }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->max_hours_per_day ? $task->max_hours_per_day . 'h' : 'No limit' }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->hourly_rate ? '$' . number_format($task->hourly_rate, 2) : '-' }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ number_format($task->total_hours, 1) }}h</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                        Active
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('internal-tasks.show', $task->id) }}" class="p-1 hover:bg-gray-100 rounded">
                                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                                        </a>
                                                        <a href="{{ route('internal-tasks.edit', $task->id) }}" class="p-1 hover:bg-gray-100 rounded">
                                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                                        </a>
                                                        <button class="p-1 hover:bg-gray-100 rounded" onclick="toggleTaskStatus({{ $task->id }}, true)">
                                                            <i data-lucide="archive" class="w-4 h-4"></i>
                                                        </button>
                                                        @if(!$task->timeEntries()->exists())
                                                            <form action="{{ route('internal-tasks.destroy', $task->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this internal task?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-1 hover:bg-gray-100 rounded text-red-600">
                                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div x-show="taskStatus === 'inactive'" x-cloak class="overflow-x-auto" id="inactiveTasksTab" style="display: none;">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(0)">
                                                Task Name
                                                <span class="sort-indicator" data-column="0">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                                Department
                                                <span class="sort-indicator" data-column="1">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                                Category
                                                <span class="sort-indicator" data-column="2">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(3)">
                                                Daily Limit
                                                <span class="sort-indicator" data-column="3">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(4)">
                                                Hourly Rate
                                                <span class="sort-indicator" data-column="4">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(5)">
                                                Total Hours
                                                <span class="sort-indicator" data-column="5">▲▼</span>
                                            </th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($internalTasks as $task)
                                            @if (!$task->is_active)
                                            <tr class="border-b">
                                                <td class="py-3 px-4">
                                                    <div class="font-medium">{{ $task->name }}</div>
                                                    @if($task->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->department }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->category }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->max_hours_per_day ? $task->max_hours_per_day . 'h' : 'No limit' }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $task->hourly_rate ? '$' . number_format($task->hourly_rate, 2) : '-' }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ number_format($task->total_hours, 1) }}h</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                                                        Inactive
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('internal-tasks.show', $task->id) }}" class="p-1 hover:bg-gray-100 rounded">
                                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                                        </a>
                                                        <a href="{{ route('internal-tasks.edit', $task->id) }}" class="p-1 hover:bg-gray-100 rounded">
                                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                                        </a>
                                                        <button class="p-1 hover:bg-gray-100 rounded" onclick="toggleTaskStatus({{ $task->id }}, false)">
                                                            <i data-lucide="archive-restore" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Departments Tab -->
        <div x-show="activeTab === 'departments'" x-cloak class="mt-6" style="display: none;">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium">Department Management</h3>
                        <button onclick="showAddDepartmentModal()" class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add Department
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto" id="departmentsTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(0)">
                                    Name
                                    <span class="sort-indicator" data-column="0">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                    Description
                                    <span class="sort-indicator" data-column="1">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                    Tasks Count
                                    <span class="sort-indicator" data-column="2">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(3)">
                                    Status
                                    <span class="sort-indicator" data-column="3">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="departmentsTableBody">
                            <!-- Departments will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Categories Tab -->
        <div x-show="activeTab === 'categories'" x-cloak class="mt-6" style="display: none;">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium">Category Management</h3>
                        <button onclick="showAddCategoryModal()" class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add Category
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto" id="categoriesTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(0)">
                                    Name
                                    <span class="sort-indicator" data-column="0">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                    Department
                                    <span class="sort-indicator" data-column="1">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                    Description
                                    <span class="sort-indicator" data-column="2">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(3)">
                                    Tasks Count
                                    <span class="sort-indicator" data-column="3">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(4)">
                                    Status
                                    <span class="sort-indicator" data-column="4">▲▼</span>
                                </th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="categoriesTableBody">
                            <!-- Categories will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        </div> <!-- Close Alpine.js container -->
        </div>
    </main>

    <!-- Department Modal -->
    <div id="departmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" id="departmentModalTitle">Add Department</h3>
                    <form id="departmentForm">
                        <input type="hidden" id="departmentId" value="">
                        <div class="mb-4">
                            <label for="departmentName" class="block text-sm font-medium text-gray-700 mb-2">Department Name</label>
                            <input type="text" id="departmentName" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="departmentDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="departmentDescription" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="departmentActive" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveDepartment()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-gray-800 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button type="button" onclick="closeDepartmentModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" id="categoryModalTitle">Add Category</h3>
                    <form id="categoryForm">
                        <input type="hidden" id="categoryId" value="">
                        <div class="mb-4">
                            <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                            <input type="text" id="categoryName" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="categoryDepartment" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select id="categoryDepartment" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="categoryDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="categoryDescription" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="categoryActive" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveCategory()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-gray-800 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button type="button" onclick="closeCategoryModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    </div> <!-- End of Alpine.js container -->
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Toggle task status
        function toggleTaskStatus(taskId, isActive) {
            fetch(`/internal-tasks/${taskId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload to update the display
                } else {
                    alert('Failed to update task status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the task status.');
            });
        }

        // Department Management Functions
        let departments = [];
        let categories = [];

        function loadDepartments() {
            console.log('Loading departments...');
            fetch('/api/departments')
                .then(response => {
                    console.log('Load departments response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Departments loaded:', data);
                    console.log('Number of departments:', data.length);
                    departments = data;
                    console.log('Calling renderDepartmentsTable...');
                    renderDepartmentsTable();
                    console.log('Calling populateDepartmentDropdowns...');
                    populateDepartmentDropdowns();
                })
                .catch(error => {
                    console.error('Error loading departments:', error);
                    showNotification('Error loading departments: ' + error.message, 'error');
                });
        }

        function loadCategories() {
            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    categories = data;
                    renderCategoriesTable();
                })
                .catch(error => console.error('Error loading categories:', error));
        }

        function renderDepartmentsTable() {
            console.log('renderDepartmentsTable called, departments array:', departments);
            const tbody = document.getElementById('departmentsTableBody');
            if (!tbody) {
                console.error('departmentsTableBody element not found');
                return;
            }
            console.log('Found tbody element, rendering', departments.length, 'departments');

            tbody.innerHTML = departments.map(dept => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${dept.name}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-500">${dept.description || '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${dept.tasks_count || 0}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${dept.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${dept.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editDepartment(${dept.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <button onclick="deleteDepartment(${dept.id})" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        function renderCategoriesTable() {
            const tbody = document.getElementById('categoriesTableBody');
            if (!tbody) return;

            tbody.innerHTML = categories.map(cat => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${cat.name}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">${cat.department_name || '-'}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-500">${cat.description || '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${cat.tasks_count || 0}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${cat.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${cat.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editCategory(${cat.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <button onclick="deleteCategory(${cat.id})" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        function populateDepartmentDropdowns() {
            console.log('populateDepartmentDropdowns called, departments array:', departments);
            const select = document.getElementById('categoryDepartment');
            if (!select) {
                console.error('categoryDepartment element not found');
                return;
            }
            
            const activeDepts = departments.filter(d => d.is_active);
            console.log('Active departments for dropdown:', activeDepts);
            
            select.innerHTML = '<option value="">Select Department</option>' + 
                activeDepts.map(dept => 
                    `<option value="${dept.id}">${dept.name}</option>`
                ).join('');
            
            console.log('Dropdown populated with', activeDepts.length, 'departments');
        }

        // Department Modal Functions
        function showAddDepartmentModal() {
            document.getElementById('departmentModalTitle').textContent = 'Add Department';
            document.getElementById('departmentForm').reset();
            document.getElementById('departmentId').value = '';
            document.getElementById('departmentActive').checked = true;
            document.getElementById('departmentModal').classList.remove('hidden');
        }

        function closeDepartmentModal() {
            document.getElementById('departmentModal').classList.add('hidden');
        }

        function editDepartment(id) {
            const dept = departments.find(d => d.id === id);
            if (!dept) return;

            document.getElementById('departmentModalTitle').textContent = 'Edit Department';
            document.getElementById('departmentId').value = dept.id;
            document.getElementById('departmentName').value = dept.name;
            document.getElementById('departmentDescription').value = dept.description || '';
            document.getElementById('departmentActive').checked = dept.is_active;
            document.getElementById('departmentModal').classList.remove('hidden');
        }

        function saveDepartment() {
            const id = document.getElementById('departmentId').value;
            const data = {
                name: document.getElementById('departmentName').value,
                description: document.getElementById('departmentDescription').value,
                is_active: document.getElementById('departmentActive').checked
            };

            const url = id ? `/api/departments/${id}` : '/api/departments';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(result => {
                console.log('Response result:', result);
                if (result.success) {
                    closeDepartmentModal();
                    loadDepartments();
                    showNotification('Department saved successfully!', 'success');
                } else {
                    showNotification('Error saving department: ' + (result.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error saving department: ' + error.message, 'error');
            });
        }

        function deleteDepartment(id) {
            if (!confirm('Are you sure you want to delete this department?')) return;

            fetch(`/api/departments/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    loadDepartments();
                    showNotification('Department deleted successfully!', 'success');
                } else {
                    showNotification('Error deleting department: ' + (result.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error deleting department', 'error');
            });
        }

        // Category Modal Functions
        function showAddCategoryModal() {
            document.getElementById('categoryModalTitle').textContent = 'Add Category';
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryActive').checked = true;
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        function editCategory(id) {
            const cat = categories.find(c => c.id === id);
            if (!cat) return;

            document.getElementById('categoryModalTitle').textContent = 'Edit Category';
            document.getElementById('categoryId').value = cat.id;
            document.getElementById('categoryName').value = cat.name;
            document.getElementById('categoryDepartment').value = cat.department_id || '';
            document.getElementById('categoryDescription').value = cat.description || '';
            document.getElementById('categoryActive').checked = cat.is_active;
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function saveCategory() {
            const id = document.getElementById('categoryId').value;
            const data = {
                name: document.getElementById('categoryName').value,
                department_id: document.getElementById('categoryDepartment').value || null,
                description: document.getElementById('categoryDescription').value,
                is_active: document.getElementById('categoryActive').checked
            };

            const url = id ? `/api/categories/${id}` : '/api/categories';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeCategoryModal();
                    loadCategories();
                    showNotification('Category saved successfully!', 'success');
                } else {
                    showNotification('Error saving category: ' + (result.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error saving category', 'error');
            });
        }

        function deleteCategory(id) {
            if (!confirm('Are you sure you want to delete this category?')) return;

            fetch(`/api/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    loadCategories();
                    showNotification('Category deleted successfully!', 'success');
                } else {
                    showNotification('Error deleting category: ' + (result.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error deleting category', 'error');
            });
        }

        // Utility Functions
        function showNotification(message, type) {
            // Simple notification system - you can enhance this
            const className = type === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400';
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${className} px-4 py-3 rounded border z-50`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 5000);
        }

        // Debug function to check user permissions
        function checkUserPermissions() {
            fetch('/debug/user')
                .then(response => response.json())
                .then(data => {
                    console.log('User info:', data);
                    if (data.error) {
                        console.error('User not authenticated:', data.error);
                        showNotification('You need to log in first', 'error');
                    } else if (!data.has_edit_settings) {
                        console.warn('User lacks edit_settings permission');
                        showNotification('You need edit_settings permission to manage departments', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error checking user permissions:', error);
                });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing...');
            
            // Check user permissions first
            checkUserPermissions();
            
            // Initialize currentSort to ensure it's available
            if (typeof currentSort === 'undefined') {
                window.currentSort = { column: -1, direction: 'asc' };
            }
            
            // Check if Alpine.js is loaded
            if (typeof Alpine === 'undefined') {
                console.warn('Alpine.js not loaded');
            } else {
                console.log('Alpine.js is available');
            }
            
            // Load data when switching tabs - replaced with direct calls in Alpine.js click handlers
            // const observer = new MutationObserver(function(mutations) {
            //     mutations.forEach(function(mutation) {
            //         if (mutation.target.textContent === 'Departments' && mutation.target.classList.contains('border-black')) {
            //             loadDepartments();
            //         } else if (mutation.target.textContent === 'Categories' && mutation.target.classList.contains('border-black')) {
            //             loadCategories();
            //             loadDepartments(); // Need departments for dropdown
            //         }
            //     });
            // });

            // Initialize Lucide icons after a short delay to ensure DOM is ready
            setTimeout(function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                    console.log('Lucide icons initialized');
                } else {
                    console.warn('Lucide not available');
                }
                
                // Test loading departments on page load
                console.log('Testing loadDepartments on page load...');
                loadDepartments();
            }, 100);
            
            console.log('Initialization completed');
        });

        // Alpine.js initialization event
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized event');
        });

        // Task tab switching functionality (now handled by Alpine.js)
        function showTasksTab(tab) {
            // This function is no longer needed as Alpine.js handles the tab switching
            console.log('Tab switching now handled by Alpine.js');
        }

        // Table sorting functionality - Initialize globally
        window.currentSort = window.currentSort || { column: -1, direction: 'asc' };

        function sortTable(columnIndex) {
            console.log('=== SORT DEBUG START ===');
            console.log('Sorting column:', columnIndex);
            console.log('Current sort object:', window.currentSort);
            
            // Wait a bit for Alpine.js to finish rendering
            setTimeout(() => {
                // Find which table is currently visible
                const activeTable = document.getElementById('activeTasksTab');
                const inactiveTable = document.getElementById('inactiveTasksTab');
                
                console.log('Found tables:', { activeTable, inactiveTable });
                
                if (!activeTable || !inactiveTable) {
                    console.error('Table elements not found');
                    return;
                }
                
                // Use computed styles to determine visibility
                const activeTableStyle = window.getComputedStyle(activeTable);
                const inactiveTableStyle = window.getComputedStyle(inactiveTable);
                
                console.log('Active table display:', activeTableStyle.display);
                console.log('Inactive table display:', inactiveTableStyle.display);
                
                let currentTable;
                if (activeTableStyle.display !== 'none') {
                    currentTable = activeTable;
                } else if (inactiveTableStyle.display !== 'none') {
                    currentTable = inactiveTable;
                } else {
                    console.log('Both tables hidden, using active as default');
                    currentTable = activeTable;
                }
                
                console.log('Selected table:', currentTable.id);
                
                const tbody = currentTable.querySelector('tbody');
                if (!tbody) {
                    console.error('Table body not found in', currentTable.id);
                    return;
                }
                
                const rows = Array.from(tbody.querySelectorAll('tr'));
                console.log('Found rows in', currentTable.id, ':', rows.length);

                if (rows.length === 0) {
                    console.log('No rows to sort');
                    return;
                }

                // Determine sort direction
                if (window.currentSort.column === columnIndex) {
                    window.currentSort.direction = window.currentSort.direction === 'asc' ? 'desc' : 'asc';
                } else {
                    window.currentSort.column = columnIndex;
                    window.currentSort.direction = 'asc';
                }

                console.log('Sort direction:', window.currentSort.direction);

                // Sort rows
                rows.sort((a, b) => {
                    if (!a.cells[columnIndex] || !b.cells[columnIndex]) {
                        console.error('Missing cell at column', columnIndex);
                        return 0;
                    }
                    
                    const aText = a.cells[columnIndex].textContent.trim();
                    const bText = b.cells[columnIndex].textContent.trim();
                    
                    console.log('Comparing:', aText, 'vs', bText);
                    
                    // Handle numeric columns
                    if (columnIndex === 3 || columnIndex === 4 || columnIndex === 5) { // Daily Limit, Hourly Rate, Total Hours
                        const aNum = parseFloat(aText.replace(/[^0-9.-]/g, '')) || 0;
                        const bNum = parseFloat(bText.replace(/[^0-9.-]/g, '')) || 0;
                        console.log('Numeric comparison:', aNum, 'vs', bNum);
                        return window.currentSort.direction === 'asc' ? aNum - bNum : bNum - aNum;
                    }
                    
                    // Handle text columns
                    const result = currentSort.direction === 'asc' ? aText.localeCompare(bText) : bText.localeCompare(aText);
                    console.log('Text comparison result:', result);
                    return result;
                });

                // Clear tbody and re-append sorted rows
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));

                // Update sort indicators
                updateSortIndicators(currentTable, columnIndex, currentSort.direction);
                
                console.log('Sorting completed successfully');
                console.log('=== SORT DEBUG END ===');
            }, 50); // Small delay to ensure Alpine.js has rendered
        }

        function updateSortIndicators(table, activeColumn, direction) {
            const indicators = table.querySelectorAll('.sort-indicator');
            indicators.forEach((indicator, index) => {
                if (index === activeColumn) {
                    indicator.textContent = direction === 'asc' ? '▲' : '▼';
                } else {
                    indicator.textContent = '▲▼';
                }
            });
        }

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