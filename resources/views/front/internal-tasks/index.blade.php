<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Internal Tasks Management - Project Management</title>

    <!-- Fonts: Use Inter for consistency, fallback to system -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>
    <style>
        html, body, .font-sans {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
        }
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600 !important;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
        }
        .font-bold, .font-semibold, .font-medium {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
        }
        .settings-menu {
            font-size: 13px !important;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
        }
        .btn, .bg-black, .text-white, .hover\:bg-gray-900 {
            font-size: 13px !important;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
            font-weight: 500 !important;
        }
        th, td, label, .text-xs, .text-sm, .text-lg, .text-gray-900, .text-gray-700, .text-gray-600 {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
        }
    </style>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6 font-sans text-gray-900">
        <div class="mx-auto px-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold" style="font-size: 20px">Internal Tasks Management</h2>
                    <p class="text-gray-600 mt-2">Manage departments for internal task tracking</p>
                </div>
                <a href="{{ route('internal-tasks.create') }}" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900 flex items-center gap-2 shadow-sm" style="font-size: 13px;">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Internal Task
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Departments Management -->
            <div x-data="departmentsManager()" class="space-y-6">
                <!-- Departments Table -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">Departments</h2>
                            <button @click="showAddModal = true" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900 flex items-center gap-2 shadow-sm" style="font-size: 13px;">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Department
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider" style="font-size: 0.875rem !important;">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider" style="font-size: 0.875rem !important;">Description</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider" style="font-size: 0.875rem !important;">Tasks</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider" style="font-size: 0.875rem !important;">Users</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider" style="font-size: 0.875rem !important;">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 tracking-wider" style="font-size: 0.875rem !important;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-if="loading">
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center">
                                            <div class="flex justify-center items-center">
                                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                                                <span class="ml-2 text-gray-500">Loading departments...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-if="!loading && departments.length === 0">
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            No departments found. Add a department to get started.
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-for="dept in departments" :key="dept.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-2 whitespace-nowrap">
                                            <button @click="viewDepartment(dept.id)" class="text-black hover:text-blue-700 font-medium">
                                                <span x-text="dept.name"></span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-2">
                                            <span x-text="dept.description || '-'" class="text-gray-600"></span>
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <span x-text="dept.tasks_count || 0" class="text-gray-900"></span>
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <span x-text="dept.assigned_users_count || 0" class="text-gray-900"></span>
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap">
                                            <span :class="dept.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border border-gray-200">
                                                <span x-text="dept.is_active ? 'Active' : 'Inactive'"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-2 whitespace-nowrap text-center relative">
                                            <button onclick="toggleSettings(event)" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-black rounded-full hover:bg-gray-100 focus:outline-none settings-menu-trigger" type="button">
                                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                                            </button>
                                            <div x-ref="menu" class="settings-menu hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                <div class="py-1">
                                                    <button @click="viewDepartment(dept.id)" class="flex w-full items-center px-4 py-2 text-sm text-gray-900 hover:bg-gray-100">
                                                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                                        View Details
                                                    </button>
                                                    <button @click="editDepartment(dept)" class="flex w-full items-center px-4 py-2 text-sm text-gray-900 hover:bg-gray-100">
                                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                                        Edit
                                                    </button>
                                                    <button @click="deleteDepartment(dept.id)" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add/Edit Department Modal -->
                <div x-show="showAddModal" x-cloak class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="editingDept ? 'Edit Department' : 'Add Department'"></h3>
                            
                            <form @submit.prevent="saveDepartment()">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Department Name</label>
                                    <input type="text" x-model="formData.name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea x-model="formData.description" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Users</label>
                                    <select x-model="selectedUserId" @change="addUser()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select a user to assign</option>
                                        <template x-for="user in availableUsers" :key="user.id">
                                            <option :value="user.id" x-text="user.name"></option>
                                        </template>
                                    </select>
                                    
                                    <div class="mt-2 space-y-2" x-show="formData.assigned_users.length > 0">
                                        <template x-for="userId in formData.assigned_users" :key="userId">
                                            <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-md">
                                                <span x-text="getUserName(userId)"></span>
                                                <button type="button" @click="removeUser(userId)" 
                                                        class="text-red-600 hover:text-red-800 text-sm">
                                                    Remove
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <div class="flex items-center mb-4">
                                    <input type="checkbox" x-model="formData.is_active" id="is_active"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button type="button" @click="closeModal()" 
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded">
                                        Cancel
                                    </button>
                                    <button type="submit" :disabled="saving"
                                            class="px-4 py-2 text-sm font-medium text-white bg-black hover:bg-gray-900 rounded disabled:opacity-50">
                                        <span x-text="saving ? 'Saving...' : 'Save'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>

        // Robust settings menu toggle for both nav and table 3-dots
        function toggleSettings(eventOrElement) {
            let trigger, menu;
            if (eventOrElement instanceof Event) {
                // Called with event (both nav and table)
                eventOrElement.stopPropagation();
                trigger = eventOrElement.currentTarget || eventOrElement.target;
                // Check if this is nav (has settingsDropdown) or table (has sibling menu)
                menu = document.getElementById('settingsDropdown') || trigger.nextElementSibling;
            } else {
                // Called with element reference (legacy support)
                trigger = eventOrElement;
                menu = trigger.nextElementSibling;
            }
            if (!menu || !(menu.classList.contains('settings-menu') || menu.classList.contains('settings-dropdown'))) {
                console.error('Settings menu not found or missing class');
                return;
            }
            // Close all other menus
            document.querySelectorAll('.settings-menu, .settings-dropdown').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            // Toggle this menu
            menu.classList.toggle('hidden');
            // Click outside to close
            const handleClickOutside = (e) => {
                if (!menu.contains(e.target) && (!trigger.contains(e.target))) {
                    menu.classList.add('hidden');
                    document.removeEventListener('click', handleClickOutside);
                }
            };
            setTimeout(() => document.addEventListener('click', handleClickOutside), 0);
        }
        // Make toggleSettings globally available for nav inline onclick
        window.toggleSettings = toggleSettings;

        // Close menus on Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.settings-menu, .settings-dropdown').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Close menus when pressing escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.settings-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Alpine.js component for departments management
        function departmentsManager() {
            return {
                departments: [],
                availableUsers: [],
                selectedUserId: '',
                loading: true,
                showAddModal: false,
                editingDept: null,
                saving: false,
                formData: {
                    name: '',
                    description: '',
                    is_active: true,
                    assigned_users: []
                },

                init() {
                    this.loadDepartments();
                    this.loadUsers();
                    // Initialize Lucide icons
                    setTimeout(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }, 100);
                },

                loadDepartments() {
                    this.loading = true;
                    fetch('/api/departments', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Use fallback data if API fails
                            return null;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && Array.isArray(data)) {
                            this.departments = data;
                        } else {
                            // Fallback data
                            this.departments = [
                                {
                                    id: 1,
                                    name: 'Engineering',
                                    description: 'Engineering Team',
                                    tasks_count: 0,
                                    assigned_users_count: 0,
                                    is_active: true
                                },
                                {
                                    id: 2,
                                    name: 'Human Resources',
                                    description: 'HR Department',
                                    tasks_count: 0,
                                    assigned_users_count: 0,
                                    is_active: true
                                }
                            ];
                        }
                        this.loading = false;
                        // Re-initialize Lucide icons after data loads
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    })
                    .catch(error => {
                        console.warn('Departments API not available, using fallback data');
                        // Fallback data
                        this.departments = [
                            {
                                id: 1,
                                name: 'Engineering',
                                description: 'Engineering Team',
                                tasks_count: 0,
                                assigned_users_count: 0,
                                is_active: true
                            },
                            {
                                id: 2,
                                name: 'Human Resources',
                                description: 'HR Department',
                                tasks_count: 0,
                                assigned_users_count: 0,
                                is_active: true
                            }
                        ];
                        this.loading = false;
                        // Re-initialize Lucide icons after data loads
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    });
                },

                viewDepartment(id) {
                    window.location.href = `/internal-tasks/departments/${id}`;
                },

                editDepartment(dept) {
                    this.editingDept = dept;
                    this.formData = {
                        name: dept.name,
                        description: dept.description || '',
                        is_active: dept.is_active,
                        assigned_users: dept.assignedUsers ? dept.assignedUsers.map(u => u.id) : []
                    };
                    this.showAddModal = true;
                },

                deleteDepartment(id) {
                    if (!confirm('Are you sure you want to delete this department?')) return;
                    
                    fetch(`/internal-tasks/departments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            this.loadDepartments();
                            this.showNotification('Department deleted successfully!', 'success');
                        } else {
                            this.showNotification('Failed to delete department', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showNotification('Failed to delete department', 'error');
                    });
                },

                saveDepartment() {
                    this.saving = true;
                    const url = this.editingDept 
                        ? `/internal-tasks/departments/${this.editingDept.id}`
                        : '/internal-tasks/departments';
                    const method = this.editingDept ? 'PUT' : 'POST';

                    const formData = new FormData();
                    formData.append('name', this.formData.name);
                    formData.append('description', this.formData.description);
                    formData.append('is_active', this.formData.is_active ? '1' : '0');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    formData.append('user_ids', JSON.stringify(this.formData.assigned_users));
                    
                    if (this.editingDept) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            this.closeModal();
                            this.loadDepartments();
                            this.showNotification('Department saved successfully!', 'success');
                        } else {
                            this.showNotification(result.message || 'Failed to save department', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showNotification('Failed to save department', 'error');
                    })
                    .finally(() => {
                        this.saving = false;
                    });
                },

                closeModal() {
                    this.showAddModal = false;
                    this.editingDept = null;
                    this.formData = {
                        name: '',
                        description: '',
                        is_active: true
                    };
                },

                showNotification(message, type) {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
                        type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    }`;
                    notification.textContent = message;
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 3000);
                },

                loadUsers() {
                    fetch('/users/list', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) return [];
                        return response.json();
                    })
                    .then(data => {
                        this.availableUsers = Array.isArray(data) ? data : [];
                    })
                    .catch(error => {
                        console.warn('Users API not available');
                        this.availableUsers = [];
                    });
                },

                addUser() {
                    if (!this.selectedUserId || this.formData.assigned_users.includes(this.selectedUserId)) {
                        return;
                    }
                    this.formData.assigned_users.push(this.selectedUserId);
                    this.selectedUserId = ''; // Reset selection
                },

                removeUser(userId) {
                    this.formData.assigned_users = this.formData.assigned_users.filter(id => id !== userId);
                },

                getUserName(userId) {
                    const user = this.availableUsers.find(u => u.id == userId);
                    return user ? user.name : 'Unknown User';
                },

                // Reset form data when opening modal
                openAddModal() {
                    this.editingDept = null;
                    this.formData = {
                        name: '',
                        description: '',
                        is_active: true,
                        assigned_users: []
                    };
                    this.showAddModal = true;
                    // Re-initialize Lucide icons for modal
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                }
            };
        }
    </script>

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
</body>
</html>
              

           