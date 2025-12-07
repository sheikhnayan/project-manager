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

    <!-- jQuery -->
    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>

    <style>
        /* User selector styles */
        .user-selector-panel {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .user-checkbox:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .assigned-user-row {
            transition: background-color 0.2s;
        }
        
        .assigned-user-row:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="mx-auto px-4">
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
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select name="department_id" 
                                    id="department_id" 
                                    required 
                                    class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ (old('department_id') ?? ($selectedDepartment == $department->name ? $department->id : '')) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
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

                        <!-- Assigned Users Section -->
                        <div class="md:col-span-2">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-base font-semibold text-gray-900">Assign Users (Optional)</h3>
                                <button type="button" onclick="toggleUserSelector()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                                    <span>Add Users</span>
                                </button>
                            </div>
                            
                            <!-- User Selector Panel (hidden by default) -->
                            <div id="userSelector" class="hidden mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-medium text-gray-700">Select Users</h4>
                                    <button style="color:#000;" type="button" onclick="toggleSelectAll()" class="text-xs text-blue-600 hover:text-blue-800">
                                        Select All
                                    </button>
                                </div>
                                
                                <div class="user-selector-panel space-y-2">
                                    @php
                                        $user = auth()->user();
                                        if ($user->role_id == 8) {
                                            $companyUsers = \App\Models\User::all();
                                        } else {
                                            $companyUsers = \App\Models\User::where('company_id', $user->company_id)->get();
                                        }
                                    @endphp
                                    
                                    @foreach($companyUsers as $companyUser)
                                        <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer">
                                            <input type="checkbox" 
                                                   class="user-checkbox rounded border-gray-300 text-black focus:ring-black"
                                                   value="{{ $companyUser->id }}"
                                                   data-user-name="{{ $companyUser->name }}"
                                                   data-user-email="{{ $companyUser->email }}"
                                                   onchange="toggleUserSelection({{ $companyUser->id }}, '{{ addslashes($companyUser->name) }}', '{{ $companyUser->email }}')">
                                            <div class="ml-3 flex items-center space-x-2">
                                                <div class="w-8 h-8 rounded-full bg-gray-700 text-white flex items-center justify-center text-xs font-medium">
                                                    {{ strtoupper(substr($companyUser->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $companyUser->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $companyUser->email }}</div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                
                                <div class="mt-3 flex justify-end space-x-2">
                                    <button type="button" onclick="toggleUserSelector()" class="text-sm text-gray-600 hover:text-gray-800 px-3 py-1">
                                        Cancel
                                    </button>
                                    <button type="button" onclick="addSelectedUsers()" class="text-sm bg-black text-white px-4 py-1 rounded hover:bg-gray-800">
                                        Add Selected
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Display Assigned Users -->
                            <div id="assignedUsersList" class="space-y-2">
                                <!-- Assigned users will be displayed here -->
                            </div>
                            
                            <!-- Hidden input to store assigned user IDs -->
                            <input type="hidden" name="assigned_users" id="assignedUsers" value="">
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

        // User assignment functionality
        let selectedUsers = {};
        let assignedUsers = {};

        function toggleUserSelector() {
            const selector = document.getElementById('userSelector');
            selector.classList.toggle('hidden');
            if (!selector.classList.contains('hidden')) {
                lucide.createIcons();
            }
        }

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.user-checkbox:not(:disabled)');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                const userId = parseInt(checkbox.value);
                const userName = checkbox.dataset.userName;
                const userEmail = checkbox.dataset.userEmail;
                
                if (checkbox.checked && !assignedUsers[userId]) {
                    selectedUsers[userId] = { name: userName, email: userEmail };
                } else if (!checkbox.checked) {
                    delete selectedUsers[userId];
                }
            });
        }

        function toggleUserSelection(userId, userName, userEmail) {
            const checkbox = document.querySelector(`.user-checkbox[value="${userId}"]`);
            
            if (checkbox.checked && !assignedUsers[userId]) {
                selectedUsers[userId] = { name: userName, email: userEmail };
            } else {
                delete selectedUsers[userId];
            }
        }

        function addSelectedUsers() {
            Object.keys(selectedUsers).forEach(userId => {
                if (!assignedUsers[userId]) {
                    assignedUsers[userId] = selectedUsers[userId];
                    addAssignedUserRow(userId, selectedUsers[userId]);
                }
            });
            
            selectedUsers = {};
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
            toggleUserSelector();
            updateAssignedUsers();
        }

        function addAssignedUserRow(userId, user) {
            const listContainer = document.getElementById('assignedUsersList');
            
            const userRow = document.createElement('div');
            userRow.className = 'assigned-user-row flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg';
            userRow.dataset.userId = userId;
            
            userRow.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-gray-700 text-white flex items-center justify-center text-xs font-medium">
                        ${user.name.substring(0, 2).toUpperCase()}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                        <div class="text-xs text-gray-500">${user.email}</div>
                    </div>
                </div>
                <button type="button" 
                        onclick="removeAssignedUser(this, ${userId})" 
                        class="remove-user-btn text-red-600 hover:text-red-800 p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;
            
            listContainer.appendChild(userRow);
            lucide.createIcons();
            
            // Disable checkbox in selector
            const checkbox = document.querySelector(`.user-checkbox[value="${userId}"]`);
            if (checkbox) checkbox.disabled = true;
        }

        function removeAssignedUser(button, userId) {
            delete assignedUsers[userId];
            button.closest('.assigned-user-row').remove();
            
            // Re-enable checkbox in selector
            const checkbox = document.querySelector(`.user-checkbox[value="${userId}"]`);
            if (checkbox) checkbox.disabled = false;
            
            updateAssignedUsers();
        }

        function updateAssignedUsers() {
            const userIds = Object.keys(assignedUsers).join(',');
            document.getElementById('assignedUsers').value = userIds;
        }
    </script>
</body>
</html>