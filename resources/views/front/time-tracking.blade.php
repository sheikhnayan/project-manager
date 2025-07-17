<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Time Tracking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Date-fns -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/date-fns/2.30.0/date-fns.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('/css/styles.css')}}'>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <style>
        table th{
            background: #fff !important;
            border: 1px solid #eee;
        }

        table td{
            border: 1px solid #eee;
        }

        table {
            border: 1px solid #eee !important;
            border-radius: 4px !important; /* Consistent rounded border for the entire table */
            border-collapse: separate; /* Ensures border radius is applied properly */
            width: 100%; /* Ensure the table spans the container */
            overflow: hidden; /* Prevents content from overflowing the rounded corners */
        }

        table th:first-child, /* Target the "Project" column */
        table td:first-child {
            width: 20%; /* Adjust width for "Project" column */
        }

        table th:nth-child(2) {
            width: 20%; /* Adjust width for "Task" column */
        }

        .input-field {
        width: 100%; /* Make the input field span the full width of the <td> */
        height: 40px; /* Make the input field span the full height of the <td> */
        border: 1px solid #ddd; /* Add a border to the input field */
        box-sizing: border-box; /* Ensure padding is included in the width/height */
        background: transparent; /* Keep the background transparent */
        text-align: center; /* Center the text */
        padding: 0; /* Remove padding */
        margin: 0; /* Remove margin */
        border-radius: 4px;
    }

        .editable {
                cursor: pointer;
            }

        .input-field {
            width: 100%;
            height: 36px;
            border: none;
            background: transparent;
            text-align: center;
            padding: 0;
            margin: 0;
            border-radius: 4px;
        }
        .hidden {
            display: none;
        }

        .holiday {
            background-color: #f9fafb; /* Light gray background for holidays */
            color: #d9534f; /* Red text for holidays */
        }
    </style>
