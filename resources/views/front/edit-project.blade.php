<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - Project Management</title>

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

    <form action="/projects/update-project/{{ $data->id }}" method="post">
        @csrf
    <main class="space-y-6 p-6 mx-auto px-4">
        <div class="flex items-center justify-between" style="margin: 16px;">
            <h1 class="text-3xl font-bold" style="font-size: 20px;">Edit Project</h1>
            <button type="submit"
                class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800"
                style="font-size: 12px;"
            >
                Save Changes
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
                                    value="{{ $data->project_number }}"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Project Name *</label>
                                <input
                                name="name"
                                    type="text"
                                    placeholder="Enter project name"
                                    class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black"
                                    value="{{ $data->name }}"
                                />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Client *</label>
                                <select name="client_id" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black" required>
                                    <option value="" selected disabled>Select a client</option>
                                    @foreach ($client as $item)
                                        <option {{ $item->id == $data->client_id ? 'selected': ''}} value="{{ $item->id }}">{{ $item->name }}</option>
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
                                    value="{{ $data->expected_profit }}"
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
                                $existingMemberIds = isset($data->members) ? $data->members->pluck('user_id')->toArray() : [];
                            @endphp
                            @foreach ($sortedTeam as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id, $existingMemberIds) ? 'disabled' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="space-y-2 mt-4" id="team-members">
                            <!-- Team members will be added here -->
                        </div>

                        <input type="hidden" name="team_members" id="teamMembers" value="@if(isset($data->members)){{ implode(',', $data->members->pluck('user_id')->toArray()) }}@endif">
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
        // Load existing team members on page load
        $(document).ready(function() {
            @if(isset($data->members) && $data->members->count() > 0)
                @foreach($data->members as $member)
                    // Fetch user data for existing members and display them
                    $.ajax({
                        url: `/api/users/{{ $member->user_id }}`,
                        method: 'GET',
                        success: function(user) {
                            addTeamMemberRowExisting({{ $member->user_id }}, user);
                        },
                        error: function() {
                            // Fallback if AJAX fails
                            const fallbackUser = {
                                name: '{{ $member->user->name }}',
                                profile_image_url: null,
                                role: 'Team Member'
                            };
                            addTeamMemberRowExisting({{ $member->user_id }}, fallbackUser);
                        }
                    });
                @endforeach
            @endif
        });

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

        function addTeamMemberRowExisting(teamMemberId, user) {
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

</body>
</html>
