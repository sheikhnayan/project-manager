<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management - Project Management</title>

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
    <!-- Add in <head> before </head> -->
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    
    <style>
        /* Modal input styling */
        .modal input, .modal select, .modal textarea {
            padding: 0.5rem 0.75rem !important;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="userManagement">
    @include('front.nav')

    <main class="py-6">
        <div class="mx-auto px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">User Management</h1>
                <button class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2" @click="showAddUserModal = true">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add User
                </button>
            </div>

            <div class="mt-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        {{-- <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <input
                                    type="text"
                                    placeholder="Search users..."
                                    class="rounded-md border-gray-300 focus:border-black focus:ring-black"
                                >
                                <select class="rounded-md border-gray-300 focus:border-black focus:ring-black">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="employee">Employee</option>
                                </select>
                                <select class="rounded-md border-gray-300 focus:border-black focus:ring-black">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="mb-4 flex gap-2">
                            <button id="activeTabBtn" class="px-4 py-2 rounded bg-black text-white" onclick="showTab('active')">Active Users</button>
                            <button id="archivedTabBtn" class="px-4 py-2 rounded bg-gray-200 text-black" onclick="showTab('archived')">Archived Users</button>
                        </div>

                        <div class="overflow-x-auto" id="activeTab">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(0)">
                                            Name
                                            <span class="sort-indicator" data-column="0">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                            Email
                                            <span class="sort-indicator" data-column="1">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                            Role
                                            <span class="sort-indicator" data-column="2">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @if ($item->is_archived == 0)
                                        <tr class="border-b">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                                                        @if($item->profile_image_url)
                                                            <img src="{{ asset('storage/' . $item->profile_image_url) }}" 
                                                                 alt="{{ $item->name }}" 
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-medium">
                                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span>{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">{{ $item->email }}</td>
                                            <td class="py-3 px-4">{{ $item->role }}</td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <button class="p-1 hover:bg-gray-100 rounded"
    @click="openEditUserModal(
        {{ $item->id }},
        '{{ $item->name }}',
        '{{ $item->email }}',
        '{{ $item->role }}',
        {{ $item->role_id ?? 'null' }},
        {{ $item->hourly_rate }},
        '{{ $item->profile_image_url ? asset('storage/' . $item->profile_image_url) : '' }}'
    )">
    <i data-lucide="pencil" class="w-4 h-4"></i>

</button>
{{-- archive button --}}
<button class="p-1 hover:bg-gray-100 rounded archive-btn"
        data-user-id="{{ $item->id }}"
        data-archived="{{ $item->is_archived }}"
        title="Archive User">
    <i data-lucide="archive" class="w-4 h-4"></i>
</button>
{{-- delete button --}}
<button class="p-1 hover:bg-red-100 rounded text-red-600 delete-btn"
        data-user-id="{{ $item->id }}"
        data-user-name="{{ $item->name }}"
        title="Delete User">
    <i data-lucide="trash-2" class="w-4 h-4"></i>
</button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="overflow-x-auto hidden" id="archivedTab">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(0)">
                                            Name
                                            <span class="sort-indicator" data-column="0">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                            Email
                                            <span class="sort-indicator" data-column="1">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                            Role
                                            <span class="sort-indicator" data-column="2">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @if ($item->is_archived == 1)
                                        <tr class="border-b">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                                                        @if($item->profile_image_url)
                                                            <img src="{{ asset('storage/' . $item->profile_image_url) }}" 
                                                                 alt="{{ $item->name }}" 
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-medium">
                                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span>{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">{{ $item->email }}</td>
                                            <td class="py-3 px-4">{{ $item->role }}</td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <button class="p-1 hover:bg-gray-100 rounded"
                                                        @click="openEditUserModal(
                                                            {{ $item->id }},
                                                            '{{ $item->name }}',
                                                            '{{ $item->email }}',
                                                            '{{ $item->role }}',
                                                            {{ $item->role_id ?? 'null' }},
                                                            {{ $item->hourly_rate }},
                                                            '{{ $item->profile_image_url ? asset('storage/' . $item->profile_image_url) : '' }}'
                                                        )">
                                                        <i data-lucide="pencil" class="w-4 h-4"></i>

                                                    </button>
                                                    {{-- unarchive button --}}
                                                    <button class="p-1 hover:bg-gray-100 rounded archive-btn"
                                                            data-user-id="{{ $item->id }}"
                                                            data-archived="{{ $item->is_archived }}"
                                                            title="Unarchive User">
                                                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                                    </button>
                                                    {{-- delete button --}}
                                                    <button class="p-1 hover:bg-red-100 rounded text-red-600 delete-btn"
                                                            data-user-id="{{ $item->id }}"
                                                            data-user-name="{{ $item->name }}"
                                                            title="Delete User">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
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
        </div>
    </main>

    <!-- Add User Modal -->
    <div x-show="showAddUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-8 w-1/3">
            <h2 class="text-2xl font-bold mb-4">Add User</h2>
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="add-user-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="add-user-name" name="name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="add-user-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="add-user-email" name="email" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="add-user-role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="add-user-role" name="role_id" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                        <option value="">Select a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="add-user-hourly-rate" class="block text-sm font-medium text-gray-700">Hourly Rate</label>
                    <input type="number" id="add-user-hourly-rate" name="hourly_rate" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div x-data="{
                        imageUrl: '',
                        cropper: null,
                        showCropper: false,
                        croppedBlob: null,
                        handleFileChange(event) {
                            const file = event.target.files[0];
                            if (file && file.type.startsWith('image/')) {
                                // Clear previous state
                                if (this.cropper) {
                                    this.cropper.destroy();
                                    this.cropper = null;
                                }
                                this.imageUrl = '';
                                this.showCropper = false;
                                this.croppedBlob = null;
                                
                                const reader = new FileReader();
                                reader.onload = e => {
                                    this.imageUrl = e.target.result;
                                    this.showCropper = true;
                                    this.$nextTick(() => {
                                        const image = this.$refs.cropperImage;
                                        this.cropper = new Cropper(image, {
                                            aspectRatio: 1,
                                            viewMode: 1,
                                        });
                                    });
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        cropImage() {
                            if (this.cropper) {
                                this.cropper.getCroppedCanvas().toBlob(blob => {
                                    this.croppedBlob = blob;
                                    // Show preview
                                    this.imageUrl = URL.createObjectURL(blob);
                                    this.showCropper = false;
                                    // Set blob to hidden input for form submit
                                    const fileInput = document.getElementById('add-user-profile-picture-cropped');
                                    const dt = new DataTransfer();
                                    dt.items.add(new File([blob], 'profile_picture.png', {type: 'image/png'}));
                                    fileInput.files = dt.files;
                                });
                            }
                        }
                    }" class="mb-4">
                    <label for="add-user-profile-picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                    <input type="file" id="add-user-profile-picture" name="profile_picture_raw" accept="image/*"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                        @change="handleFileChange">
                    <!-- Hidden input for cropped image -->
                    <input type="file" id="add-user-profile-picture-cropped" name="profile_picture" style="display:none;">
                    <!-- Preview -->
                    <template x-if="imageUrl && !showCropper">
                        <img :src="imageUrl" alt="Preview" class="mt-2 w-24 h-24 rounded-full object-cover border">
                    </template>
                    <!-- Cropper Modal -->
                    <div x-show="showCropper" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                        <div class="bg-white p-4 rounded shadow-lg">
                            <img :src="imageUrl" x-ref="cropperImage" class="max-w-xs max-h-80">
                            <div class="mt-4 flex justify-end gap-2">
                                <button type="button" class="bg-gray-300 px-3 py-1 rounded" @click="showCropper=false; cropper.destroy();">Cancel</button>
                                <button type="button" class="bg-black text-white px-3 py-1 rounded" @click="cropImage">Crop & Use</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showAddUserModal = false" class="bg-gray-300 text-black px-4 py-2 rounded-md mr-2">Cancel</button>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded-md">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div x-show="showEditUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-8 w-1/3">
            <h2 class="text-2xl font-bold mb-4">Edit User</h2>
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="edit-user-id" name="id">
                <div class="mb-4">
                    <label for="edit-user-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="edit-user-name" name="name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="edit-user-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="edit-user-email" name="email" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>
                <div class="mb-4">
                    <label for="edit-user-role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="edit-user-role" name="role_id" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                        <option value="">Select a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="edit-user-hourly-rate" class="block text-sm font-medium text-gray-700">Hourly Rate</label>
                    <input type="number" id="edit-user-hourly-rate" name="hourly_rate" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                </div>

                <div x-data="{
                        imageUrl: '',
                        cropper: null,
                        showCropper: false,
                        croppedBlob: null,
                        init() {
                            // Initialize imageUrl when component loads
                            this.$nextTick(() => {
                                const urlInput = document.getElementById('edit-user-profile-picture-url');
                                if (urlInput && urlInput.value) {
                                    this.imageUrl = urlInput.value;
                                }
                            });
                        },
                        handleFileChange(event) {
                            const file = event.target.files[0];
                            if (file && file.type.startsWith('image/')) {
                                // Clear previous state
                                if (this.cropper) {
                                    this.cropper.destroy();
                                    this.cropper = null;
                                }
                                this.imageUrl = '';
                                this.showCropper = false;
                                this.croppedBlob = null;
                                
                                const reader = new FileReader();
                                reader.onload = e => {
                                    this.imageUrl = e.target.result;
                                    this.showCropper = true;
                                    this.$nextTick(() => {
                                        const image = this.$refs.cropperImage;
                                        this.cropper = new Cropper(image, {
                                            aspectRatio: 1,
                                            viewMode: 1,
                                        });
                                    });
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        cropImage() {
                            if (this.cropper) {
                                this.cropper.getCroppedCanvas().toBlob(blob => {
                                    this.croppedBlob = blob;
                                    // Show preview
                                    this.imageUrl = URL.createObjectURL(blob);
                                    this.showCropper = false;
                                    // Set blob to hidden input for form submit
                                    const fileInput = document.getElementById('edit-user-profile-picture-cropped');
                                    const dt = new DataTransfer();
                                    dt.items.add(new File([blob], 'profile_picture.png', {type: 'image/png'}));
                                    fileInput.files = dt.files;
                                });
                            }
                        }
                    }" x-ref="editUserImageCropperAlpine" class="mb-4">
                    <label for="edit-user-profile-picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                    <input type="file" id="edit-user-profile-picture" name="profile_picture_raw" accept="image/*"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                        @change="handleFileChange">
                    <!-- Hidden input for cropped image -->
                    <input type="file" id="edit-user-profile-picture-cropped" name="profile_picture" style="display:none;">
                    <input type="hidden" id="edit-user-profile-picture-url">
                    <!-- Preview -->
                    <template x-if="imageUrl && !showCropper">
                        <img :src="imageUrl" alt="Preview" class="mt-2 w-24 h-24 rounded-full object-cover border">
                    </template>
                    <!-- Cropper Modal -->
                    <div x-show="showCropper" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                        <div class="bg-white p-4 rounded shadow-lg">
                            <img :src="imageUrl" x-ref="cropperImage" class="max-w-xs max-h-80">
                            <div class="mt-4 flex justify-end gap-2">
                                <button type="button" class="bg-gray-300 px-3 py-1 rounded" @click="showCropper=false; cropper.destroy();">Cancel</button>
                                <button type="button" class="bg-black text-white px-3 py-1 rounded" @click="cropImage">Crop & Use</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showEditUserModal = false" class="bg-gray-300 text-black px-4 py-2 rounded-md mr-2">Cancel</button>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

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

    <script>

        // Sort table functionality
        let sortDirection = {}; // Track sort direction for each column

        function sortTable(columnIndex) {
            const tableBody = document.querySelector('tbody'); // Get the table body
            const rows = Array.from(tableBody.querySelectorAll('tr')); // Get all rows as an array

            // Determine the sort direction (toggle between ascending and descending)
            sortDirection[columnIndex] = !sortDirection[columnIndex];

            // Sort rows based on the selected column
            rows.sort((a, b) => {
                const cellA = a.querySelector(`td:nth-child(${columnIndex + 1})`).textContent.trim().toLowerCase();
                const cellB = b.querySelector(`td:nth-child(${columnIndex + 1})`).textContent.trim().toLowerCase();

                if (cellA < cellB) return sortDirection[columnIndex] ? -1 : 1;
                if (cellA > cellB) return sortDirection[columnIndex] ? 1 : -1;
                return 0;
            });

            // Append sorted rows back to the table body
            rows.forEach(row => tableBody.appendChild(row));

            // Update the sort indicator
            updateSortIndicator(columnIndex);
        }

        function updateSortIndicator(columnIndex) {
            const indicators = document.querySelectorAll('.sort-indicator');
            indicators.forEach(indicator => {
                const col = indicator.getAttribute('data-column');
                if (col == columnIndex) {
                    indicator.textContent = sortDirection[columnIndex] ? '▲' : '▼'; // Update arrow direction
                } else {
                    indicator.textContent = '▲▼'; // Reset other columns
                }
            });
        }

        function showTab(tab) {
            const activeTab = document.getElementById('activeTab');
            const archivedTab = document.getElementById('archivedTab');
            const activeBtn = document.getElementById('activeTabBtn');
            const archivedBtn = document.getElementById('archivedTabBtn');

            if(tab === 'active') {
                activeTab.classList.remove('hidden');
                archivedTab.classList.add('hidden');
                activeBtn.classList.add('bg-black', 'text-white');
                activeBtn.classList.remove('bg-gray-200', 'text-black');
                archivedBtn.classList.remove('bg-black', 'text-white');
                archivedBtn.classList.add('bg-gray-200', 'text-black');
            } else {
                activeTab.classList.add('hidden');
                archivedTab.classList.remove('hidden');
                archivedBtn.classList.add('bg-black', 'text-white');
                archivedBtn.classList.remove('bg-gray-200', 'text-black');
                activeBtn.classList.remove('bg-black', 'text-white');
                activeBtn.classList.add('bg-gray-200', 'text-black');
            }
            
            // Save current tab to localStorage
            localStorage.setItem('activeUserTab', tab);
        }

        // Set default tab on page load - restore from localStorage or default to active
        document.addEventListener('DOMContentLoaded', function() {
            const savedTab = localStorage.getItem('activeUserTab') || 'active';
            showTab(savedTab);
        });
    </script>

    {{-- archive user functionality --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).on('click', '.archive-btn', function() {
    var btn = $(this);
    var userId = btn.data('user-id');
    var isArchived = btn.data('archived');

    var confirmMsg = isArchived ? 'unarchive this user' : 'archive this user';
    if(!confirm('Are you sure you want to ' + confirmMsg + '?')) return;

    // Save current tab before action
    const currentTab = document.getElementById('activeTab').classList.contains('hidden') ? 'archived' : 'active';
    localStorage.setItem('activeUserTab', currentTab);

    $.ajax({
        url: '/users/' + userId + '/archive',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                // Reload the page to refresh the tabs with updated data
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
});
    </script>

    <script>
        // Alpine.js data for user management
        document.addEventListener('alpine:init', () => {
            Alpine.data('userManagement', () => ({
                showAddUserModal: false,
                showEditUserModal: false,
                openEditUserModal(id, name, email, role, roleId, hourlyRate, profilePicture) {
                    this.showEditUserModal = true;
                    document.getElementById('edit-user-id').value = id;
                    document.getElementById('edit-user-name').value = name;
                    document.getElementById('edit-user-email').value = email;
                    document.getElementById('edit-user-role').value = roleId || '';
                    document.getElementById('edit-user-hourly-rate').value = hourlyRate;
                    document.getElementById('editUserForm').action = `/users/${id}`;
                    document.getElementById('edit-user-profile-picture-url').value = profilePicture ? profilePicture : '';
                    
                    // Update the Alpine component's imageUrl
                    this.$nextTick(() => {
                        const editUserCropper = document.querySelector('[x-ref="editUserImageCropperAlpine"]');
                        if (editUserCropper && editUserCropper._x_dataStack && editUserCropper._x_dataStack[0]) {
                            editUserCropper._x_dataStack[0].imageUrl = profilePicture ? profilePicture : '';
                        }
                    });
                }
            }))
        });

        // Delete user functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners for delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const userName = this.dataset.userName;
                    
                    if (confirm(`Are you sure you want to delete ${userName}? This action cannot be undone.`)) {
                        // Save current tab before action
                        const currentTab = document.getElementById('activeTab').classList.contains('hidden') ? 'archived' : 'active';
                        localStorage.setItem('activeUserTab', currentTab);
                        
                        // Create form and submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/users/${userId}`;
                        form.style.display = 'none';
                        
                        // Add CSRF token
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        form.appendChild(csrfToken);
                        
                        // Add DELETE method
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>