</head>
<body class="bg-white text-gray-800">
    @include('front.nav')
    <div class="contain mx-auto p-4" style="border: 1px solid #D1D5DB;
  margin: 16px;
  padding: 0.3rem;
  border-radius: 8px;
  box-shadow:#000;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
        <div class="bg-white rounded-sm overflow-hidden">
            <div class="p-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">Track</h2>
                <div class="flex items-center space-x-2">
                    <button id="prev-week" class="text-gray-600 hover:text-black"><i class="fas fa-chevron-left" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem;"></i></button>
                    <span id="date-range" class="text-gray-600" style="width: 185px; font-size: 0.875rem; line-height: 1.25rem; color: #000;">Mon, 24 Mar - Sun, 30 Mar</span>
                    <button id="next-week" class="text-gray-600 hover:text-black"><i class="fas fa-chevron-right" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem;"></i></button>
                    <button class="text-gray-600 hover:text-black"><i class="fas fa-home" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem;"></i></button>
                    <div class="relative">
                        <select id="user-select"
                            style="width: 200px; border: 1px solid #000; padding:0.5rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; appearance: none;"
                            class="block appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select User</option>
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                            <!-- Add other user options as needed -->
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M7 10l5 5 5-5H7z"/></svg>
                        </div>
                    </div>
                    <div style="border: 1px solid #eee; border-radius: 4px;">
                        <button id="copy-entries" class="text-gray-600 hover:text-black"><i class="fa fa-clone" aria-hidden="true" style="padding:0.6rem 0.8rem; font-size: 0.8rem;"></i></button>
                        <span style='content: ""; height: 24px; width: 1px; background: #eee; display: inline-block; padding-top: 0px; margin-top: 5px; margin-bottom: -5px;'></span>
                        <button id="paste-entries" class="text-gray-600 hover:text-black"><i class="fas fa-paste" style="padding:0.6rem 0.8rem; font-size: 0.8rem;"></i></button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="add-entry" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.5rem 1rem;">+  Add Entry</button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="add-entry" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.5rem 1rem; background: #3B7E28"><img src="{{ asset('Vector.svg') }}" alt="">  Approve</button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto border-b border-gray-200 ">
                <table id="time-table" class="min-w-full bg-white border border-gray-400">
                    <thead>
                        <tr class="w-full bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3" style="padding-left: 1rem">Project</th>
                            <th class="py-3">Task</th>
                            <th class="py-3" style="width: 70px; text-align: center;">Mon</th>
                            <th class="py-3" style="width: 70px; text-align: center;">Tue</th>
                            <th class="py-3" style="width: 70px; text-align: center;">Wed</th>
                            <th class="py-3" style="width: 70px; text-align: center;">Thu</th>
                            <th class="py-3" style="width: 70px; text-align: center;">Fri</th>
                            <th class="py-3 holiday" style="width: 70px; text-align: center;">Sat</th>
                            <th class="py-3 holiday" style="width: 70px; text-align: center;">Sun</th>
                            <th class="py-3" style="width: 80px">Total</th>
                            <th class="py-3" style="width: 100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td class="py-3 px-4 font-semibold" colspan="2">Daily Total:</td>
                            <td class="py-3 px-4 daily-total" data-day="mon" style="width: 118px; text-align: center;"></td>
                            <td class="py-3 px-4 daily-total" data-day="tue" style="width: 118px; text-align: center;"></td>
                            <td class="py-3 px-4 daily-total" data-day="wed" style="width: 118px; text-align: center;"></td>
                            <td class="py-3 px-4 daily-total" data-day="thu" style="width: 118px; text-align: center;"></td>
                            <td class="py-3 px-4 daily-total" data-day="fri" style="width: 118px; text-align: center;"></td>
                            <td class="py-3 px-4 daily-total holiday" data-day="sat" style="width: 118px; text-align: center;"></td>
                            <td class="py-3 px-4 daily-total holiday" data-day="sun" style="width: 118px; text-align: center;"></td>
                            <td class="py-3 px-4"></td>
                            <td class="py-3 px-4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="success-alert" class="fixed bottom-4 right-4 bg-white border border-gray-200 shadow-lg rounded-lg p-4 hidden">
        <span class="text-green-500">Success</span>
        <span class="text-gray-600 alert-message">Entries copied from previous week</span>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateRangeElement = document.getElementById('date-range');
            const userSelect = document.getElementById('user-select');
            const copyEntriesButton = document.getElementById('copy-entries');
            const pasteEntriesButton = document.getElementById('paste-entries');
            const addEntryButton = document.getElementById('add-entry');
            const successAlert = document.getElementById('success-alert');
            const timeTable = document.getElementById('time-table'); // Reference the <table> element
            let currentDate = new Date(); // Initialize with today's date


            // Helper function to get the start of the week (Monday)
            function getStartOfWeek(date) {
                const day = date.getDay(); // Get the current day (0 = Sunday, 1 = Monday, etc.)
                const diff = day === 0 ? -6 : 1 - day; // Adjust to get Monday (start of the week)
                const startOfWeek = new Date(date);
                startOfWeek.setDate(date.getDate() + diff);
                return startOfWeek;
            }

            // Format date range for display
            function formatDateRange(date) {
                const startDate = getStartOfWeek(date); // Get the start of the week
                const endDate = new Date(date);
                endDate.setDate(endDate.getDate() + 6); // Add 6 days to get the end of the week

                const options = { month: 'short', day: 'numeric' };
                return `Mon, ${startDate.toLocaleDateString('en-US', options)} - Sun, ${endDate.toLocaleDateString('en-US', options)}`;
            }

            // Update the date range display
            function updateDateRange() {
                dateRangeElement.textContent = formatDateRange(currentDate);
            }

            async function saveData() {
                const user = userSelect.value;
                const dateRange = dateRangeElement.textContent;

                if (!user) {
                    $('.alert-message').text('Please select a user.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);
                    return;
                }

                const rows = Array.from(timeTable.querySelectorAll('tbody tr'));
                const data = rows.map(row => {
                    return {
                        project: row.querySelector('td:nth-child(1) select').value,
                        task: row.querySelector('td:nth-child(2) select').value,
                        mon: parseTime(row.querySelector('td:nth-child(3) .input-field').value.trim()) || 0,
                        tue: parseTime(row.querySelector('td:nth-child(4) .input-field').value.trim()) || 0,
                        wed: parseTime(row.querySelector('td:nth-child(5) .input-field').value.trim()) || 0,
                        thu: parseTime(row.querySelector('td:nth-child(6) .input-field').value.trim()) || 0,
                        fri: parseTime(row.querySelector('td:nth-child(7) .input-field').value.trim()) || 0,
                        sat: parseTime(row.querySelector('td:nth-child(8) .input-field').value.trim()) || 0,
                        sun: parseTime(row.querySelector('td:nth-child(9) .input-field').value.trim()) || 0,
                    };
                });

                // console.log('Saving data:', { user, dateRange, data });
                // Debug the data being sent

                try {
                    const response = await fetch('/time-tracking/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                        },
                        body: JSON.stringify({ user, dateRange, data }),
                    });

                    if (!response.ok) {
                        throw new Error('Failed to save data');
                    }

                    console.log('Data saved successfully'); // Debug success
                    $('.alert-message').text('Data saved successfully.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);

                } catch (error) {
                    console.error('Error saving data:', error); // Debug errors
                }
            }

            addEntryButton.addEventListener('click', function() {
                if (userSelect.value === "") {
                    $('.alert-message').text('Please select a user before adding an entry.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);
                    return;
                }
                addRow();
            });

            // Load data from the backend
            async function loadData() {
                const user = userSelect.value;
                const dateRange = dateRangeElement.textContent;

                if (!user) {
                    $('.alert-message').text('Please select a user.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);
                    return;
                }

                try {
                    const response = await fetch(`/time-tracking/${user}/${dateRange}`);
                    if (!response.ok) {
                        throw new Error('Failed to fetch data');
                    }
                    const data = await response.json();

                        // Clear the table body (but not the footer)
                        const tbody = timeTable.querySelector('tbody'); // Reference the <tbody> element
                        tbody.innerHTML = '';
                        console.log(data);
                        // Populate the table with data
                        data.entries.forEach(row => {
                            addRow(row.project, row.task, row.mon, row.tue, row.wed, row.thu, row.fri, row.sat, row.sun);
                        });


                } catch (error) {
                    console.error('Error loading data:', error);
                }
            }

            // Add a new row to the table
            function addRow(project = '', task = '', mon = '', tue = '', wed = '', thu = '', fri = '', sat = '', sun = '') {

                const tbody = timeTable.querySelector('tbody'); // Reference the <tbody> element
                const newRow = document.createElement('tr');
                newRow.className = "border-b border-gray-200 hover:bg-gray-100";

                newRow.innerHTML = `
                    <td class="py-3 px-4">
                        <select class="block appearance-none w-full bg-white border border-gray-300 px-4 py-2 rounded project-select">
                            <option value="">Select Project</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <select class="block appearance-none w-full bg-white border border-gray-300 px-4 py-2 rounded task-select">
                            <option value="">Select Task</option>
                        </select>
                    </td>
                    <td class="py-3 px-4 editable" data-day="mon">
                        <input type="text" class="input-field" value="${mon ? formatTime(mon) : ''}" placeholder="0:00">
                    </td>
                    <td class="py-3 px-4 editable" data-day="tue">
                        <input type="text" class="input-field" value="${tue ? formatTime(tue) : ''}" placeholder="0:00">
                    </td>
                    <td class="py-3 px-4 editable" data-day="wed">
                        <input type="text" class="input-field" value="${wed ? formatTime(wed) : ''}" placeholder="0:00">
                    </td>
                    <td class="py-3 px-4 editable" data-day="thu">
                        <input type="text" class="input-field" value="${thu ? formatTime(thu) : ''}" placeholder="0:00">
                    </td>
                    <td class="py-3 px-4 editable" data-day="fri">
                        <input type="text" class="input-field" value="${fri ? formatTime(fri) : ''}" placeholder="0:00">
                    </td>
                    <td class="py-3 px-4 editable holiday" data-day="sat">
                        <input type="text" class="input-field" value="${sat ? formatTime(sat) : ''}" placeholder="0:00">
                    </td>
                    <td class="py-3 px-4 editable holiday" data-day="sun">
                        <input type="text" class="input-field" value="${sun ? formatTime(sun) : ''}" placeholder="0:00">
                    </td>
                    <td class="py-3 px-4 total">0:00</td>
                    <td class="py-3 px-4 delete-row"><i class="fas fa-trash-alt"></i></td>
                `;

                tbody.appendChild(newRow);

                const projectSelect = newRow.querySelector('.project-select');
                const taskSelect = newRow.querySelector('.task-select');

                // Fetch projects for the user and populate the project dropdown
                fetchProjectsForUser(projectSelect, project).then(() => {
                    if (project) {
                        fetchTasksForProject(project, taskSelect, task);
                    }
                });

                // Event listener to fetch tasks when a project is selected
                projectSelect.addEventListener('change', function () {
                    const projectId = this.value;
                    fetchTasksForProject(projectId, taskSelect);
                });

                // Add delete functionality
                newRow.querySelector('.delete-row').addEventListener('click', function () {
                    const row = this.closest('tr');
                    row.remove();
                    updateDailyTotals();
                    saveData(); // Save data after deleting a row
                });

                // Add event listeners to input fields for updating totals
                newRow.querySelectorAll('.input-field').forEach(input => {
                    input.addEventListener('input', function () {
                        updateTotal(newRow); // Update the row total
                        updateDailyTotals(); // Update daily totals
                        saveData(); // Save data after input
                    });

                    // Auto-select all text on focus
                    input.addEventListener('focus', function () {
                        this.select();
                    });

                    // Auto-convert time format on blur
                    input.addEventListener('blur', function () {
                        const value = input.value.trim();
                        if (value) {
                            const decimalHours = parseTime(value);
                            input.value = formatTime(decimalHours); // Convert to "HH:MM" format
                        } else {
                            input.value = '0:00'; // Default to "0:00" if the input is empty
                        }
                    });
                });

                updateTotal(newRow);
                updateDailyTotals();
            }

            function fetchProjectsForUser(projectSelect, selectedProject = '') {
                const userId = document.getElementById('user-select').value;

                if (!userId) {
                    return Promise.resolve(); // Return an empty promise if no user is selected
                }

                return fetch(`/user/${userId}/projects`)
                    .then(response => response.json())
                    .then(projects => {
                        projectSelect.innerHTML = '<option value="">Select Project</option>';
                        projects.forEach(project => {
                            const option = document.createElement('option');
                            option.value = project.id;
                            option.textContent = project.name;
                            if (project.id === selectedProject) {
                                option.selected = true; // Pre-select the project
                            }
                            projectSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching projects:', error);
                    });
            }

            function fetchTasksForProject(projectId, taskSelect, selectedTask = '') {
                if (!projectId) {
                    taskSelect.innerHTML = '<option value="">Select Task</option>';
                    return;
                }

                fetch(`/project/${projectId}/tasks`) // Ensure projectId is passed here
                    .then(response => response.json())
                    .then(tasks => {
                        taskSelect.innerHTML = '<option value="">Select Task</option>';
                        tasks.forEach(task => {
                            const option = document.createElement('option');
                            option.value = task.id;
                            option.textContent = task.name;
                            if (task.id === selectedTask) {
                                option.selected = true; // Pre-select the task
                            }
                            taskSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching tasks:', error);
                    });
            }

            // Convert decimal hours to "HH:MM" format
            function formatTime(decimalHours) {
                // console.log('Formatting time:', decimalHours);
                // Debug input
                if (isNaN(decimalHours) || decimalHours < 0) {
                    // console.log('Invalid decimal hours, returning "0:00"');
                    return '0:00';
                }

                const hours = Math.floor(decimalHours);
                const minutes = Math.round((decimalHours - hours) * 60);

                return `${hours}:${minutes.toString().padStart(2, '0')}`;
            }

            // Convert "HH:MM" format to decimal hours
            function parseTime(timeString) {
                if (!timeString) return 0;

                // Replace comma with dot for decimal conversion
                timeString = timeString.replace(',', '.');

                // If it's a decimal number (e.g., "5.2" or "5,2")
                if (!timeString.includes(':') && !isNaN(timeString)) {
                    return parseFloat(timeString);
                }

                // If it's in HH:MM format
                if (timeString.includes(':')) {
                    const [hours, minutes] = timeString.split(':').map(Number);
                    if (isNaN(hours) || isNaN(minutes) || hours < 0 || minutes < 0 || minutes >= 60) {
                        return 0;
                    }
                    return hours + (minutes / 60);
                }

                return 0;
            }

            // Update the total for a row
            function updateTotal(row) {
                const inputs = row.querySelectorAll('.input-field');
                let total = 0;

                inputs.forEach(input => {
                    const value = input.value.trim();

                    if (!value) {
                        return; // Skip empty inputs
                    }
                    total += parseTime(value); // Convert "HH:MM" to decimal hours
                });


                row.querySelector('.total').textContent = formatTime(total); // Display total in "HH:MM" format
            }

            function updateDailyTotals() {
                const days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                days.forEach(day => {
                    let dailyTotal = 0;
                    document.querySelectorAll(`.editable[data-day="${day}"] .input-field`).forEach(input => {
                        const value = input.value.trim();
                        if (!value) {
                            return; // Skip empty inputs
                        }
                        dailyTotal += parseTime(value); // Convert "HH:MM" to decimal hours
                    });
                    const dailyTotalCell = document.querySelector(`.daily-total[data-day="${day}"]`);
                    if (dailyTotalCell) {
                        dailyTotalCell.textContent = formatTime(dailyTotal); // Display total in "HH:MM" format
                    }
                });
            }

            // Navigate to the previous week
            document.getElementById('prev-week').addEventListener('click', function () {
                currentDate = getStartOfWeek(currentDate); // Align currentDate to the start of the week
                currentDate.setDate(currentDate.getDate() - 7); // Go back 7 days
                updateDateRange();
                loadData();
            });

            // Navigate to the next week
            document.getElementById('next-week').addEventListener('click', function () {
                currentDate = getStartOfWeek(currentDate); // Align currentDate to the start of the week
                currentDate.setDate(currentDate.getDate() + 7); // Go forward 7 days
                updateDateRange();
                loadData();
            });

            // Automatically select the first user and load its data
            function initializeFirstUser() {
                const firstUserOption = userSelect.options[1]; // Skip the placeholder option
                if (firstUserOption) {
                    userSelect.value = firstUserOption.value;
                    updateDateRange();
                    loadData();
                }
            }


            copyEntriesButton.addEventListener('click', function() {
                copiedRows = [];
                const rows = document.querySelectorAll('#time-table tbody tr:not(:last-child)');
                rows.forEach(row => {
                    const project = row.querySelector('td:nth-child(1) select').value;
                    const task = row.querySelector('td:nth-child(2) select').value;
                    const mon = row.querySelector('td:nth-child(3)').textContent.trim();
                    const tue = row.querySelector('td:nth-child(4)').textContent.trim();
                    const wed = row.querySelector('td:nth-child(5)').textContent.trim();
                    const thu = row.querySelector('td:nth-child(6)').textContent.trim();
                    const fri = row.querySelector('td:nth-child(7)').textContent.trim();
                    const sat = row.querySelector('td:nth-child(8)').textContent.trim();
                    const sun = row.querySelector('td:nth-child(9)').textContent.trim();
                    copiedRows.push({ project, task, mon, tue, wed, thu, fri, sat, sun });
                });
                successAlert.classList.remove('hidden');
                setTimeout(() => {
                    successAlert.classList.add('hidden');
                }, 3000);
            });

            window.onload = function () {
                // console.log("Page fully loaded");
                addRow();
            };


            pasteEntriesButton.addEventListener('click', function() {
                if (copiedRows.length === 0) {
                    $('.alert-message').text('No entries to paste. Please copy entries first.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);
                    return;
                }

                // Clear the table body before pasting new rows
                const tbody = timeTable.querySelector('tbody');
                tbody.innerHTML = '';

                // Add copied rows and clear input fields
                copiedRows.forEach(rowData => {
                    addRow(rowData.project, rowData.task, '', '', '', '', '', '', ''); // Clear all day inputs
                });

                // Save the data after pasting
                saveData();
            });

            // Event listeners
            userSelect.addEventListener('change', function () {
                loadData();
            });

            initializeFirstUser(); // Automatically select the first user
        });
    </script>

<script src="{{asset('/js/app.js')}}"></script>

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
