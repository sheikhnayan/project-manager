<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Time Tracking</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='2' y='6' fill='%23000'/><rect width='24' height='2' y='11' fill='%23000'/><rect width='24' height='2' y='16' fill='%23000'/></svg>">
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
        /* Fix border-top for Edge browser compatibility */
        table thead th {
            border-top: unset !important;
            border-left: 1px solid #eee !important;
            border-right: 1px solid #eee !important;
            border-bottom: 1px solid #eee !important;
        }

        table th{
            background: #f9fafb !important;
            border: 1px solid #eee;
            border-top: 1px solid #eee !important;
            padding: 17px 10px;
        }

        table td{
            border: 1px solid #eee;
            padding: 6px 10px;
        }

        table th{
            border: 1px solid #eee;
        }

        table {
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            overflow: visible;
            box-shadow: 0 0 0 1px #D1D5DB;
        }
        
        /* Additional Edge browser fix - ensure first row shows top border */
        table thead tr:first-child th {
            border-top: unset !important;
        }
        
        /* Wrapper to handle border-radius clipping */
        .overflow-x-auto {
            border-radius: 4px;
            overflow: hidden;
        }

        table th:first-child,
        table td:first-child {
            width: 20%;
        }

        table th:nth-child(2) {
            width: 20%;
        }

        .input-field {
            width: 100%;
            height: 40px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            background: transparent;
            text-align: center;
            padding: 0;
            margin: 0;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .input-field::-webkit-outer-spin-button,
        .input-field::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .editable {
                cursor: pointer;
            }
        .hidden {
            display: none;
        }

        .holiday {
            background-color: #f9fafb; /* Light gray background for holidays */
            color: #d9534f; /* Red text for holidays */
        }

        /* Style for client group headers in project dropdown */
        .project-select optgroup,
        .task-select optgroup {
            font-weight: 600 !important;
            color: #000 !important;
            background-color: #fff !important;
            padding: 8px 12px !important;
            font-size: 13px !important;
        }

        .project-select option,
        .task-select option {
            font-weight: normal !important;
            color: #374151 !important;
            padding: 8px 12px 8px 24px !important;
            background-color: white !important;
            font-size: 13px !important;
        }

        .delete-row{
            display: block;
            border: unset;
            text-align: center;
            margin-top: 13px;
        }

        .input-field{
            height: 35px;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('front.nav')
    <div class="mx-auto shadow border rounded-lg overflow-hidden" style="height: 100vh;">
        <div class="p-4 rounded-lg" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); background: #fff !important;">
            <div class="bg-white rounded-sm overflow-hidden" style="background: transparent">
                <div class="p-2 flex items-center justify-between" style="padding-left: 0px;"> 
                    <h2 style="font-size: 20px; font-weight: 600;">Track</h2>
                <div class="flex items-center space-x-2">
                    <button id="prev-week" class="text-gray-600 hover:text-black"><i class="fas fa-chevron-left" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; color: #000;"></i></button>
                    <span id="date-range" class="text-gray-600" style="width: 185px; font-size: 0.875rem; line-height: 1.25rem; color: #000;">Mon, 24 Mar - Sun, 30 Mar</span>
                    <button id="next-week" class="text-gray-600 hover:text-black"><i class="fas fa-chevron-right" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; color: #000;"></i></button>
                    <button id="home-button" class="text-gray-600 hover:text-black"><i class="fas fa-home" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; color: #000;"></i></button>
                    <div class="relative">
                        <select id="user-select"
                            style="width: 200px; border: 1px solid #000; padding:0.5rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; appearance: none;"
                            class="block appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select User</option>
                            @foreach ($users as $item)
                                @if ($item->is_archived == 0)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endif
                            @endforeach
                            <!-- Add other user options as needed -->
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M7 10l5 5 5-5H7z"/></svg>
                        </div>
                    </div>
                    <div style="border: 1px solid #eee; border-radius: 4px;">
                        <button id="copy-entries" class="text-gray-600 hover:text-black"><i class="fa fa-clone" aria-hidden="true" style="padding:0.6rem 0.8rem; font-size: 0.8rem; color: #000;"></i></button>
                        <span style='content: ""; height: 24px; width: 1px; background: #eee; display: inline-block; padding-top: 0px; margin-top: 5px; margin-bottom: -5px;'></span>
                        <button id="paste-entries" class="text-gray-600 hover:text-black"><i class="fas fa-paste" style="padding:0.6rem 0.8rem; font-size: 0.8rem; color: #000;"></i></button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="add-entry" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.5rem 1rem;">+  Add Entry</button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="approve-entries" class="bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400 disabled:cursor-not-allowed" style="font-size: 13px; padding:0.5rem 1rem;" disabled><img src="{{ asset('Vector.svg') }}" alt="" style="display: inline; margin-right: 4px;">  Approve</button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto border-b border-gray-200">
                <table id="time-table" class="min-w-full bg-white" style="border: 1px solid #D1D5DB; border-radius: 4px;">
                    <thead style="height: 52px;">
                        <tr class="w-full bg-gray-100 text-left text-gray-600 leading-normal" style="font-size: 14px;">
                            <th class="py-1" style="background: #000 !important; color: #fff;padding-left: 1rem; font-size: 14px; font-weight: 600;">Type</th>
                            <th class="py-1" style="background: #000 !important; color: #fff;padding-left: 1rem; font-size: 14px; font-weight: 600;">Project/Task</th>
                            <th class="py-1" style="width: 70px; text-align: center; font-size: 14px; font-weight: 600; color: #000">Monday</th>
                            <th class="py-1" style="width: 70px; text-align: center; font-size: 14px; font-weight: 600; color: #000">Tuesday</th>
                            <th class="py-1" style="width: 70px; text-align: center; font-size: 14px; font-weight: 600; color: #000">Wednesday</th>
                            <th class="py-1" style="width: 70px; text-align: center; font-size: 14px; font-weight: 600; color: #000">Thursday</th>
                            <th class="py-1" style="width: 70px; text-align: center; font-size: 14px; font-weight: 600; color: #000">Friday</th>
                            <th class="py-1 holiday" style="width: 70px; text-align: center; font-size: 14px; font-weight: 600;">Saturday</th>
                            <th class="py-1 holiday" style="width: 70px; text-align: center; font-size: 14px; font-weight: 600;">Sunday</th>
                            <th class="py-1" style="width: 80px; text-align: center; font-size: 14px; font-weight: 600; color: #000">Total</th>
                            <th class="py-1" style="width: 100px; text-align: center; font-size: 14px; font-weight: 600; color: #000">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 font-light" style="font-size: 14px;">
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td class="px-4 font-semibold" colspan="2" style="font-size: 14px; padding: 10px 16px;">Daily Total:</td>
                            <td class="px-4 daily-total" data-day="mon" style="width: 118px; text-align: center; font-size: 14px; padding: 10px;"></td>
                            <td class="px-4 daily-total" data-day="tue" style="width: 118px; text-align: center; font-size: 14px; padding: 10px;"></td>
                            <td class="px-4 daily-total" data-day="wed" style="width: 118px; text-align: center; font-size: 14px; padding: 10px;"></td>
                            <td class="px-4 daily-total" data-day="thu" style="width: 118px; text-align: center; font-size: 14px; padding: 10px;"></td>
                            <td class="px-4 daily-total" data-day="fri" style="width: 118px; text-align: center; font-size: 14px; padding: 10px;"></td>
                            <td class="px-4 daily-total holiday" data-day="sat" style="width: 118px; text-align: center; font-size: 14px; padding: 10px;"></td>
                            <td class="px-4 daily-total holiday" data-day="sun" style="width: 118px; text-align: center; font-size: 14px; padding: 10px;"></td>
                            <td class="px-4 font-semibold total-total" style="width: 80px; text-align: center; font-size: 14px; padding: 10px;">0:00</td>
                            <td class="px-4" style="padding: 10px;"></td>
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
            const approveEntriesButton = document.getElementById('approve-entries');
            const successAlert = document.getElementById('success-alert');
            const timeTable = document.getElementById('time-table'); // Reference the <table> element
            let currentDate = new Date(); // Initialize with today's date
            let isCurrentWeekApproved = false; // Track if current week is approved
            let copiedRows = []; // Array to store copied rows


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
                updateApproveButtonState();
                checkApprovalStatus();
            }

            // Check if the selected week is in the past or current week and update approve button state
            function updateApproveButtonState() {
                const selectedWeekStart = getStartOfWeek(currentDate);
                const currentWeekStart = getStartOfWeek(new Date());
                
                // If already approved, disable the button regardless of date
                if (isCurrentWeekApproved) {
                    approveEntriesButton.disabled = true;
                    approveEntriesButton.classList.remove('bg-green-600');
                    approveEntriesButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                    approveEntriesButton.innerHTML = '<img src="{{ asset("Vector.svg") }}" alt="" style="display: inline; margin-right: 4px;">  Approved';
                    return;
                }
                
                // Enable approve button for past weeks AND current week (disable only future weeks)
                if (selectedWeekStart <= currentWeekStart) {
                    approveEntriesButton.disabled = false;
                    approveEntriesButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    approveEntriesButton.classList.add('bg-green-600');
                    approveEntriesButton.innerHTML = '<img src="{{ asset("Vector.svg") }}" alt="" style="display: inline; margin-right: 4px;">  Approve';
                } else {
                    approveEntriesButton.disabled = true;
                    approveEntriesButton.classList.remove('bg-green-600');
                    approveEntriesButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                    approveEntriesButton.innerHTML = '<img src="{{ asset("Vector.svg") }}" alt="" style="display: inline; margin-right: 4px;">  Approve';
                }
            }

            // Check approval status for current week and user
            async function checkApprovalStatus() {
                const user = userSelect.value;
                if (!user) return;

                const selectedWeekStart = getStartOfWeek(currentDate);
                const weekDates = [];
                
                // Generate all 7 days of the week (Monday to Sunday)
                for (let i = 0; i < 7; i++) {
                    const date = new Date(selectedWeekStart);
                    date.setDate(selectedWeekStart.getDate() + i);
                    weekDates.push(date.toISOString().split('T')[0]);
                }

                try {
                    const params = new URLSearchParams({
                        user_id: user,
                        dates: weekDates.join(',')
                    });
                    
                    const response = await fetch(`/time-tracking/approval-status?${params.toString()}`);
                    if (response.ok) {
                        const data = await response.json();
                        isCurrentWeekApproved = data.is_approved || false;
                        updateApproveButtonState();
                        updateFormEditability();
                    }
                } catch (error) {
                    console.error('Error checking approval status:', error);
                }
            }

            // Update form editability based on approval status
            function updateFormEditability() {
                const inputFields = document.querySelectorAll('.input-field');
                const projectSelects = document.querySelectorAll('.project-select');
                const taskSelects = document.querySelectorAll('.task-select');
                const deleteButtons = document.querySelectorAll('.delete-row');
                const addButton = document.getElementById('add-entry');

                if (isCurrentWeekApproved) {
                    // Disable all inputs if approved
                    inputFields.forEach(input => {
                        input.disabled = true;
                        input.style.backgroundColor = '#f9fafb';
                        input.style.cursor = 'not-allowed';
                    });
                    
                    projectSelects.forEach(select => {
                        select.disabled = true;
                        select.style.backgroundColor = '#f9fafb';
                        select.style.cursor = 'not-allowed';
                    });
                    
                    taskSelects.forEach(select => {
                        select.disabled = true;
                        select.style.backgroundColor = '#f9fafb';
                        select.style.cursor = 'not-allowed';
                    });
                    
                    deleteButtons.forEach(button => {
                        button.style.display = 'none';
                    });
                    
                    addButton.disabled = true;
                    addButton.style.backgroundColor = '#9ca3af';
                    addButton.style.cursor = 'not-allowed';
                } else {
                    // Enable all inputs if not approved
                    inputFields.forEach(input => {
                        input.disabled = false;
                        input.style.backgroundColor = 'transparent';
                        input.style.cursor = 'text';
                    });
                    
                    projectSelects.forEach(select => {
                        select.disabled = false;
                        select.style.backgroundColor = 'white';
                        select.style.cursor = 'pointer';
                    });
                    
                    taskSelects.forEach(select => {
                        select.disabled = false;
                        select.style.backgroundColor = 'white';
                        select.style.cursor = 'pointer';
                    });
                    
                    deleteButtons.forEach(button => {
                        button.style.display = 'block';
                    });
                    
                    addButton.disabled = false;
                    addButton.style.backgroundColor = '#000';
                    addButton.style.cursor = 'pointer';
                }
            }

            // Function to validate task selection and disable/enable time inputs
            function validateTaskSelection(row) {
                const projectSelect = row.querySelector('.project-select');
                const taskSelect = row.querySelector('.task-select');
                const timeInputs = row.querySelectorAll('.input-field');
                
                let hasValidSelection = false;
                
                // Check if a valid project/task combination is selected
                if (projectSelect && projectSelect.value) {
                    if (projectSelect.value.startsWith('internal_')) {
                        // Internal task selected - always valid since no sub-tasks
                        hasValidSelection = true;
                    } else {
                        // Regular project selected - need task selection too
                        hasValidSelection = taskSelect && taskSelect.value;
                    }
                }
                
                if (!hasValidSelection) {
                    // No task selected - disable all time inputs but preserve existing values
                    timeInputs.forEach(input => {
                        input.disabled = true;
                        input.style.backgroundColor = '#f9fafb';
                        input.style.cursor = 'not-allowed';
                        // Don't clear existing values and don't change placeholder
                    });
                } else {
                    // Task selected - enable time inputs (unless week is approved)
                    if (!isCurrentWeekApproved) {
                        timeInputs.forEach(input => {
                            input.disabled = false;
                            input.style.backgroundColor = 'transparent';
                            input.style.cursor = 'text';
                        });
                    }
                }
                
                // Update totals after validation
                updateTotal(row);
                updateDailyTotals();
            }

            async function saveData() {
                // Prevent saving if the week is already approved
                if (isCurrentWeekApproved) {
                    $('.alert-message').text('Cannot save changes to approved entries.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);
                    return;
                }

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
                    const projectSelect = row.querySelector('.project-select');
                    const taskSelect = row.querySelector('.task-select');
                    
                    let taskType = 'project';
                    let projectId = null;
                    let taskId = null;
                    
                    if (projectSelect && projectSelect.value) {
                        if (projectSelect.value.startsWith('internal_dept_')) {
                            // Department selected - check if task is also selected
                            if (!taskSelect || !taskSelect.value || taskSelect.value === '') {
                                console.warn('Department selected without task - skipping');
                                return null;
                            }
                            // Internal task selected from department
                            taskType = 'internal';
                            taskId = taskSelect.value.replace('internal_', '');
                            projectId = null;
                        } else if (projectSelect.value.startsWith('internal_')) {
                            // Internal task selected directly (old format, probably not used)
                            taskType = 'internal';
                            taskId = projectSelect.value.replace('internal_', '');
                            projectId = null;
                        } else {
                            // Regular project selected
                            taskType = 'project';
                            projectId = projectSelect.value;
                            taskId = taskSelect ? taskSelect.value : null;
                        }
                    }
                    
                    return {
                        task_type: taskType,
                        project: projectId,
                        task: taskId,
                        mon: parseTime(row.querySelector('td:nth-child(3) .input-field').value.trim()) || 0,
                        tue: parseTime(row.querySelector('td:nth-child(4) .input-field').value.trim()) || 0,
                        wed: parseTime(row.querySelector('td:nth-child(5) .input-field').value.trim()) || 0,
                        thu: parseTime(row.querySelector('td:nth-child(6) .input-field').value.trim()) || 0,
                        fri: parseTime(row.querySelector('td:nth-child(7) .input-field').value.trim()) || 0,
                        sat: parseTime(row.querySelector('td:nth-child(8) .input-field').value.trim()) || 0,
                        sun: parseTime(row.querySelector('td:nth-child(9) .input-field').value.trim()) || 0,
                    };
                }).filter(entry => entry !== null); // Filter out null entries

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

                    const result = await response.json();

                    if (!response.ok) {
                        const errorMsg = result.error || result.message || 'Failed to save data';
                        throw new Error(errorMsg);
                    }

                    console.log('Data saved successfully'); // Debug success
                    $('.alert-message').text('Data saved successfully.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);

                } catch (error) {
                    console.error('Error saving data:', error); // Debug errors
                    alert('Error: ' + error.message);
                }

                // Always ensure there's an empty row after saving
                ensureEmptyRow();
            }

            // Function to ensure there's always at least one empty row
            function ensureEmptyRow() {
                console.log('ensureEmptyRow called'); // Debug log
                
                const tbody = timeTable.querySelector('tbody');
                const rows = tbody.querySelectorAll('tr');
                
                console.log('Number of rows:', rows.length); // Debug log
                
                // Always add an empty row if no rows exist
                if (rows.length === 0) {
                    console.log('No rows exist, adding empty row'); // Debug log
                    addRow();
                    return;
                }
                
                // Check if the last row is empty (no project and task selected, and no time entries)
                let hasEmptyRow = false;
                const lastRow = rows[rows.length - 1];
                const projectSelect = lastRow.querySelector('.project-select');
                const taskSelect = lastRow.querySelector('.task-select');
                const inputFields = lastRow.querySelectorAll('.input-field');
                
                console.log('Checking last row - project:', projectSelect?.value, 'task:', taskSelect?.value); // Debug log
                
                // Check if all fields are empty
                const isEmptyRow = (!projectSelect || !projectSelect.value) && 
                                  (!taskSelect || !taskSelect.value) && 
                                  Array.from(inputFields).every(input => !input.value || input.value.trim() === '' || input.value === '0:00');
                
                if (isEmptyRow) {
                    hasEmptyRow = true;
                    console.log('Empty row already exists'); // Debug log
                }
                
                // If no empty row exists, add one
                if (!hasEmptyRow) {
                    console.log('Adding empty row'); // Debug log
                    addRow();
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
                    
                    // Clear table and ensure empty row even when no user is selected
                    const tbody = timeTable.querySelector('tbody');
                    tbody.innerHTML = '';
                    setTimeout(() => {
                        ensureEmptyRow();
                    }, 100); // Small delay to ensure DOM is ready
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
                            const taskType = row.task_type || 'project';
                            console.log('Loading row:', row, 'taskType:', taskType);
                            if (taskType === 'internal') {
                                // Pass department_id and task_id for internal tasks
                                console.log('Loading internal task - dept:', row.department_id, 'task:', row.task);
                                addRow(row.department_id, row.task, row.mon || '', row.tue || '', row.wed || '', row.thu || '', row.fri || '', row.sat || '', row.sun || '', 'internal');
                            } else {
                                // Only add project tasks that have a valid project
                                if (row.project) {
                                    console.log('Loading project task - project:', row.project, 'task:', row.task);
                                    addRow(row.project, row.task, row.mon || '', row.tue || '', row.wed || '', row.thu || '', row.fri || '', row.sat || '', row.sun || '', 'project');
                                }
                            }
                        });

                        // Always ensure there's one empty row after loading data
                        setTimeout(() => {
                            ensureEmptyRow();
                        }, 100); // Small delay to ensure DOM is fully updated

                        // Check approval status after loading data
                        await checkApprovalStatus();

                } catch (error) {
                    console.error('Error loading data:', error);
                }
            }

            // Add a new row to the table with async project/task handling


            // Add a new row to the table
            function addRow(project = '', task = '', mon = '', tue = '', wed = '', thu = '', fri = '', sat = '', sun = '', taskType = 'project') {

                const tbody = timeTable.querySelector('tbody'); // Reference the <tbody> element
                const newRow = document.createElement('tr');
                newRow.className = "border-b border-gray-200 hover:bg-gray-100";

                // Convert internal task format for loading existing data
                let projectValue = project ? String(project) : ''; // Ensure it's a string
                if (taskType === 'internal' && project) {
                    // For internal tasks, project is the department_id
                    projectValue = 'internal_dept_' + project;
                }

                newRow.innerHTML = `
                    <td class="px-4">
                        <select class="block appearance-none w-full bg-white border border-gray-300 px-4 py-2 rounded project-select">
                            <option value="">Select Project</option>
                        </select>
                    </td>
                    <td class="px-4">
                        <select class="block appearance-none w-full bg-white border border-gray-300 px-4 py-2 rounded task-select">
                            <option value="">Select Task</option>
                        </select>
                    </td>
                    <td class="px-4 editable" data-day="mon">
                        <input type="text" class="input-field" value="${mon ? formatTime(mon) : ''}" placeholder="0:00">
                    </td>
                    <td class="px-4 editable" data-day="tue">
                        <input type="text" class="input-field" value="${tue ? formatTime(tue) : ''}" placeholder="0:00">
                    </td>
                    <td class="px-4 editable" data-day="wed">
                        <input type="text" class="input-field" value="${wed ? formatTime(wed) : ''}" placeholder="0:00">
                    </td>
                    <td class="px-4 editable" data-day="thu">
                        <input type="text" class="input-field" value="${thu ? formatTime(thu) : ''}" placeholder="0:00">
                    </td>
                    <td class="px-4 editable" data-day="fri">
                        <input type="text" class="input-field" value="${fri ? formatTime(fri) : ''}" placeholder="0:00">
                    </td>
                    <td class="px-4 editable holiday" data-day="sat">
                        <input type="text" class="input-field" value="${sat ? formatTime(sat) : ''}" placeholder="0:00">
                    </td>
                    <td class="px-4 editable holiday" data-day="sun">
                        <input type="text" class="input-field" value="${sun ? formatTime(sun) : ''}" placeholder="0:00">
                    </td>
                    <td class="px-4 total" style="text-align: center;">0:00</td>
                    <td class="px-4 delete-row"><i class="fas fa-trash-alt"></i></td>
                `;

                tbody.appendChild(newRow);

                const projectSelect = newRow.querySelector('.project-select');
                const taskSelect = newRow.querySelector('.task-select');

                // Fetch projects (including internal tasks) and set selections
                fetchProjectsForUser(projectSelect, projectValue).then(() => {
                    if (projectValue) {
                        if (projectValue.startsWith('internal_dept_')) {
                            // Handle internal task - load department tasks and select the specific task
                            const taskValue = task ? 'internal_' + task : '';
                            fetchTasksForProject(projectValue, taskSelect, taskValue);
                        } else {
                            // Handle regular project
                            fetchTasksForProject(projectValue, taskSelect, task);
                        }
                    }
                });

                // Event listener to fetch tasks when a project is selected
                projectSelect.addEventListener('change', function () {
                    const selectedValue = this.value;
                    
                    if (selectedValue.startsWith('internal_dept_')) {
                        // Handle department selection - fetch internal tasks for this department
                        taskSelect.disabled = false;
                        taskSelect.style.backgroundColor = 'white';
                        fetchTasksForProject(selectedValue, taskSelect);
                        taskSelect.value = '';
                    } else if (selectedValue.startsWith('internal_')) {
                        // Handle old internal task selection format (if any)
                        taskSelect.innerHTML = '<option value="internal">Internal Task</option>';
                        taskSelect.value = 'internal';
                        taskSelect.disabled = true;
                        taskSelect.style.backgroundColor = '#f3f4f6';
                    } else if (selectedValue) {
                        // Handle regular project selection
                        taskSelect.disabled = false;
                        taskSelect.style.backgroundColor = 'white';
                        fetchTasksForProject(selectedValue, taskSelect);
                        taskSelect.value = '';
                    } else {
                        // No selection
                        taskSelect.innerHTML = '<option value="">Select Project First</option>';
                        taskSelect.disabled = true;
                    }
                    
                    validateTaskSelection(newRow);
                });

                // Event listener for task selection
                taskSelect.addEventListener('change', function () {
                    validateTaskSelection(newRow);
                });

                // Initial validation
                validateTaskSelection(newRow);

                // Add delete functionality
                newRow.querySelector('.delete-row').addEventListener('click', function () {
                    const row = this.closest('tr');
                    row.remove();
                    updateDailyTotals();
                    saveData(); // Save data after deleting a row
                    
                    // Ensure there's always an empty row after deletion
                    ensureEmptyRow();
                });

                // Add event listeners to input fields for updating totals
                newRow.querySelectorAll('.input-field').forEach(input => {
                    input.addEventListener('input', function () {
                        // Check if task is selected before allowing new input (but allow existing values to remain)
                        const taskSelect = this.closest('tr').querySelector('.task-select');
                        if (!taskSelect.value && this.value !== this.dataset.originalValue) {
                            // Only prevent new input, don't clear existing values
                            this.value = this.dataset.originalValue || '';
                            return;
                        }
                        
                        let val = this.value.trim().replace(',', '.');
                        
                        // Validate max 24 hours
                        const parsedValue = parseTime(val);
                        if (parsedValue > 24) {
                            $('.alert-message').text('Time entry cannot exceed 24 hours per day.');
                            successAlert.classList.remove('hidden');
                            setTimeout(() => {
                                successAlert.classList.add('hidden');
                            }, 3000);
                            this.value = this.dataset.lastValid || '';
                            updateTotal(newRow);
                            updateDailyTotals();
                            return;
                        }
                        
                        // Allow empty or HH:MM format
                        if (val === '' || val.includes(':')) {
                            this.dataset.lastValid = val;
                            updateTotal(newRow);
                            updateDailyTotals();
                            saveData();
                            return;
                        }
                        // Allow whole numbers, .5, numbers ending in .5, and a trailing dot
                        if (/^(\d+(\.5)?|\.5|\d+\.)$/.test(val)) {
                            this.dataset.lastValid = val;
                        } else {
                            // If invalid, revert to previous valid value
        this.value = this.dataset.lastValid || '';
        return;
    }
    updateTotal(newRow);
    updateDailyTotals();
    saveData();
});


                    // Auto-select all text on focus
                    input.addEventListener('focus', function () {
                        // Store original value for comparison
                        this.dataset.originalValue = this.value;
                        
                        // Check if task is selected before allowing focus
                        const taskSelect = this.closest('tr').querySelector('.task-select');
                        if (!taskSelect.value) {
                            this.blur();
                            return;
                        }
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
                    return Promise.resolve();
                }

                // Fetch both regular projects and departments
                const projectsPromise = fetch(`/user/${userId}/projects`).then(response => response.json());
                const departmentsPromise = fetch(`/user/${userId}/departments`).then(response => response.json());

                return Promise.all([projectsPromise, departmentsPromise])
                    .then(([projects, departmentsData]) => {
                        console.log('Projects data:', projects);
                        console.log('Departments data:', departmentsData);
                        
                        projectSelect.innerHTML = '<option value="">Select Project</option>';

                        // Add Internal Tasks / Departments with Company Name
                        if (departmentsData.departments && departmentsData.departments.length > 0) {
                            const internalOptgroup = document.createElement('optgroup');
                            // Use company name if available, otherwise use default label
                            const companyName = departmentsData.company_name || 'Internal Tasks';
                            internalOptgroup.label = `${companyName}`;
                            internalOptgroup.style.fontWeight = '600';
                            internalOptgroup.style.backgroundColor = '#f3f4f6';
                            internalOptgroup.style.padding = '20px 10px 10px';
                            
                            // Sort departments alphabetically
                            const sortedDepartments = departmentsData.departments.sort((a, b) => 
                                a.name.localeCompare(b.name)
                            );
                            
                            sortedDepartments.forEach(dept => {
                                const option = document.createElement('option');
                                option.value = `internal_dept_${dept.id}`;
                                option.textContent = `${dept.name}`;
                                option.setAttribute('data-type', 'internal');
                                option.setAttribute('data-department-id', dept.id);
                                if (option.value === selectedProject) {
                                    option.selected = true;
                                }
                                internalOptgroup.appendChild(option);
                            });
                            
                            projectSelect.appendChild(internalOptgroup);
                        }

                        // Add projects grouped by client
                        if (projects && projects.length > 0) {
                            // Group projects by client
                            const projectsByClient = {};
                            projects.forEach(project => {
                                const clientName = project.client ? project.client.name : 'No Client';
                                if (!projectsByClient[clientName]) {
                                    projectsByClient[clientName] = [];
                                }
                                projectsByClient[clientName].push(project);
                            });

                            // Sort clients alphabetically
                            const sortedClients = Object.keys(projectsByClient).sort();

                            // Add projects grouped by client
                            sortedClients.forEach(clientName => {
                                const optgroup = document.createElement('optgroup');
                                optgroup.label = `👥 ${clientName}`;
                                
                                // Sort projects within each client
                                const clientProjects = projectsByClient[clientName].sort((a, b) => 
                                    a.project_number.localeCompare(b.project_number)
                                );
                                
                                clientProjects.forEach(project => {
                                    const option = document.createElement('option');
                                    option.value = project.id;
                                    option.textContent = project.project_number + '_' + project.name;
                                    option.setAttribute('data-type', 'project');
                                    if (project.id == selectedProject) {
                                        option.selected = true;
                                    }
                                    optgroup.appendChild(option);
                                });
                                
                                projectSelect.appendChild(optgroup);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching projects or departments:', error);
                    });
            }

            function fetchTasksForProject(projectId, taskSelect, selectedTask = '') {
                if (!projectId) {
                    taskSelect.innerHTML = '<option value="">Select Project/Department First</option>';
                    return Promise.resolve();
                }

                // Check if this is a department selection
                if (projectId.startsWith('internal_dept_')) {
                    const departmentId = projectId.replace('internal_dept_', '');
                    const userId = document.getElementById('user-select').value;
                    return fetch(`/time-tracking/departments/${departmentId}/tasks?user_id=${userId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(tasks => {
                            taskSelect.innerHTML = '<option value="">Select Task</option>';
                            if (Array.isArray(tasks) && tasks.length > 0) {
                                tasks.forEach(task => {
                                    const option = document.createElement('option');
                                    option.value = 'internal_' + task.id;
                                    option.textContent = task.name;
                                    if (option.value === selectedTask) {
                                        option.selected = true;
                                    }
                                    taskSelect.appendChild(option);
                                });
                            } else {
                                taskSelect.innerHTML = '<option value="">No tasks available</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching department tasks:', error);
                            taskSelect.innerHTML = '<option value="">Error loading tasks</option>';
                        });
                }

                // Handle regular project tasks
                return fetch(`/project/${projectId}/tasks`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(tasks => {
                        taskSelect.innerHTML = '<option value="">Select Task</option>';
                        if (tasks && Array.isArray(tasks)) {
                            tasks.forEach(task => {
                                const option = document.createElement('option');
                                option.value = task.id;
                                option.textContent = task.name;
                                if (task.id == selectedTask) {
                                    option.selected = true;
                                }
                                taskSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching tasks:', error);
                        taskSelect.innerHTML = '<option value="">Error loading tasks</option>';
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
                let grandTotal = 0;
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
                    grandTotal += dailyTotal;
                });
                
                // Update the grand total cell
                const grandTotalCell = document.querySelector('.total-total');
                if (grandTotalCell) {
                    grandTotalCell.textContent = formatTime(grandTotal);
                }
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

            // Navigate to current week (home button)
            document.getElementById('home-button').addEventListener('click', function () {
                currentDate = new Date(); // Reset to today's date
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
                } else {
                    // If no users available, still ensure empty row is shown
                    const tbody = timeTable.querySelector('tbody');
                    tbody.innerHTML = '';
                    ensureEmptyRow();
                }
            }

            // Add event listener for approve button
            approveEntriesButton.addEventListener('click', function() {
                if (this.disabled) return;
                
                const user = userSelect.value;
                const dateRange = dateRangeElement.textContent;
                
                if (!user) {
                    $('.alert-message').text('Please select a user before approving entries.');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);
                    return;
                }

                // Show confirmation dialog
                if (!confirm('Are you sure you want to submit this weeks timesheet?')) {
                    return; // User cancelled, don't proceed
                }

                // Calculate the week dates
                const selectedWeekStart = getStartOfWeek(currentDate);
                const weekDates = [];
                
                // Generate all 7 days of the week (Monday to Sunday)
                for (let i = 0; i < 7; i++) {
                    const date = new Date(selectedWeekStart);
                    date.setDate(selectedWeekStart.getDate() + i);
                    weekDates.push(date.toISOString().split('T')[0]); // Format as YYYY-MM-DD
                }
                
                // Send AJAX GET request to backend
                const params = new URLSearchParams({
                    user_id: user,
                    dates: weekDates.join(',') // Send dates as comma-separated string
                });
                
                fetch(`/time-tracking/approve?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Set approval status to true after successful approval
                        isCurrentWeekApproved = true;
                        updateApproveButtonState();
                        updateFormEditability();
                        
                        $('.alert-message').text(`Entries approved for ${dateRange}`);
                        successAlert.classList.remove('hidden');
                        setTimeout(() => {
                            successAlert.classList.add('hidden');
                        }, 3000);
                    } else {
                        $('.alert-message').text(data.message || 'Failed to approve entries');
                        successAlert.classList.remove('hidden');
                        setTimeout(() => {
                            successAlert.classList.add('hidden');
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error approving entries:', error);
                    $('.alert-message').text('Error occurred while approving entries');
                    successAlert.classList.remove('hidden');
                    setTimeout(() => {
                        successAlert.classList.add('hidden');
                    }, 3000);
                });
            });


            copyEntriesButton.addEventListener('click', function() {
                copiedRows = [];
                const rows = document.querySelectorAll('#time-table tbody tr');
                rows.forEach(row => {
                    const project = row.querySelector('td:nth-child(1) select').value;
                    const task = row.querySelector('td:nth-child(2) select').value;
                    const mon = row.querySelector('td:nth-child(3) .input-field').value.trim();
                    const tue = row.querySelector('td:nth-child(4) .input-field').value.trim();
                    const wed = row.querySelector('td:nth-child(5) .input-field').value.trim();
                    const thu = row.querySelector('td:nth-child(6) .input-field').value.trim();
                    const fri = row.querySelector('td:nth-child(7) .input-field').value.trim();
                    const sat = row.querySelector('td:nth-child(8) .input-field').value.trim();
                    const sun = row.querySelector('td:nth-child(9) .input-field').value.trim();
                    
                    // Only copy rows that have at least a project selected
                    if (project) {
                        copiedRows.push({ project, task, mon, tue, wed, thu, fri, sat, sun });
                    }
                });
                
                console.log('Copied rows:', copiedRows); // Debug log
                $('.alert-message').text(`${copiedRows.length} entries copied successfully.`);
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

                // Add copied rows with proper async handling
                let completedRows = 0;
                copiedRows.forEach(async (rowData, index) => {
                    await addRowWithProjects(rowData.project, rowData.task, '', '', '', '', '', '', '');
                    completedRows++;
                    
                    // Show success message after all rows are processed
                    if (completedRows === copiedRows.length) {
                        $('.alert-message').text(`${copiedRows.length} entries pasted successfully (project/task structure copied, time entries cleared).`);
                        successAlert.classList.remove('hidden');
                        setTimeout(() => {
                            successAlert.classList.add('hidden');
                        }, 3000);
                        saveData();
                    }
                });
            });

            // Event listeners
            userSelect.addEventListener('change', function () {
                loadData();
            });

            // Initialize first user and ensure empty row
            initializeFirstUser(); // Automatically select the first user
            
            // Initialize row totals for existing data
            setTimeout(() => {
                const existingRows = document.querySelectorAll('#time-table tbody tr');
                existingRows.forEach(row => {
                    updateTotal(row);
                });
                updateDailyTotals();
            }, 100);
            
            // Also ensure empty row after a delay as a fallback
            setTimeout(() => {
                console.log('Fallback ensureEmptyRow called'); // Debug log
                ensureEmptyRow();
            }, 500);
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
