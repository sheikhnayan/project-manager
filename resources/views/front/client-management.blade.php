<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management - Project Management</title>

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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50" x-data="{ showModal: false, editModal: false, openEditModal(id, name) {
    this.editModal = true;
    document.getElementById('edit-client-id').value = id;
    document.getElementById('edit-client-name').value = name;
    document.getElementById('editClientForm').action = `/client-management/${id}`;
}}">
    @include('front.nav')

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Client Management</h1>
                <button @click="showModal = true" class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Client
                </button>
            </div>

            <div class="mt-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        {{-- <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <input
                                    type="text"
                                    placeholder="Search clients..."
                                    class="rounded-md border-gray-300 focus:border-black focus:ring-black"
                                >
                                <select class="rounded-md border-gray-300 focus:border-black focus:ring-black">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="mb-4 flex gap-2">
                            <button id="activeTabBtn" class="px-4 py-2 rounded bg-black text-white" onclick="showTab('active')">Active Clients</button>
                            <button id="archivedTabBtn" class="px-4 py-2 rounded bg-gray-200 text-black" onclick="showTab('archived')">Archived Clients</button>
                        </div>

                        <div class="overflow-x-auto" id="activeTab">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 text-left">Name</th>
                                        <th class="py-3 px-4 text-left">Projects</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @if ($item->is_archived == 0)
                                        <tr class="border-b">
                                            <td class="py-3 px-4">{{ $item->name }}</td>
                                            <td class="py-3 px-4">{{ $item->projects->count() }} Active</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                    Active
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <button class="p-1 hover:bg-gray-100 rounded" @click="openEditModal({{ $item->id }}, '{{ $item->name }}')">
                                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                                    </button>
                                                    <button class="p-1 hover:bg-gray-100 rounded" onclick="archiveClient({{ $item->id }})">
                                                        <i data-lucide="archive" class="w-4 h-4"></i>
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
                                        <th class="py-3 px-4 text-left">Name</th>
                                        <th class="py-3 px-4 text-left">Projects</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @if ($item->is_archived == 1)
                                        <tr class="border-b">
                                            <td class="py-3 px-4">{{ $item->name }}</td>
                                            <td class="py-3 px-4">{{ $item->projects->count() }} Projects</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                                                    Archived
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <button class="p-1 hover:bg-gray-100 rounded" @click="openEditModal({{ $item->id }}, '{{ $item->name }}')">
                                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                                    </button>
                                                    <button class="p-1 hover:bg-gray-100 rounded" onclick="archiveClient({{ $item->id }})">
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
        </div>
    </main>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
            <h2 class="text-2xl font-bold mb-4">Add Client</h2>
            <form action="{{route('client.store')}}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="client-name" class="block text-sm font-medium text-gray-700">Client Name</label>
                    <input type="text" id="client-name" name="name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showModal = false" class="bg-gray-300 text-black px-4 py-2 rounded-md mr-2">Cancel</button>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div x-show="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
            <h2 class="text-2xl font-bold mb-4">Edit Client</h2>
            <form id="editClientForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-client-id" name="id">
                <div class="mb-4">
                    <label for="edit-client-name" class="block text-sm font-medium text-gray-700">Client Name</label>
                    <input type="text" id="edit-client-name" name="name" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="editModal = false" class="bg-gray-300 text-black px-4 py-2 rounded-md mr-2">Cancel</button>
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

        function archiveClient(clientId) {
            if (confirm('Are you sure you want to archive this client ?')) {
                fetch(`/client-management/${clientId}/archive`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to archive the client.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while archiving the client.');
                });
            }
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
        }

        // Set default tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            showTab('active');
        });
    </script>
</body>
</html>
