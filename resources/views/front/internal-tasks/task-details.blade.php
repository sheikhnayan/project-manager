<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $task->name }} - Task Details</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>
    
    <style>
        [x-cloak] { display: none !important; }

        /* Team member row styles */
        .team-member-row {
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
        }

        .team-member-row:hover {
            background-color: #f9fafb !important;
            border-color: #d1d5db;
        }

        input, select, textarea {
            border: 1px solid #e5e7eb;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="mx-auto px-4">
            <div class="bg-white rounded-lg shadow" style="border: 1px solid #D1D5DB; padding: 16px; margin-bottom: 24px;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="/internal-tasks/departments/{{ $task->department }}" class="text-gray-600 hover:text-black">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-semibold">Task Details & Assignment</h1>
                            <p class="text-gray-600 text-sm mt-1">Manage task information and assign users</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="saveTask()" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900 flex items-center gap-2" style="font-size: 13px; padding: 0.4rem 1rem; height: 34px;">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
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

            <form id="taskForm" method="POST" action="{{ route('internal-tasks.update', $task->id) }}">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Task Information Section -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Task Information</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Task Name</label>
                                        <input type="text" id="name" name="name" value="{{ $task->name }}" required
                                               class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <textarea id="description" name="description" rows="3"
                                                  class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $task->description }}</textarea>
                                    </div>

                                    {{-- <div>
                                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                        <select id="department_id" name="department_id" required
                                                class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Department</option>
                                            @foreach(\App\Models\Department::where('company_id', auth()->user()->company_id)->where('is_active', true)->orderBy('name')->get() as $dept)
                                                <option value="{{ $dept->id }}" {{ $task->department_id == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div>
                                        <label for="max_hours_per_day" class="block text-sm font-medium text-gray-700 mb-2">Max Hours Per Day</label>
                                        <input type="number" id="max_hours_per_day" name="max_hours_per_day" min="1" max="24" value="{{ $task->max_hours_per_day }}"
                                               class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <br>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="requires_approval" name="requires_approval" value="1" {{ $task->requires_approval ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="requires_approval" class="ml-2 text-sm text-gray-700">Requires Approval</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $task->is_active ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Users Section -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-semibold text-gray-900">Assigned Users</h2>
                                    <button type="button" onclick="toggleUserSelector()" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900 flex items-center gap-2" style="font-size: 13px; padding: 0.4rem 1rem; height: 34px;">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                        Add Users
                                    </button>
                                </div>
                                
                                <!-- User Selector (hidden by default) -->
                                <div id="userSelector" class="hidden mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-sm font-medium text-gray-900">Select users to assign to this task:</h3>
                                        <button style="color:#000;" type="button" onclick="toggleSelectAll()" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            Select All
                                        </button>
                                    </div>
                                    <div class="max-h-60 overflow-y-auto space-y-2">
                                        @foreach (\App\Models\User::where('company_id', auth()->user()->company_id)->where('is_archived', 0)->orderBy('name')->get() as $availableUser)
                                            <label class="flex items-center gap-3 p-2 hover:bg-white rounded cursor-pointer transition-colors">
                                                <input type="checkbox" 
                                                       value="{{ $availableUser->id }}" 
                                                       class="user-checkbox rounded border-gray-300 text-black focus:ring-black"
                                                       {{ $task->assignedUsers->contains($availableUser->id) ? 'checked disabled' : '' }}
                                                       onchange="toggleUserSelection({{ $availableUser->id }}, '{{ addslashes($availableUser->name) }}', '{{ addslashes($availableUser->email) }}')">
                                                <div class='w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0'>
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ strtoupper(substr($availableUser->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div class='flex flex-col min-w-0 flex-1'>
                                                    <span class='text-sm font-medium text-gray-900'>{{ $availableUser->name }}</span>
                                                    <span class='text-xs text-gray-500'>{{ $availableUser->email }}</span>
                                                </div>
                                                @if($task->assignedUsers->contains($availableUser->id))
                                                    <span class="text-xs text-green-600 font-medium">Already assigned</span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 flex gap-2">
                                        <button type="button" onclick="addSelectedUsers()" class="bg-black text-white px-4 py-2 rounded text-sm hover:bg-gray-900">
                                            Add Selected
                                        </button>
                                        <button type="button" onclick="toggleUserSelector()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="space-y-2" id="assigned-users">
                                    @foreach ($task->assignedUsers as $user)
                                        <div class='team-member-row flex items-center justify-between py-2 px-4 rounded-lg bg-white border' data-user-id='{{ $user->id }}'>
                                            <div class='flex items-center gap-3'>
                                                <div class='w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0'>
                                                    <span class="text-sm font-medium text-gray-600">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div class='flex flex-col min-w-0 flex-1'>
                                                    <span class='text-sm font-medium text-gray-900 truncate'>{{ $user->name }}</span>
                                                    <span class='text-xs text-gray-500 truncate'>{{ $user->email }}</span>
                                                </div>
                                            </div>
                                            <button type='button' 
                                                class='remove-member-btn p-2 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors flex-shrink-0' 
                                                onclick='removeAssignedUser(this, {{ $user->id }})' 
                                                title='Remove user'>
                                                <i data-lucide='x' class='w-4 h-4'></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                <input type="hidden" name="assigned_users" id="assignedUsers" value="{{ $task->assignedUsers->pluck('id')->join(',') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Sidebar -->
                    <div class="space-y-6">
                        <!-- Quick Stats -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Hours</span>
                                        <span class="text-lg font-semibold">{{ number_format($task->timeEntries->sum('hours'), 1) }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Time Entries</span>
                                        <span class="text-lg font-semibold">{{ $task->timeEntries->count() }}</span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Assigned Users</span>
                                        <span class="text-lg font-semibold">{{ $task->assignedUsers->count() }}</span>
                                    </div>

                                    @if($task->hourly_rate)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Estimated Cost</span>
                                            <span class="text-lg font-semibold">{{ formatCurrency($task->timeEntries->sum('hours') * $task->hourly_rate) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Task Metadata -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Task Information</h3>
                                <div class="space-y-3 text-sm">
                                    <div>
                                        <span class="text-gray-600">Created:</span>
                                        <span class="text-gray-900 font-medium">{{ $task->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($task->creator)
                                        <div>
                                            <span class="text-gray-600">Created by:</span>
                                            <span class="text-gray-900 font-medium">{{ $task->creator->name }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-gray-600">Last updated:</span>
                                        <span class="text-gray-900 font-medium">{{ $task->updated_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Initialize on page load
        $(document).ready(function() {
            // Disable checkboxes for already assigned users
            $('#assigned-users .team-member-row').each(function() {
                const userId = $(this).data('user-id');
                if (userId) {
                    $(`.user-checkbox[value='${userId}']`).prop('disabled', true);
                }
            });

            // Initialize the hidden input with current assigned users
            updateAssignedUsers();
        });

        // Toggle user selector panel
        function toggleUserSelector() {
            const selector = document.getElementById('userSelector');
            selector.classList.toggle('hidden');
            lucide.createIcons();
        }

        // Track selected users for batch add
        let selectedUsersToAdd = [];

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.user-checkbox:not(:disabled)');
            const selectAllBtn = event.target;
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                const userId = parseInt(checkbox.value);
                const label = checkbox.closest('label');
                const userName = label.querySelector('.text-sm.font-medium').textContent;
                const userEmail = label.querySelector('.text-xs.text-gray-500').textContent;
                
                if (!allChecked) {
                    if (!selectedUsersToAdd.find(u => u.id === userId)) {
                        selectedUsersToAdd.push({ id: userId, name: userName, email: userEmail });
                    }
                } else {
                    selectedUsersToAdd = selectedUsersToAdd.filter(u => u.id !== userId);
                }
            });
            
            selectAllBtn.textContent = allChecked ? 'Select All' : 'Deselect All';
        }

        function toggleUserSelection(userId, userName, userEmail) {
            const checkbox = event.target;
            if (checkbox.checked && !checkbox.disabled) {
                selectedUsersToAdd.push({ id: userId, name: userName, email: userEmail });
            } else {
                selectedUsersToAdd = selectedUsersToAdd.filter(u => u.id !== userId);
            }
        }

        function addSelectedUsers() {
            if (selectedUsersToAdd.length === 0) {
                alert('Please select at least one user to assign');
                return;
            }

            console.log('Adding users:', selectedUsersToAdd);

            selectedUsersToAdd.forEach(user => {
                addAssignedUserRow(user.id, user);
            });

            selectedUsersToAdd = [];
            toggleUserSelector();
            updateAssignedUsers();
            
            // Uncheck all checkboxes
            document.querySelectorAll('.user-checkbox:not(:disabled)').forEach(cb => cb.checked = false);
        }

        function addAssignedUserRow(userId, user) {
            // Check if user already exists
            const existingUser = $(`#assigned-users .team-member-row button[onclick*="${userId}"]`).length;
            if (existingUser > 0) {
                return; // User already assigned, skip
            }

            $('#assigned-users').append(`
                <div class='team-member-row flex items-center justify-between py-2 px-4 rounded-lg bg-white border' data-user-id='${userId}'>
                    <div class='flex items-center gap-3'>
                        <div class='w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0'>
                            <span class="text-sm font-medium text-gray-600">${user.name.substr(0, 2).toUpperCase()}</span>
                        </div>
                        <div class='flex flex-col min-w-0 flex-1'>
                            <span class='text-sm font-medium text-gray-900 truncate'>${user.name}</span>
                            <span class='text-xs text-gray-500 truncate'>${user.email || ''}</span>
                        </div>
                    </div>
                    <button type='button' class='remove-member-btn p-2 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors flex-shrink-0' onclick='removeAssignedUser(this, ${userId})' title='Remove user'>
                        <i data-lucide='x' class='w-4 h-4'></i>
                    </button>
                </div>
            `);

            // Disable checkbox in selector
            $(`.user-checkbox[value='${userId}']`).prop('disabled', true);

            lucide.createIcons();
        }

        function removeAssignedUser(button, userId) {
            console.log('Removing user:', userId);
            
            // Re-enable the option in checkbox
            $(`.user-checkbox[value='${userId}']`).removeAttr('disabled').prop('checked', false);
            $(button).closest('div.team-member-row').remove();
            updateAssignedUsers();
        }

        function updateAssignedUsers() {
            let assignedUsers = [];
            $('#assigned-users .team-member-row').each(function() {
                // Get user ID from data attribute or onclick attribute
                let userId = $(this).data('user-id');
                if (!userId) {
                    // Fallback: Extract from the remove button's onclick attribute
                    const onclickAttr = $(this).find('.remove-member-btn').attr('onclick');
                    const match = onclickAttr ? onclickAttr.match(/removeAssignedUser\(this,\s*(\d+)\)/) : null;
                    if (match) {
                        userId = match[1];
                    }
                }
                if (userId) {
                    assignedUsers.push(userId);
                }
            });
            $('#assignedUsers').val(assignedUsers.join(','));
            console.log('Updated assigned users:', assignedUsers.join(','));
        }

        function saveTask() {
            // Update the assigned users one more time before submission
            updateAssignedUsers();
            
            // Log the value being submitted
            const assignedUsersValue = $('#assignedUsers').val();
            console.log('Submitting form with assigned_users:', assignedUsersValue);
            
            // Check if the hidden input is in the form
            const hiddenInput = document.querySelector('input[name="assigned_users"]');
            console.log('Hidden input element:', hiddenInput);
            console.log('Hidden input value:', hiddenInput ? hiddenInput.value : 'NOT FOUND');
            
            document.getElementById('taskForm').submit();
        }

        // Settings dropdown functionality
        function toggleSettings(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('settingsDropdown');
            dropdown.classList.toggle('show');
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
