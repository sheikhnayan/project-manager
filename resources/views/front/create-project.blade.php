<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project - Project Management</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Date-fns -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/date-fns/2.30.0/date-fns.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>

    <style>
        body {
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        h3{
            font-size: 0.875rem !important;
            line-height: 1.25rem;
            color: #888e99;
        }

        input{
        border:1px solid #e5e7eb;
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
        }

        select{
            border: 1px solid #e5e7eb;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            background: transparent;
        }

        h2{
            font-size: 16px !important;
        }

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
    </style>
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <form action="{{ route('projects.store') }}" method="post">
        @csrf
    <main class="space-y-6 p-6 mx-auto px-4">
        <div class="flex items-center justify-between" style="margin: 16px;">
            <h1 class="text-3xl font-bold" style="font-size: 20px;">Create New Project</h1>
            <button type="submit"
                class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800"
                style="font-size: 12px;"
            >
                Add Project
            </button>
        </div>

        <div class="grid grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow border col-span-3" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Project Details</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Project Number *</label>
                                <input
                                    type="text"
                                    name="project_number"
                                    placeholder="Enter project number"
                                    class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"
                                    required
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Project Name *</label>
                                <input
                                name="name"
                                    type="text"
                                    placeholder="Enter project name"
                                    class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"
                                    required
                                />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Client *</label>
                                <select name="client_id" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                                    <option value="" selected disabled>Select a client</option>
                                    @foreach ($client as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Expected Profit (%) *</label>
                                <input
                                name="expected_profit"
                                    type="text"
                                    placeholder="Enter expected profit"
                                    class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"
                                    required
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow border col-span-2" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Team Members</h2>
                    <div class="space-y-4">
                        <select class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black team">
                            <option value="" selected disabled>Add team member</option>
                            @php
                                $sortedTeam = $team->where('is_archived', '!=', 1)->sortBy('name');
                            @endphp
                            @foreach ($sortedTeam as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <div class="space-y-2 mt-4" id="team-members">
                            <!-- Team members will be added here -->
                        </div>

                        <input type="hidden" name="team_members" id="teamMembers">
                    </div>
                </div>
            </div>

            <div class='bg-white rounded-lg shadow border col-span-5' style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
                <div class='p-6'>
                    <h2 class='text-lg font-semibold mb-4'>Tasks</h2>
                    <div class='space-y-4'>
                        <div class='grid grid-cols-2 gap-4'>
                            <div class='space-y-2'>
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class='font-medium flex items-center gap-2' style="color: #000;">
                                        Predefined Tasks
                                        <button id="prev-country" type="button" class="text-black px-2 py-1 rounded" style="font-size:12px;" title="Previous Country">
                                            <i data-lucide="chevron-left"></i>
                                        </button>
                                        <span id="current-country-name" class="text-sm font-medium text-gray-600">Loading...</span>
                                        <button id="next-country" type="button" class="text-black px-2 py-1 rounded" style="font-size:12px;" title="Next Country">
                                            <i data-lucide="chevron-right"></i>
                                        </button>
                                    </h3>
                                </div>
                                <div id="predefined-tasks-list" class='space-y-1 border rounded-lg p-4 ' style="margin-top: 1rem !important;">
                                    <!-- Tasks will be populated by JavaScript -->
                                </div>
                            </div>
                            <div class='space-y-2'>
                                <h3 class='font-medium' style="color: #000; margin-top: 0.4rem;">Selected Tasks</h3>
                                <div class="border rounded-lg p-4 " style="margin-top: 1.3rem !important;">
                                    <div class='flex gap-2'>
                                        <input
                                            type='text'
                                            placeholder='Add custom task…press return'
                                            class='flex-1 rounded-md border-gray-300 focus:border-black focus:ring-black'
                                            id='custom-task-input'
                                        />
                                        {{-- <button class='bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800'>
                                            Add Task
                                        </button> --}}
                                    </div>
                                    <div id="selected-tasks" style="margin-top: 1rem">
                                        <div class="text-sm text-muted-foreground text-center py-4">No tasks selected</div>
                                    </div>
                                    <!-- Selected tasks will appear here -->
                                </div>
                            </div>
                        </div>


                        <input type="hidden" name="tasks" id="tasks">
                    </div>
                </div>
            </div>
        </div>
    </main>
</form>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Global functions that need to be accessible from multiple script blocks
        function addTaskSorted(taskName) {
            const newTaskHtml = `
                <div class='flex items-center justify-between py-2 px-3 rounded-md' style="background: #f9f9fa; margin-top: 0.5rem;">
                    <span>${taskName}</span>
                    <button class='p-1 rounded-full hover:bg-gray-200' onclick='removeTask(this)'>
                        <i data-lucide='x' class='w-4 h-4'></i>x
                    </button>
                </div>
            `;
            
            // Get all existing tasks
            const existingTasks = [];
            $('#selected-tasks div').not('.text-muted-foreground').each(function() {
                const text = $(this).find('span').text();
                existingTasks.push({
                    element: $(this),
                    text: text
                });
            });
            
            // Add new task to the array
            existingTasks.push({
                element: $(newTaskHtml),
                text: taskName
            });
            
            // Sort using simple numeric sorting for task numbers
            existingTasks.sort((a, b) => {
                // Simple function to extract number from start of string
                const getNumber = (str) => {
                    const match = str.match(/^(\d+)/);
                    return match ? parseInt(match[1]) : 9999;
                };
                
                const numA = getNumber(a.text);
                const numB = getNumber(b.text);
                
                // Just compare the numbers directly
                return numA - numB;
            });
            
            // Clear the container (except for the "No tasks selected" message)
            $('#selected-tasks div').not('.text-muted-foreground').remove();
            
            // Re-add all tasks in sorted order
            existingTasks.forEach(task => {
                $('#selected-tasks').append(task.element);
            });
        }

        function updateTasksHiddenInput() {
            const tasks = [];
            $('#selected-tasks div span').each(function () {
                tasks.push($(this).text());
            });
            $('#tasks').val(tasks.join(','));
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
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    </script>

    <script>
        $('.team').on('change', function(){
            const teamMember = $(this).val();
            const teamMemberName = $(this).find('option:selected').text();

            if (!teamMember) return;

            $(".team option:selected").attr('disabled','disabled');

            // Fetch user data including profile picture
            $.ajax({
                url: `/api/users/${teamMember}`,
                method: 'GET',
                success: function(user) {
                    addTeamMemberRow(teamMember, user);
                },
                error: function() {
                    // Fallback if AJAX fails
                    const fallbackUser = {
                        name: teamMemberName,
                        profile_image_url: null,
                        role: 'Team Member'
                    };
                    addTeamMemberRow(teamMember, fallbackUser);
                }
            });
            
            // Reset the select dropdown
            $(this).val('');
        });

        function addTeamMemberRow(teamMemberId, user) {
            const roleDisplay = user.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : 'Team Member';

            $('#team-members').append(`
                <div class='team-member-row flex items-center justify-between py-2 px-4 rounded-lg bg-white' style="margin-bottom: 8px;">
                    <div class='flex items-center gap-3'>
                        <div class='w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0'>
                            ${user.profile_image_url || user.profile_image_path ? 
                                `<img src='${user.profile_image_url ? (user.profile_image_url.startsWith('http') ? user.profile_image_url : '/storage/' + user.profile_image_url) : user.profile_image_path}' alt='${user.name}' class='w-full h-full object-cover'>` :
                                `<div class='w-full h-full bg-black rounded-full'></div>`
                            }
                        </div>
                        <div class='flex flex-col min-w-0 flex-1'>
                            <span class='text-sm font-medium text-gray-900 truncate'>${user.name}</span>
                            <span class='text-xs text-gray-500 truncate'>${roleDisplay}</span>
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

        // Function to remove team member
        function removeTeamMember(button, teamMemberId) {
            // Re-enable the option in select dropdown
            $(`.team option[value='${teamMemberId}']`).removeAttr('disabled');
            
            // Remove the team member row
            $(button).closest('div').remove();
            
            // Update hidden input by removing the team member ID
            let currentMembers = $('#teamMembers').val();
            let membersArray = currentMembers.split(',').filter(id => id !== '' && id != teamMemberId);
            $('#teamMembers').val(membersArray.join(','));
        }
    </script>

    <script>
        // This script is now handled by the pagination script above
        // Task change handlers are initialized in the renderTaskList function
    </script>

    <script>
        $('#custom-task-input').on('keypress', function(e){
            if (e.key === 'Enter') {
                const task = $(this).val().trim();
                if (!task) return;

                $('.text-muted-foreground').hide();

                // Add task using the sorted function
                addTaskSorted(task);
                updateTasksHiddenInput();
                
                $(this).val('');
            }
        })
    </script>

    <script>
        // Prevent form submit on Enter key for all inputs
        $('form').on('keypress', function(e) {
            if (e.key === 'Enter') {
                // Allow Enter only for textarea, not for input fields
                if (e.target.tagName.toLowerCase() !== 'textarea') {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Form validation before submission
        $('form').on('submit', function(e) {
            const teamMembers = $('#teamMembers').val();
            if (!teamMembers || teamMembers.trim() === '') {
                e.preventDefault();
                alert('Please add at least one team member before creating the project.');
                return false;
            }
        });
    </script>

    {{-- function for country task list management --}}
    <script>
$(document).ready(function() {
    // Country task lists will be loaded from backend
    let countryTaskLists = [];
    let currentCountryIndex = 0;

    // Load country task lists from backend
    function loadCountryTaskLists() {
        $.ajax({
            url: '/api/countries/task-lists',
            method: 'GET',
            success: function(response) {
                countryTaskLists = response.data || [];
                if (countryTaskLists.length > 0) {
                    renderTaskList(0);
                } else {
                    // Fallback to default task lists if no countries found
                    initializeDefaultTaskLists();
                }
            },
            error: function() {
                // Fallback to default task lists on error
                console.warn('Could not load country task lists, using defaults');
                initializeDefaultTaskLists();
            }
        });
    }

    // Fallback default task lists
    function initializeDefaultTaskLists() {
        countryTaskLists = [
            {
                name: 'Denmark',
                tasks: [
                    '01_BD & Contracts',
                    '02_Competition / Pitch design',
                    '03_Concept Design',
                    '04_Preliminary Design',
                    '05_Planning Submission Stage',
                    '06_Detail Design Stage',
                    '07_Technical Design',
                    '08_Design Review',
                    '09_Design Approval',
                    '10_Extra Work'
                ]
            },
            {
                name: 'Germany',
                tasks: [
                    '01_BD & Contracts',
                    '02_Competition / Pitch development',
                    '03_Requirements Analysis',
                    '04_System Architecture',
                    '05_Frontend Development',
                    '06_Backend Development',
                    '07_Database Design',
                    '08_Testing Phase',
                    '09_Quality Assurance',
                    '10_Extra Work'
                ]
            },
            {
                name: 'Sweden',
                tasks: [
                    '01_BD & Contracts',
                    '02_Competition / Pitch construction',
                    '03_Site Survey',
                    '04_Foundation Work',
                    '05_Structural Work',
                    '06_Electrical Installation',
                    '07_Plumbing Installation',
                    '08_Finishing Work',
                    '09_Final Inspection',
                    '10_Extra Work'
                ]
            },
            {
                name: 'Norway',
                tasks: [
                    '01_BD & Contracts',
                    '02_Competition / Pitch management',
                    '03_Project Planning',
                    '04_Resource Allocation',
                    '05_Timeline Management',
                    '06_Budget Control',
                    '07_Risk Management',
                    '08_Team Coordination',
                    '09_Progress Monitoring',
                    '10_Extra Work'
                ]
            }
        ];
        renderTaskList(0);
    }

    function renderTaskList(countryIndex) {
        if (!countryTaskLists[countryIndex]) return;
        
        const country = countryTaskLists[countryIndex];
        const tasks = country.tasks || [];
        const $container = $('#predefined-tasks-list');
        
        // Update country name display
        $('#current-country-name').text(country.name);
        
        // Clear selected tasks when switching countries
        clearSelectedTasks();
        
        $container.empty();
        
        // Show all tasks at once - no pagination
        tasks.forEach(task => {
            $container.append(`
                <label class='flex items-center gap-2'>
                    <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='${task}'>
                    ${task}
                </label>
            `);
        });
        
        // Reinitialize task change handlers
        initializeTaskHandlers();
        
        // Update country navigation button states
        updateCountryNavigation();
    }

    function updateCountryNavigation() {
        const totalCountries = countryTaskLists.length;
        
        // Update button states
        $('#prev-country').prop('disabled', currentCountryIndex === 0);
        $('#next-country').prop('disabled', currentCountryIndex >= totalCountries - 1);
        
        // Update button opacity
        if (currentCountryIndex === 0) {
            $('#prev-country').css('opacity', '0.5');
        } else {
            $('#prev-country').css('opacity', '1');
        }
        
        if (currentCountryIndex >= totalCountries - 1) {
            $('#next-country').css('opacity', '0.5');
        } else {
            $('#next-country').css('opacity', '1');
        }
    }

    function clearSelectedTasks() {
        // Clear all selected task elements except the "No tasks selected" message
        $('#selected-tasks div').not('.text-muted-foreground').remove();
        
        // Show "No tasks selected" message
        if ($('#selected-tasks .text-muted-foreground').length === 0) {
            $('#selected-tasks').append('<div class="text-sm text-muted-foreground text-center py-4">No tasks selected</div>');
        } else {
            $('.text-muted-foreground').show();
        }
        
        // Clear the hidden input
        $('#tasks').val('');
    }

    function initializeTaskHandlers() {
        $('.task').off('change').on('change', function(){
            const task = $(this).val();
            const taskName = $(this).attr('value');

            if ($(this).is(':checked')) {
                // Hide "No tasks selected" message
                $('.text-muted-foreground').hide();
                
                // Add task and then sort
                addTaskSorted(taskName);
                updateTasksHiddenInput();
            } else {
                // Remove from selected tasks
                $("#selected-tasks div:contains('" + taskName + "')").remove();
                // Update hidden input
                updateTasksHiddenInput();
                
                // Show "No tasks selected" message if no tasks remain
                if ($('#selected-tasks div').not('.text-muted-foreground').length === 0) {
                    if ($('#selected-tasks .text-muted-foreground').length === 0) {
                        $('#selected-tasks').append('<div class="text-sm text-muted-foreground text-center py-4">No tasks selected</div>');
                    } else {
                        $('.text-muted-foreground').show();
                    }
                }
            }
        });
    }

    // Country navigation handlers
    $('#prev-country').on('click', function() {
        if (currentCountryIndex > 0) {
            currentCountryIndex--;
            renderTaskList(currentCountryIndex);
        }
    });

    $('#next-country').on('click', function() {
        if (currentCountryIndex < countryTaskLists.length - 1) {
            currentCountryIndex++;
            renderTaskList(currentCountryIndex);
        }
    });

    // Initialize by loading country task lists
    loadCountryTaskLists();
});

// Global function for removing tasks (called from onclick)
function removeTask(el) {
    const task = $(el).parent().find('span').text();
    
    // Uncheck the corresponding checkbox
    $(".task").filter(function() {
        return $(this).val() === task;
    }).prop("checked", false);
    
    // Remove the task element
    $(el).parent().remove();

    // Update the hidden input field
    updateTasksHiddenInput();
    
    // Show "No tasks selected" message if no tasks remain
    if ($('#selected-tasks div').not('.text-muted-foreground').length === 0) {
        if ($('#selected-tasks .text-muted-foreground').length === 0) {
            $('#selected-tasks').append('<div class="text-sm text-muted-foreground text-center py-4">No tasks selected</div>');
        } else {
            $('.text-muted-foreground').show();
        }
    }
}
</script>


</body>
</html>
