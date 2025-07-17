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
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Project Name *</label>
                                <input
                                name="name"
                                    type="text"
                                    placeholder="Enter project name"
                                    class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"
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
                        <select class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black team" required>
                            <option value="" selected disabled>Add team member</option>
                            @foreach ($team as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <div class="flex flex-wrap gap-2 mt-4" id="team-members">
                            <!-- Team members will be added here -->
                        </div>

                        <input type="hidden" name="team_members" id="teamMembers">
                    </div>
                </div>
            </div>

            <div class='bg-white rounded-lg shadow border col-span-3' style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
                <div class='p-6'>
                    <h2 class='text-lg font-semibold mb-4'>Tasks</h2>
                    <div class='space-y-4'>
                        <div class='grid grid-cols-2 gap-4'>
                            <div class='space-y-2'>
                                <h3 class='font-medium'>Predefined Tasks</h3>
                                <div class='space-y-1 border rounded-lg p-4 ' style="margin-top: 1rem !important;">
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='01_BD & Contracts'>
                                        01_BD & Contracts
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='02_Competition / Pitch design'>
                                        02_Competition / Pitch design
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='03_Concept Design'>
                                        03_Concept Design
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='04_Preliminary Design'>
                                        04_Preliminary Design
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='05_Planning Submission Stage'>
                                        05_Planning Submission Stage
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='06_Detail Design Stage'>
                                        06_Detail Design Stage
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='07_Tender process'>
                                        07_Tender process
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='08_Author supervision'>
                                        08_Author supervision
                                    </label>
                                    <label class='flex items-center gap-2'>
                                        <input type='checkbox' class='rounded border-gray-300 text-black focus:ring-black task' value='10_Extra Work'>
                                        10_Extra Work
                                    </label>
                                </div>
                            </div>
                            <div class='space-y-2'>
                                <h3 class='font-medium'>Selected Tasks</h3>
                                <div class="border rounded-lg p-4 " style="margin-top: 1rem !important;">
                                    <div class='flex gap-2'>
                                        <input
                                            type='text'
                                            placeholder='Add custom task...'
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

            $(".team option:selected").attr('disabled','disabled');

            $('#team-members').append(`
                <div class='rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 flex items-center gap-1' style="background: #f9f9fa;">
                    <span>${teamMemberName}</span>
                    <button class='p-1 rounded-full hover:bg-gray-200' onclick='$(this).parent().remove(); $(".team option[value=${teamMember}]").removeAttr("disabled")'>
                        <i data-lucide='x' class='w-4 h-4'></i>x
                    </button>
                </div>
            `);

            $('#teamMembers').val($('#teamMembers').val() + teamMember + ',');
        })
    </script>

    <script>
        $('.task').on('change', function(){
            const task = $(this).val();
            const taskName = $(this).attr('value');

            if ($(this).is(':checked')) {
                $('#selected-tasks').append(`
                    <div class='flex items-center justify-between py-2 px-3 rounded-md' style="background: #f9f9fa; margin-top: 0.5rem;">
                        <span>${taskName}</span>
                        <button class='p-1 rounded-full hover:bg-gray-200' onclick='removeTask(this)'>
                            <i data-lucide='x' class='w-4 h-4'></i>x
                        </button>
                    </div>
                `);

                $('#tasks').val($('#tasks').val() + task + ',');
            } else {
                // Use a more robust selector
                $("#selected-tasks div:contains('" + taskName + "')").remove();
                removeTask(this); // Ensure the hidden input is updated
            }
        })

        function removeTask(el) {
            const task = $(el).parent().find('span').text();
            $(`#selected-tasks .task:contains(${task})`).prev().prop('checked', false);
            $(el).parent().remove();

            // Use a more robust selector
            $(".task").filter(function() {
                return $(this).val() === task;
            }).prop("checked", false);

            // Update the hidden input field #tasks
            const tasks = [];
            $('#selected-tasks div span').each(function () {
                tasks.push($(this).text());
            });
            $('#tasks').val(tasks.join(',')); // Join the tasks with a comma

        }


    </script>

    <script>
        $('#custom-task-input').on('keypress', function(e){
            if (e.key === 'Enter') {

                $('.text-muted-foreground ').hide();

                const task = $(this).val();

                $('#selected-tasks').append(`
                    <div class='flex items-center justify-between py-2 px-3 rounded-md' style="background: #f9f9fa; margin-top: 0.5rem;">
                        <span>${task}</span>
                        <button class='p-1 rounded-full hover:bg-gray-200' onclick='removeTask(this)'>
                            <i data-lucide='x' class='w-4 h-4'></i>x
                        </button>
                    </div>
                `);

                $('#tasks').val($('#tasks').val() + task + ',');
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
    </script>
</body>
</html>
