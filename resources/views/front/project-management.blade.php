<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management - Project Management</title>

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
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Project Management</h1>
                <a class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2" href="{{route('projects.create')}}">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Create Project
                </a>
            </div>

            <div class="mt-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        {{-- <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <input
                                    type="text"
                                    placeholder="Search projects..."
                                    class="rounded-md border-gray-300 focus:border-black focus:ring-black"
                                >
                                <select class="rounded-md border-gray-300 focus:border-black focus:ring-black">
                                    <option value="">All Clients</option>
                                </select>
                                <select class="rounded-md border-gray-300 focus:border-black focus:ring-black">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="mb-4 flex gap-2">
                            <button id="activeTabBtn" class="px-4 py-2 rounded bg-black text-white" onclick="showTab('active')">Active Projects</button>
                            <button id="archivedTabBtn" class="px-4 py-2 rounded bg-gray-200 text-black" onclick="showTab('archived')">Archived Projects</button>
                        </div>

                        <div class="overflow-x-auto" id="activeTab">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(0)">
                                            Project Number
                                            <span class="sort-indicator" data-column="0">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                            Project
                                            <span class="sort-indicator" data-column="1">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                            Client
                                            <span class="sort-indicator" data-column="2">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(3)">
                                            Timeline
                                            <span class="sort-indicator" data-column="3">▲▼</span>
                                        </th>
                                        {{-- <th class="py-3 px-4 text-left">Progress</th> --}}
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(5)">
                                            Status
                                            <span class="sort-indicator" data-column="5">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @if ($item->is_archived == 0)
                                        <tr class="border-b">
                                            <td class="py-3 px-4">{{ $item->project_number }}</td>
                                            <td class="py-3 px-4">{{ $item->name }}</td>
                                            <td class="py-3 px-4">{{ $item->client->name }}</td>
                                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->start_date)->format('m-d-Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('m-d-Y') }}</td>
                                            {{-- <td class="py-3 px-4">
                                                @php
                                                    $estimated_hours = $item->estimatedtimeEntries->sum('hours') < 1 ? 1 : $item->estimatedtimeEntries->sum('hours');

                                                    $spent_hours = $item->timeEntries->sum('hours') < 1 ? 1 : $item->timeEntries->sum('hours');
                                                    if ($spent_hours == 1) {
                                                        # code...
                                                        $p = 0;
                                                    } else {
                                                        # code...
                                                        $p = ( $spent_hours / $estimated_hours )*100;
                                                        $p = round($p);
                                                    }

                                                @endphp
                                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 rounded-full h-2" style="width: {{ $p }}%"></div>
                                                </div>
                                            </td> --}}
                                            <td class="py-3 px-4">
                                                @php
                                                    $estimated_hours = $item->estimatedtimeEntries->sum('hours') < 1 ? 1 : $item->estimatedtimeEntries->sum('hours');

                                                    $spent_hours = $item->timeEntries->sum('hours') < 1 ? 1 : $item->timeEntries->sum('hours');
                                                    if ($spent_hours == 1) {
                                                        # code...
                                                        $p = 0;
                                                    } else {
                                                        # code...
                                                        $p = ( $spent_hours / $estimated_hours )*100;
                                                        $p = round($p);
                                                    }

                                                @endphp
                                                @if ($p >= 100)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                        Completed
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                        In Progress
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('projects.edits',[$item->id]) }}" class="p-1 hover:bg-gray-100 rounded">
                                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('projects.archive', $item->id) }}" style="display:inline;">
                                                        @csrf
                                                        @method('GET')
                                                        <button type="submit" class="p-1 hover:bg-gray-100 rounded" title="Archive Project" onclick="return confirm('Are you sure you want to archive this project?')">
                                                            <i data-lucide="archive" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
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
                                            Project Number
                                            <span class="sort-indicator" data-column="0">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(1)">
                                            Project
                                            <span class="sort-indicator" data-column="1">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(2)">
                                            Client
                                            <span class="sort-indicator" data-column="2">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(3)">
                                            Timeline
                                            <span class="sort-indicator" data-column="3">▲▼</span>
                                        </th>
                                        {{-- <th class="py-3 px-4 text-left">Progress</th> --}}
                                        <th class="py-3 px-4 text-left cursor-pointer" onclick="sortTable(5)">
                                            Status
                                            <span class="sort-indicator" data-column="5">▲▼</span>
                                        </th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @if ($item->is_archived == 1)
                                        <tr class="border-b">
                                            <td class="py-3 px-4">{{ $item->project_number }}</td>
                                            <td class="py-3 px-4">{{ $item->name }}</td>
                                            <td class="py-3 px-4">{{ $item->client->name }}</td>
                                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->start_date)->format('m-d-Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('m-d-Y') }}</td>
                                            {{-- <td class="py-3 px-4">
                                                @php
                                                    $estimated_hours = $item->estimatedtimeEntries->sum('hours') < 1 ? 1 : $item->estimatedtimeEntries->sum('hours');

                                                    $spent_hours = $item->timeEntries->sum('hours') < 1 ? 1 : $item->timeEntries->sum('hours');

                                                    if ($spent_hours == 1) {
                                                        # code...
                                                        $p = 0;
                                                    } else {
                                                        # code...
                                                        $p = ( $spent_hours / $estimated_hours )*100;
                                                        $p = round($p);
                                                    }
                                                @endphp
                                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 rounded-full h-2" style="width: {{ $p }}%"></div>
                                                </div>
                                            </td> --}}
                                            <td class="py-3 px-4">
                                                 @php
                                                    $estimated_hours = $item->estimatedtimeEntries->sum('hours') < 1 ? 1 : $item->estimatedtimeEntries->sum('hours');

                                                    $spent_hours = $item->timeEntries->sum('hours') < 1 ? 1 : $item->timeEntries->sum('hours');

                                                    if ($spent_hours == 1) {
                                                        # code...
                                                        $p = 0;
                                                    } else {
                                                        # code...
                                                        $p = ( $spent_hours / $estimated_hours )*100;
                                                        $p = round($p);
                                                    }
                                                @endphp
                                                @if ($p >= 100)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                        Completed
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                        In Progress
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('projects.show',[$item->id]) }}" class="p-1 hover:bg-gray-100 rounded">
                                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('projects.archive', $item->id) }}" style="display:inline;">
                                                        @csrf
                                                        @method('GET')
                                                        <button type="submit" class="p-1 hover:bg-gray-100 rounded" title="Unarchive Project" onclick="return confirm('Are you sure you want to unarchive this project?')">
                                                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
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
        }
        // Set default tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            showTab('active');
        });
    </script>
</body>
</html>
