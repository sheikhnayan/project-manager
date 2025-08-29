<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Setting - Project Management</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Date-fns -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/date-fns/2.30.0/date-fns.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>
</head>
<body class="bg-gray-50" x-data="{
    showAddUserModal: false
}">
    @include('front.nav')
    <div class="mx-auto p-6 shadow rounded-lg border" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-600 mt-1">Configure your application settings</p>
        </div>

        <form method="POST" action="/settings" enctype="multipart/form-data" class="space-y-6">
            @csrf
            {{-- @method('PUT') --}}

            <!-- Time Format Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Time & Date Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Time Format -->
                    <div>
                        <label for="time_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Time Format
                        </label>
                        <select name="time_format" id="time_format" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                            <option value="24-hour" {{ old('time_format', $data->time_format ?? '24-hour') == '24-hour' ? 'selected' : '' }}>
                                24-hour (14:30)
                            </option>
                            <option value="12-hour" {{ old('time_format', $data->time_format ?? '24-hour') == '12-hour' ? 'selected' : '' }}>
                                12-hour (2:30 PM)
                            </option>
                        </select>
                    </div>

                    <!-- Date Format -->
                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Date Format
                        </label>
                        <select name="date_format" id="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                            <option value="Y-m-d" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>
                                YYYY-MM-DD (2025-08-07)
                            </option>
                            <option value="m/d/Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>
                                MM/DD/YYYY (08/07/2025)
                            </option>
                            <option value="d/m/Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>
                                DD/MM/YYYY (07/08/2025)
                            </option>
                            <option value="d-m-Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'd-m-Y' ? 'selected' : '' }}>
                                DD-MM-YYYY (07-08-2025)
                            </option>
                            <option value="M j, Y" {{ old('date_format', $data->date_format ?? 'Y-m-d') == 'M j, Y' ? 'selected' : '' }}>
                                Aug 7, 2025
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Currency & Working Hours Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Business Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                            Currency
                        </label>
                        <select name="currency" id="currency" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                            <option value="USD" {{ old('currency', $data->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD - US Dollar ($)</option>
                            <option value="EUR" {{ old('currency', $data->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                            <option value="GBP" {{ old('currency', $data->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP - British Pound (£)</option>
                            <option value="JPY" {{ old('currency', $data->currency ?? 'USD') == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen (¥)</option>
                            <option value="CAD" {{ old('currency', $data->currency ?? 'USD') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar (C$)</option>
                            <option value="AUD" {{ old('currency', $data->currency ?? 'USD') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar (A$)</option>
                            <option value="CHF" {{ old('currency', $data->currency ?? 'USD') == 'CHF' ? 'selected' : '' }}>CHF - Swiss Franc</option>
                            <option value="CNY" {{ old('currency', $data->currency ?? 'USD') == 'CNY' ? 'selected' : '' }}>CNY - Chinese Yuan (¥)</option>
                            <option value="INR" {{ old('currency', $data->currency ?? 'USD') == 'INR' ? 'selected' : '' }}>INR - Indian Rupee (₹)</option>
                            <option value="DKK" {{ old('currency', $data->currency ?? 'USD') == 'DKK' ? 'selected' : '' }}>DKK - Danish Krone (kr)</option>
                        </select>
                    </div>

                    <!-- Working Hours -->
                    <div>
                        <label for="working_hour" class="block text-sm font-medium text-gray-700 mb-2">
                            Daily Working Hours
                        </label>
                        <div class="relative">
                            <input style="height: 38px;"
                                   type="number" 
                                   name="working_hour" 
                                   id="working_hour" 
                                   min="1" 
                                   max="24" 
                                   step="0.5"
                                   value="{{ old('working_hour', $data->working_hour ?? '8') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="8">
                            <span class="absolute right-3 top-2 text-gray-500 text-sm">hours</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Standard working hours per day (1-24 hours)</p>
                    </div>
                </div>
            </div>

            <!-- Logo Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Branding</h2>
                
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Company Logo
                    </label>
                    <div class="flex items-center space-x-4">
                        <!-- Current Logo Preview -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 border-2 border-gray-300 border-dashed rounded-lg flex items-center justify-center bg-gray-50">
                                @if(isset($data->logo) && $data->logo && $data->logo != '8')
                                    <img src="{{ asset('storage/' . $data->logo) }}" alt="Current Logo" class="w-full h-full object-contain rounded-lg">
                                @else
                                    <i class="fas fa-image text-gray-400 text-xl"></i>
                                @endif
                            </div>
                        </div>
                        
                        <!-- File Input -->
                        <div class="flex-1">
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-black hover:file:bg-gray-100">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB. Recommended size: 200x200px</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Presets Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200" x-data="{
                presets: {{ json_encode($data->task_presets ?? []) }},
                showAddPreset: false,
                editingPreset: null,
                newPreset: {
                    title: '',
                    tasks: []
                },
                init() {
                    this.$nextTick(() => {
                        this.initSortable();
                    });
                },
                initSortable() {
                    // Re-initialize sortable when tasks change
                    this.$watch('showAddPreset', () => {
                        if (this.showAddPreset) {
                            this.$nextTick(() => {
                                const taskContainer = document.querySelector('.task-sortable');
                                if (taskContainer) {
                                    // Destroy previous instance if exists
                                    if (taskContainer.sortableInstance) {
                                        taskContainer.sortableInstance.destroy();
                                    }
                                    
                                    // Create new sortable instance
                                    taskContainer.sortableInstance = Sortable.create(taskContainer, {
                                        animation: 150,
                                        ghostClass: 'bg-gray-100',
                                        chosenClass: 'bg-gray-50',
                                        dragClass: 'opacity-50',
                                        handle: '.cursor-move',
                                        onEnd: (evt) => {
                                            // Reorder the tasks array
                                            const tasks = this.newPreset.tasks;
                                            const movedTask = tasks[evt.oldIndex];
                                            tasks.splice(evt.oldIndex, 1);
                                            tasks.splice(evt.newIndex, 0, movedTask);
                                            
                                            // Update positions
                                            this.updateTaskPositions();
                                        }
                                    });
                                }
                            });
                        } else {
                            // Cleanup when modal closes
                            const taskContainer = document.querySelector('.task-sortable');
                            if (taskContainer && taskContainer.sortableInstance) {
                                taskContainer.sortableInstance.destroy();
                                taskContainer.sortableInstance = null;
                            }
                        }
                    });
                },
                updateTaskPositions() {
                    // Update positions after drag and drop
                    this.newPreset.tasks.forEach((task, index) => {
                        task.position = index + 1;
                    });
                }
            }">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Task List Presets</h2>
                    <button type="button" 
                            @click="showAddPreset = true; newPreset = {title: '', tasks: []}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        <i class="fas fa-plus mr-2"></i>
                        Add Preset
                    </button>
                </div>

                <!-- Existing Presets List -->
                <div class="space-y-4 mb-6">
                    <template x-for="(preset, presetIndex) in presets" :key="presetIndex">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900" x-text="preset.title"></h3>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="button" 
                                            @click.stop="editingPreset = presetIndex; newPreset = JSON.parse(JSON.stringify(preset)); showAddPreset = true"
                                            class="text-black hover:text-gray-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                            @click.stop="presets.splice(presetIndex, 1)"
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Task List Display -->
                            <div class="space-y-2">
                                <template x-for="(task, taskIndex) in preset.tasks" :key="taskIndex">
                                    <div class="flex items-center justify-between bg-white px-3 py-2 rounded border">
                                        <span class="text-sm" x-text="`${task.position || (taskIndex + 1)}. ${task.name}`"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Add/Edit Preset Modal -->
                <div x-show="showAddPreset" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                     style="display: none;">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900" 
                                    x-text="editingPreset !== null ? 'Edit Task Preset' : 'Add New Task Preset'"></h3>
                                <button @click.stop="showAddPreset = false; editingPreset = null" 
                                        type="button"
                                        class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Country/Title Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preset Heading</label>
                                    <input type="text" 
                                           x-model="newPreset.title"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                           placeholder="e.g., Denmark Standard Tasks or Germany Construction">
                                </div>

                                <!-- Tasks List -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Tasks</label>
                                        <button type="button" 
                                                @click="newPreset.tasks.push({name: ''})"
                                                class="text-sm text-black hover:text-gray-800">
                                            <i class="fas fa-plus mr-1"></i>Add Task
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-3 max-h-64 overflow-y-auto task-sortable">
                                        <template x-for="(task, taskIndex) in newPreset.tasks" :key="taskIndex">
                                            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-md bg-gray-50">
                                                <!-- Drag Handle -->
                                                <div class="cursor-move text-gray-400 hover:text-gray-600 transition-colors">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </div>
                                                
                                                <!-- Task Name -->
                                                <div class="flex-1">
                                                    <input type="text" 
                                                           x-model="task.name"
                                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black"
                                                           placeholder="Task name">
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <button type="button" 
                                                        @click.stop="newPreset.tasks.splice(taskIndex, 1); updateTaskPositions()"
                                                        class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Actions -->
                            <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t">
                                <button type="button" 
                                        @click.stop="showAddPreset = false; editingPreset = null"
                                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" 
                                        @click.stop="
                                            if (editingPreset !== null) {
                                                updateTaskPositions();
                                                presets[editingPreset] = JSON.parse(JSON.stringify(newPreset));
                                            } else {
                                                updateTaskPositions();
                                                presets.push(JSON.parse(JSON.stringify(newPreset)));
                                            }
                                            showAddPreset = false; 
                                            editingPreset = null;
                                        "
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800">
                                    <span x-text="editingPreset !== null ? 'Update' : 'Save'"></span> Preset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden input to store presets data -->
                <input type="hidden" name="task_presets" :value="JSON.stringify(presets)">
            </div>

            <!-- Role Management Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200" x-data="{
                roles: {{ json_encode($roles) }},
                editRole(role) {
                    window.roleManager.editRole(role);
                },
                deleteRole(roleId) {
                    window.roleManager.deleteRole(roleId);
                }
            }" x-init="window.roleManager = window.roleManager || {};">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Role Management</h2>
                {{-- <p class="text-gray-600 mb-6">Create and manage user roles with specific permissions</p> --}}

                <!-- Add Role Button -->
                <button @click="$dispatch('open-role-modal')" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" style="background-color: #000;">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Role
                </button>
            </div>

                <!-- Roles Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Role Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Permissions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="role in roles" :key="role.id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b" x-text="role.display_name"></td>
                                    <td class="px-6 py-4 text-sm text-gray-500 border-b" x-text="role.description"></td>
                                    <td class="px-6 py-4 text-sm text-gray-500 border-b">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" x-text="role.permissions.length + ' permissions'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border-b">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                              :class="role.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                              x-text="role.is_active ? 'Active' : 'Inactive'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b">
                                        <button @click.stop="editRole(role)" type="button" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click.stop="deleteRole(role.id)" type="button" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Save Settings
                </button>
            </div>
        </form>

        <!-- Role Management Modals (Outside main form) -->
        <div x-data="{
            showAddRoleModal: false,
            showEditRoleModal: false,
            editingRole: {
                id: null,
                display_name: '',
                description: '',
                permissions: []
            },
            roles: {{ json_encode($roles) }},
            permissions: {{ json_encode($permissions) }},
            permissionGroups: {{ json_encode($permissionGroups) }},
            newRole: {
                name: '',
                display_name: '',
                description: '',
                permissions: []
            },
            resetNewRole() {
                this.newRole = {
                    name: '',
                    display_name: '',
                    description: '',
                    permissions: []
                };
            },
            togglePermission(permissionId) {
                const index = this.newRole.permissions.indexOf(permissionId);
                if (index > -1) {
                    this.newRole.permissions.splice(index, 1);
                } else {
                    this.newRole.permissions.push(permissionId);
                }
            },
            editRole(role) {
                this.editingRole = { 
                    id: role.id,
                    display_name: role.display_name || '',
                    description: role.description || '',
                    permissions: role.permissions ? role.permissions.map(p => p.id) : []
                };
                this.showEditRoleModal = true;
            },
            resetEditingRole() {
                this.editingRole = {
                    id: null,
                    display_name: '',
                    description: '',
                    permissions: []
                };
            },
            async saveRole() {
                try {
                    console.log('Saving role:', this.newRole);
                    
                    this.newRole.name = this.newRole.display_name;
                    
                    console.log('Testing simple route...');
                    const testResponse = await fetch('/test-roles', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            test: 'data',
                            name: this.newRole.display_name
                        })
                    });
                    
                    const testResult = await testResponse.json();
                    console.log('Test route response:', testResult);
                    
                    const response = await fetch('/roles', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: this.newRole.display_name,
                            display_name: this.newRole.display_name,
                            description: this.newRole.description,
                            permissions: this.newRole.permissions
                        })
                    });
                    
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Response error text:', errorText);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    console.log('Response data:', result);
                    
                    if (result.success) {
                        this.roles.push(result.role);
                        this.resetNewRole();
                        this.showAddRoleModal = false;
                        alert('Role created successfully!');
                        location.reload();
                    } else {
                        alert('Error creating role: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error creating role:', error);
                    alert('Error creating role: ' + error.message);
                }
            },
            async updateRole() {
                try {
                    const response = await fetch(`/roles/${this.editingRole.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            display_name: this.editingRole.display_name,
                            description: this.editingRole.description,
                            permissions: this.editingRole.permissions
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        const index = this.roles.findIndex(r => r.id === this.editingRole.id);
                        if (index > -1) {
                            this.roles[index] = result.role;
                        }
                        this.showEditRoleModal = false;
                        this.resetEditingRole();
                        alert('Role updated successfully!');
                        location.reload();
                    } else {
                        alert('Error updating role: ' + result.message);
                    }
                } catch (error) {
                    alert('Error updating role: ' + error.message);
                }
            },
            async deleteRole(roleId) {
                if (!confirm('Are you sure you want to delete this role?')) return;
                
                try {
                    const response = await fetch(`/roles/${roleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.roles = this.roles.filter(r => r.id !== roleId);
                        alert('Role deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting role: ' + result.message);
                    }
                } catch (error) {
                    alert('Error deleting role: ' + error.message);
                }
            }
        }" 
        @open-role-modal.window="showAddRoleModal = true"
        @edit-role.window="editRole($event.detail)"
        @delete-role.window="deleteRole($event.detail)"
        x-init="
            window.roleManager = {
                editRole: (role) => $dispatch('edit-role', role),
                deleteRole: (roleId) => $dispatch('delete-role', roleId)
            };
        ">

            <!-- Add Role Modal -->
            <div x-show="showAddRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Role</h3>
                        
                        <div class="space-y-4">
                            <!-- Role Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role Name</label>
                                <input type="text" x-model="newRole.display_name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea x-model="newRole.description" rows="3" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"></textarea>
                            </div>

                            <!-- Permissions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                                <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
                                    <template x-for="(groupPermissions, groupName) in permissionGroups" :key="groupName">
                                        <div class="mb-4">
                                            <h4 class="font-medium text-gray-900 mb-2 capitalize" x-text="groupName.replace('_', ' ')"></h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <template x-for="permission in groupPermissions" :key="permission.id">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               :value="permission.id" 
                                                               @change="togglePermission(permission.id)"
                                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="ml-2 text-sm text-gray-700" x-text="permission.display_name"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button @click.stop="showAddRoleModal = false; resetNewRole()" type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                            <button @click.stop="console.log('Create Role button clicked'); saveRole()" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Role</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Role Modal -->
            <div x-show="showEditRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Role</h3>
                        
                        <div class="space-y-4">
                            <!-- Role Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role Name</label>
                                <input type="text" x-model="editingRole.display_name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea x-model="editingRole.description" rows="3" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"></textarea>
                            </div>

                            <!-- Permissions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                                <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
                                    <template x-for="(groupPermissions, groupName) in permissionGroups" :key="groupName">
                                        <div class="mb-4">
                                            <h4 class="font-medium text-gray-900 mb-2 capitalize" x-text="groupName.replace('_', ' ')"></h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <template x-for="permission in groupPermissions" :key="permission.id">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               :value="permission.id" 
                                                               :checked="editingRole && editingRole.permissions.includes(permission.id)"
                                                               @change="
                                                                   const index = editingRole.permissions.indexOf(permission.id);
                                                                   if (index > -1) {
                                                                       editingRole.permissions.splice(index, 1);
                                                                   } else {
                                                                       editingRole.permissions.push(permission.id);
                                                                   }
                                                               "
                                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="ml-2 text-sm text-gray-700" x-text="permission.display_name"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button @click.stop="showEditRoleModal = false; resetEditingRole()" type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                            <button @click.stop="updateRole()" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Role</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sortable.js for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <style>
        .cursor-move {
            cursor: move;
        }
        
        .cursor-move:hover {
            cursor: grab;
        }
        
        .cursor-move:active {
            cursor: grabbing;
        }
        
        .task-sortable .sortable-ghost {
            opacity: 0.4;
        }
        
        .task-sortable .sortable-chosen {
            background-color: rgba(0, 0, 0, 0.1) !important;
        }
        
        .task-sortable .sortable-drag {
            opacity: 0.6;
        }
    </style>
    
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
