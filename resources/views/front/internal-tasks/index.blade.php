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
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Internal Tasks Management</h1>
                    <p class="text-gray-600 mt-2">Manage departments for internal task tracking</p>
                </div>
                <a href="{{ route('internal-tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
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

            <!-- Departments Management -->
            <div x-data="departmentsManager()" class="space-y-6">
                <!-- Departments Table -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">Departments</h2>
                            <button @click="showAddModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Department
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button @click="viewDepartment(dept.id)" class="text-blue-600 hover:text-blue-800 font-medium">
                                                <span x-text="dept.name"></span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span x-text="dept.description || '-'" class="text-gray-600"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span x-text="dept.tasks_count || 0" class="text-gray-900"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span x-text="dept.assigned_users_count || 0" class="text-gray-900"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="dept.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                <span x-text="dept.is_active ? 'Active' : 'Inactive'"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button @click="viewDepartment(dept.id)" class="text-blue-600 hover:text-blue-800" title="View">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </button>
                                                <button @click="editDepartment(dept)" class="text-green-600 hover:text-green-800" title="Edit">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </button>
                                                <button @click="deleteDepartment(dept.id)" class="text-red-600 hover:text-red-800" title="Delete">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
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
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                                        Cancel
                                    </button>
                                    <button type="submit" :disabled="saving"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md disabled:opacity-50">
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
                    fetch('/internal-tasks/departments', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        this.departments = data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error loading departments:', error);
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
                    .then(response => response.json())
                    .then(data => {
                        this.availableUsers = data;
                    })
                    .catch(error => {
                        console.error('Error loading users:', error);
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
                }
            };
        }

        // Initialize Lucide icons when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 500);
        });
    </script>
</body>
</html>
              

           