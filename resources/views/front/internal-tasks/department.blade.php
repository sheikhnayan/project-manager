<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $department->name }} - Department Management</title>

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

        .remove-member-btn {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .team-member-row:hover .remove-member-btn {
            opacity: 1;
        }

        input {
            border: 1px solid #e5e7eb;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        select {
            border: 1px solid #e5e7eb;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            background: transparent;
        }
    </style>

    <script>
        function toggleSettings(element) {
            try {
                // Try to find the settings menu as the next sibling
                const settingsMenu = element.nextElementSibling;
                
                // Check if the settings menu exists and has the correct class
                if (!settingsMenu || !settingsMenu.classList.contains('settings-menu')) {
                    console.error('Settings menu not found or missing settings-menu class');
                    return;
                }

                // Close all other settings menus first
                document.querySelectorAll('.settings-menu').forEach(menu => {
                    if (menu !== settingsMenu) {
                        menu.classList.add('hidden');
                    }
                });

                // Toggle the current menu
                settingsMenu.classList.toggle('hidden');

                // Handle clicking outside
                const handleClickOutside = (event) => {
                    if (!settingsMenu.contains(event.target) && !element.contains(event.target)) {
                        settingsMenu.classList.add('hidden');
                        // Remove the event listener once the menu is closed
                        document.removeEventListener('click', handleClickOutside);
                    }
                };

                // Add click outside listener
                document.addEventListener('click', handleClickOutside);
            } catch (error) {
                console.error('Error in toggleSettings:', error);
            }
        }

        // Close menus when pressing escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.settings-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="mx-auto px-4">
            <div class="bg-white rounded-lg shadow" style="border: 1px solid #D1D5DB; padding: 16px; margin-bottom: 24px;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('internal-tasks.index') }}" class="text-gray-600 hover:text-black">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-semibold">{{ $department->name }}</h1>
                            <p class="text-gray-600 text-sm mt-1">{{ $department->description ?: 'Department tasks and team management' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="showAddTaskModal()" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900 flex items-center gap-2" style="font-size: 13px; padding: 0.4rem 1rem; height: 34px;">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add Task
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

            <!-- Department Tasks and Team Management -->
            <div x-data="{ 
                showAddTaskModal: false,
                showAssignUserModal: false,
                selectedTask: null,
                init() {
                    console.log('Department management initialized');
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                }
            }" x-cloak class="space-y-6">

                <!-- Tasks Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Department Tasks</h2>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 text-left">Task Name</th>
                                        <th class="py-3 px-4 text-left">Description</th>
                                        <th class="py-3 px-4 text-center">Daily Limit</th>
                                        <th class="py-3 px-4 text-center">Hourly Rate</th>
                                        <th class="py-3 px-4 text-center">Total Hours</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($department->internalTasks as $task)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <div class="font-medium">{{ $task->name }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">
                                            {{ $task->description ? Str::limit($task->description, 50) : '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            {{ $task->max_hours_per_day ? $task->max_hours_per_day . 'h' : 'No limit' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            {{ $task->hourly_rate ? formatCurrency($task->hourly_rate) : '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            {{ number_format($task->timeEntries->sum('hours'), 1) }}h
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $task->is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                                                {{ $task->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex gap-2 relative">
                                                <button onclick="toggleSettings(this)" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100">
                                                    <i data-lucide="more-vertical" class="w-5 h-5"></i>
                                                </button>
                                                <div class="settings-menu hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                    <div class="py-1">
                                                        <a href="{{ route('internal-tasks.edit', $task->id) }}" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                                            Edit
                                                        </a>
                                                        <button onclick="toggleTaskStatus({{ $task->id }}, {{ $task->is_active ? 'false' : 'true' }})" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i data-lucide="{{ $task->is_active ? 'archive' : 'archive-restore' }}" class="w-4 h-4 mr-2"></i>
                                                            {{ $task->is_active ? 'Archive' : 'Restore' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                            No tasks found for this department.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Team Members Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Team Members</h2>
                            <button onclick="toggleUserSelector()" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900 flex items-center gap-2" style="font-size: 13px; padding: 0.4rem 1rem; height: 34px;">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                Add Members
                            </button>
                        </div>
                        
                        <!-- User Selector (hidden by default) -->
                        <div id="userSelector" class="hidden mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-900">Select team members to add:</h3>
                                <button onclick="toggleSelectAll()" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    Select All
                                </button>
                            </div>
                            <div class="max-h-60 overflow-y-auto space-y-2">
                                @foreach (\App\Models\User::where('company_id', auth()->user()->company_id)->where('is_archived', 0)->orderBy('name')->get() as $availableUser)
                                    <label class="flex items-center gap-3 p-2 hover:bg-white rounded cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               value="{{ $availableUser->id }}" 
                                               class="user-checkbox rounded border-gray-300 text-black focus:ring-black"
                                               {{ $department->assignedUsers->contains($availableUser->id) ? 'checked disabled' : '' }}
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
                                        @if($department->assignedUsers->contains($availableUser->id))
                                            <span class="text-xs text-green-600 font-medium">Already added</span>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-4 flex gap-2">
                                <button onclick="addSelectedUsers()" class="bg-black text-white px-4 py-2 rounded text-sm hover:bg-gray-900">
                                    Add Selected
                                </button>
                                <button onclick="toggleUserSelector()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-4">

                            <div class="space-y-2 mt-4" id="team-members">
                                @foreach ($department->assignedUsers as $user)
                                    <div class='team-member-row flex items-center justify-between py-2 px-4 rounded-lg bg-white border'>
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
                                            onclick='removeTeamMember(this, {{ $user->id }})' 
                                            title='Remove team member'>
                                            <i data-lucide='x' class='w-4 h-4'></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" name="team_members" id="teamMembers" value="{{ $department->assignedUsers->pluck('id')->join(',') }}">
                        </div>
                    </div>
                </div>

                <!-- Add Task Modal -->
                <div x-show="showAddTaskModal" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Task</h3>
                                <form id="addTaskForm">
                                    <input type="hidden" name="department" value="{{ $department->name }}">
                                    <div class="mb-4">
                                        <label for="taskName" class="block text-sm font-medium text-gray-700 mb-2">Task Name</label>
                                        <input type="text" id="taskName" name="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="taskDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <textarea id="taskDescription" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="maxHours" class="block text-sm font-medium text-gray-700 mb-2">Max Hours Per Day</label>
                                        <input type="number" id="maxHours" name="max_hours_per_day" min="1" max="24" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="hourlyRate" class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate</label>
                                        <input type="number" id="hourlyRate" name="hourly_rate" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </form>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" onclick="saveTask()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-gray-800 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Save Task
                                </button>
                                <button type="button" @click="showAddTaskModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assign User Modal -->
                <div x-show="showAssignUserModal" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Assign User to Department</h3>
                                <form id="assignUserForm">
                                    <div class="mb-4">
                                        <label for="userSelect" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                                        <select id="userSelect" name="user_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Select a user...</option>
                                            @foreach(\App\Models\User::where('company_id', auth()->user()->company_id)->orderBy('name')->get() as $user)
                                                @unless($department->assignedUsers->contains($user->id))
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                @endunless
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" onclick="assignUser()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Assign User
                                </button>
                                <button type="button" @click="showAssignUserModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
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

        // Show/hide add task modal
        function showAddTaskModal() {
            // Use Alpine.js to trigger the modal
            const alpineData = Alpine.$data(document.querySelector('[x-data]'));
            if (alpineData) {
                alpineData.showAddTaskModal = true;
            }
        }

        // Toggle user selector panel
        function toggleUserSelector() {
            const selector = document.getElementById('userSelector');
            selector.classList.toggle('hidden');
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
                    // Select all
                    if (!selectedUsersToAdd.find(u => u.id === userId)) {
                        selectedUsersToAdd.push({ id: userId, name: userName, email: userEmail });
                    }
                } else {
                    // Deselect all
                    selectedUsersToAdd = selectedUsersToAdd.filter(u => u.id !== userId);
                }
            });
            
            // Update button text
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

        // Add all selected users
        async function addSelectedUsers() {
            if (selectedUsersToAdd.length === 0) {
                alert('Please select at least one user to add');
                return;
            }

            for (const user of selectedUsersToAdd) {
                await addTeamMemberToDepartment(user.id);
            }

            // Reload to show updated list
            location.reload();
        }

        // Add team member to department via API
        async function addTeamMemberToDepartment(userId) {
            try {
                const response = await fetch('/internal-tasks/departments/{{ $department->id }}/assign-user', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                });

                if (!response.ok) {
                    console.error('Failed to add user:', userId);
                }
            } catch (error) {
                console.error('Error adding user:', error);
            }
        }

        // Save new task
        async function saveTask() {
            const form = document.getElementById('addTaskForm');
            const formData = new FormData(form);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                const response = await fetch('{{ route("internal-tasks.store") }}', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    location.reload();
                } else {
                    const result = await response.json();
                    alert('Error: ' + (result.message || 'Failed to save task'));
                }
            } catch (error) {
                console.error('Error saving task:', error);
                alert('Failed to save task');
            }
        }

        // Toggle task status
        async function toggleTaskStatus(taskId, status) {
            try {
                const response = await fetch(`/internal-tasks/${taskId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ is_active: status })
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert('Failed to update task status');
                }
            } catch (error) {
                console.error('Error toggling task status:', error);
                alert('Failed to update task status');
            }
        }

        $('.team').on('change', function(){
            const teamMember = $(this).val();
            const teamMemberName = $(this).find('option:selected').text();

            if (!teamMember) return;

            $(".team option:selected").attr('disabled','disabled');

            // Fetch user data
            $.ajax({
                url: `/api/users/${teamMember}`,
                method: 'GET',
                success: function(user) {
                    addTeamMemberRow(teamMember, user);
                    updateDepartmentUsers();
                },
                error: function() {
                    // Fallback if AJAX fails
                    const fallbackUser = {
                        name: teamMemberName,
                        email: '',
                        profile_image_url: null,
                    };
                    addTeamMemberRow(teamMember, fallbackUser);
                    updateDepartmentUsers();
                }
            });
            
            // Reset the select dropdown
            $(this).val('');
        });

        function addTeamMemberRow(teamMemberId, user) {
            $('#team-members').append(`
                <div class='team-member-row flex items-center justify-between py-2 px-4 rounded-lg bg-white border'>
                    <div class='flex items-center gap-3'>
                        <div class='w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0'>
                            ${user.profile_image_url ? 
                                `<img src='${user.profile_image_url.startsWith('http') ? user.profile_image_url : '/storage/' + user.profile_image_url}' alt='${user.name}' class='w-full h-full object-cover'>` :
                                `<span class="text-sm font-medium text-gray-600">${user.name.substr(0, 2).toUpperCase()}</span>`
                            }
                        </div>
                        <div class='flex flex-col min-w-0 flex-1'>
                            <span class='text-sm font-medium text-gray-900 truncate'>${user.name}</span>
                            <span class='text-xs text-gray-500 truncate'>${user.email || ''}</span>
                        </div>
                    </div>
                    <button type='button' class='remove-member-btn p-2 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors flex-shrink-0' onclick='removeTeamMember(this, ${teamMemberId})' title='Remove team member'>
                        <i data-lucide='x' class='w-4 h-4'></i>
                    </button>
                </div>
            `);

            // Reinitialize Lucide icons for the new elements
            lucide.createIcons();

            // Update hidden input
            let currentMembers = $('#teamMembers').val();
            $('#teamMembers').val(currentMembers + (currentMembers ? ',' : '') + teamMemberId);
        }

        function removeTeamMember(button, teamMemberId) {
            // Re-enable the option in select dropdown
            $(`.team option[value='${teamMemberId}']`).removeAttr('disabled');
            
            // Remove the team member row
            $(button).closest('div').remove();
            
            // Update hidden input
            let currentMembers = $('#teamMembers').val();
            let membersArray = currentMembers.split(',').filter(id => id !== '' && id != teamMemberId);
            $('#teamMembers').val(membersArray.join(','));

            // Update department users on the server
            updateDepartmentUsers();
        }

        async function updateDepartmentUsers() {
            const teamMembers = $('#teamMembers').val();
            
            try {
                const response = await fetch(`/internal-tasks/departments/{{ $department->id }}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: '{{ $department->name }}',
                        description: '{{ $department->description }}',
                        user_ids: JSON.stringify(teamMembers.split(',').filter(id => id !== ''))
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to update department users');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update team members. Please try again.');
            }
        }
    </script>
</body>
</html>