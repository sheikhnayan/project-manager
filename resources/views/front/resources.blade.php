<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resources - Project Management</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinite Scrollable Gantt Chart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>

        .sss .task-header{
            padding: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin:auto;
            margin-top: 10rem;
        }

        .modal-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-footer button {
            margin-left: 10px;
        }
        .draggable {
            cursor: move;
            background-color: #4A5568 !important;
            border-radius: 5px !important;
            border: 1px solid #fff !important;
        }

        .draggable[data-task="task1"] {
            margin-top: 3px !important;
        }

        .draggable span{
            font-size: 12px;
            display: block;
        }

        .calendar-day{
            width: 24px !important;
            height: 20px !important;
            text-align: center;
            cursor: grab;
        }

        :focus-visible {
            outline: none !important;
        }

        .second-input .calendar-day{
            height: 30px !important;
            font-size: 10px;
            padding: 0px !important;
            border-right: 1px solid #eee;
            border-top: 1px solid #eee;
            border-bottom: unset;
            border-left: unset;
        }

        .draggable[data-task="task1"] {
            margin-top: 3px !important;
        }

        .second-input{
            display: flex;
            cursor: grab;
        }

        .draggable:first-of-type{
            top: 0 !important;
            margin-top: 0px !important;
        }
        .calendar-container, .gantt-bar-container {
            display: flex;
            white-space: nowrap;
            position: relative;
        }
        .month-container {
            display: inline-block;
        }
        .month-header {
            display: block;
            width: 100%;
            margin: 0;
            padding: 5px 0;
            font-size: 14px;
            background-color: #000 !important;
            border-right: 1px solid #fff;
            color: white;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            /* Remove any margin-bottom or padding-bottom if present */
        }
        .calendar-day {
            display: inline-block;
            vertical-align: top;
            border: 1px solid #ccc;
            padding-top: 1px;
            font-size: 10px;
            box-sizing: border-box;
            margin: 0; /* Remove any margin that could cause a gap */
            border-top: unset;
        }
        .gantt-bar-container {
            position: relative;
        }
        .scroll-container {
            overflow-x: scroll;
            width: 100%;
            cursor: grab;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            scrollbar-width: none; /* For Firefox */
            -ms-overflow-style: none; /* For Internet Explorer and Edge */
        }

        .scroll-container::-webkit-scrollbar {
            display: none; /* For Chrome, Safari, and Opera */
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            background-color: #4a5568;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .content {
            margin-top: 50px; /* Adjust based on header height */
            display: flex;
        }
        .task-list {
            width: 600px;
            background-color: #f7fafc;
            border-right: 1px solid #ccc;
            border-radius: 4px;
        }
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: bold;
            background-color: #000;
            color: white;
            padding: 10px;
            height: 52px;
        }
        .task-item {
            padding: 10px;
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 0px;
            border-bottom: 1px solid #eee;
            margin-left: 0px;
            background: #fff;
            height: 30px;
            padding-right: 0px;
        }

        /* Border-radius for last task-item is now handled dynamically via JavaScript */
        .task-item:last-child{
            /* border-bottom-left-radius: 4px; */
        }

        .task-item img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .holiday{
            background: #eee;
        }

        .holiday {
            background: #ebebeb;
            color: #dc2626;
        }

        .second-input .holiday {
            background: #ebebeb !important;
            color: #dc2626 !important;
        }

        /* Team member drag and drop styles */
        .team-member-row {
            position: relative;
            cursor: move;
        }

        .team-member-row.ui-sortable-helper {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 1px solid #ddd;
        }

        .team-member-row.ui-sortable-placeholder {
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            height: 40px;
            visibility: visible !important;
        }

        .drag-handle {
            cursor: grab;
            padding: 6px 8px;
            color: #9ca3af;
            display: inline-flex;
            align-items: center;
            margin-right: 8px;
        }

        .drag-handle:hover {
            color: #4b5563;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        /* Expandable hierarchy styles */
        .expand-arrow {
            cursor: pointer;
            transition: transform 0.2s ease;
            font-size: 14px;
            color: #6b7280;
            margin-left: auto;
            padding: 4px;
        }

        .expand-arrow:hover {
            color: #374151;
        }

        .expand-arrow.expanded {
            transform: rotate(0deg);
        }

        .member-projects {
            display: none;
        }

        .member-projects.expanded {
            display: block;
        }

        .project-row {
            background-color: #f8f9fa;
            border-left: 3px solid #e5e7eb;
            padding-left: 7px;
        }

        .hierarchy-level-1 {
            background-color: #f8f9fa;
            border-left: 3px solid #3b82f6;
        }

        /* Calendar styling for expanded rows */
        .project-calendar-row {
            background-color: rgba(59, 130, 246, 0.05);
            /* border-left: 3px solid #3b82f6; */
        }

        .project-calendar-row.archived {
            background-color: rgba(156, 163, 175, 0.1);
            border-left-color: #9ca3af;
        }

        .member-projects-calendar {
            margin-top: 2px;
        }

        /* Add styles for resizable handles */
        .ui-resizable-handle {
            background: #fff;
            border: 1px solid #fff;
            z-index: 90;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20%;
        }
        .ui-resizable-e {
            right: 3px;
            float: right;
            width: 2px;
            height: 67%;
            cursor: e-resize;
            position: absolute;
            top: 4px;
            opacity: 1 !important;
            transition: opacity 0.2s;
        }
        .ui-resizable-w {
            left: 3px;
            width: 2px;
            height: 67%;
            cursor: e-resize;
            position: absolute;
            top: 4px;
            opacity: 1 !important;
            transition: opacity 0.2s;
            /* display: none; */
        }

        .ui-resizable-w:hover{
            display: block !important;
            opacity: 1 !important;
        },

        .ui-resizable-handle::before {
            content: ''; /* FontAwesome arrow icon */
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: #fff;
        }
        .ui-resizable-w::before {
            transform: rotate(180deg);
        }

        .ui-draggable-handle{
            background-color: #fff;
            padding-bottom: 0px;
            padding-top: 0px;
            border-radius: 5px;
        }

        .ui-draggable-handle-after{
            content: '';
            width: 100%;
            height: 5px;
            background-color: #fff;
            color: #fff;
            display: block;
        }

        .today-line {
            position: absolute;
            top: 31px;
            bottom: 0;
            width: 2px;
            background-color: #D9534F; /* Red color for the today line */
            z-index: 100; /* Ensure it appears above other elements */
        }

        /* Highlight holidays in the Gantt chart */
        .holiday-highlight {
            position: absolute;
            top: 0;
            bottom: 0;
            background-color: #f7f7f7; /* Light red background for holidays */
            z-index: -1; /* Ensure it doesn't overlap the Gantt bars */
        }

        progress{
            background: #dadada;
        }

        #addTaskButton{
            cursor: pointer;
        }

        /*circle-progess*/

        .circle-progess .progress-container {
            position: relative;
            width: 100%; /* take full width of parent */
            max-width: 100px; /* optional: limit max size */
            aspect-ratio: 1/1; /* maintain circle (1:1) */
            margin: 20px;
            margin-top: 0px;
            margin-left: 0px;
            }

        .circle-progess .progress-ring {
        transform: rotate(-90deg);
        width: 100%;
        height: 100%;
        }

        .circle-progess .progress-text {
            font-size: 2.2vw; /* responsive font size */
            font-weight: bold;
            color: #333;
            line-height: 1;
        }

        .circle-progess .custom-label {
            font-size: 0.4vw; /* responsive font size */
            /* margin-top: 5px; */
            color: #555;
        }

        .circle-progess svg {
            width: 100%;
            height: 100%;
        }

        .sss .relative.mt-4 {
            margin-top: 0px !important;
        }

        .modal input{
            border:1px solid #e5e7eb;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        .circle {
            margin: 1rem;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            align-content: center;
            /* padding: 0.6rem; */
            margin-top: 0px;
            color: #fff;
            text-align: center;
        }

        .content{
            margin-top: 0px !important;
        }

        .mains{
            border: 1px solid #D1D5DB;
            border-radius: 4px;
        }

        /* Border for first archived user row in calendar */
        .first-archived-row .calendar-day {
            border-top: 1px solid #ccc !important;
            height: 35px !important; /* Adjust height as needed */
        }

        .task-header::after{
            content: '';
            display: block;
            height: 72px;
            width: 1px;
            background: #f7f7f7;
            position: relative;
            top: -10px;
        }
    </style>

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
    showAddUserModal: false,
    showArchivedUsers: false
}">
    @include('front.nav')
    <div class="mx-auto p-4 shadow rounded-lg border" style="background: #fff !important; border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
        <div class="content p-2" style="padding-left: 0px !important; display: block; margin-bottom: 40px;">
            <div style="float: left; margin-top: 6px;">
                <h5 style="font-size: 20px; font-weight: 600; padding-left: 10px;">Team Members</h5>
            </div>
            <div class="flex items-center " style="float: right;">
                        <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;">
                            <i class="fas fa-home" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; color: #000;"></i>
                            {{-- <img src="{{ asset('house.png') }}" style="border: 1px solid #000; color: #000; padding: 0.6rem 0.8rem;border-radius: 4px;border-color: #eee;"> --}}
                        </button>
                        {{-- <div style="border: 1px solid #eee; border-radius: 8px; padding: 5px 3px; margin-right: 8px;">
                            <a href="/projects" class="toggle-btn active">Daily</a>
                            <a href="/projects/weekly" class="toggle-btn">Weekly</a>
                        </div> --}}
                        <style>
                            .toggle-btn {
                                background: #000;
                                color: #fff;
                                border: 2px solid #fff;
                                border-radius: 8px;
                                padding: 4px 18px;
                                font-size: 15px;
                                font-weight: 500;
                                margin-right: 0;
                                transition: background 0.2s, color 0.2s;
                            }
                            .toggle-btn:not(.active) {
                                background: transparent;
                                color: #000;
                            }
                            .toggle-btn.active {
                                background: #000;
                                color: #fff;
                                border: 2px solid #fff;
                            }
                            .toggle-btn:focus {
                                outline: none;
                            }
                        </style>
                        <script>
                            // Toggle active class on click
                            $('#toggleDaily, #toggleWeekly').on('click', function() {
                                $('.toggle-btn').removeClass('active');
                                $(this).addClass('active');
                                // Add your view switching logic here if needed
                            });
                        </script>

                        {{-- <a href="#" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.4rem 1rem;" @click="showAddUserModal = true">+  Add Member</a> --}}
                        <button 
                            @click="showArchivedUsers = !showArchivedUsers; setTimeout(() => { updateTodayLine(showArchivedUsers); updateLastTaskItemBorder(); }, 300)" 
                            class="ml-2 px-4 py-2 rounded bg-black text-white"
                            style="font-size: 13px; padding:0.4rem 1rem;"
                            x-text="showArchivedUsers ? 'Hide Archived Users' : 'Show Archived Users'"
                        ></button>
            </div>
        </div>
        <div class="content" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
                <div class="task-list" style="border-right: 0px; padding-right: 0px; padding-top: 0px">
                    <div class="task-header" style="margin-bottom: 0px; padding: 10px; padding-right: 0px; border-top-left-radius: 4px;">
                        <span id="sortProject" style="width: 40%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProject">
                            Team Member
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortProjectIcon" alt="">
                        </span>
                        <span id="sortRole" style="width: 22%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                            Role
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortRoleIcon" alt="">
                        </span>
                        <span id="sortCost" style="width: 15%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                            Cost
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortCostIcon" alt="">
                        </span>
                        <span id="sortHours" style="width: 15%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 6px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                            Hours
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortHoursIcon" alt="">
                        </span>
                        <span class="custom-offset" style="width: 8%; font-size: 12px; display: inherit; padding-left: 10px; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                            <!-- Status column header (no text) -->
                        </span>
                        {{-- <span style="font-size: 12px; width: 10%; padding-top: 17px; padding-bottom: 17px; text-align: center; border-right: 1px solid #eee;"> <i class="fas fa-eye show-user" data-type="show"></i> </span> --}}
                        {{-- <span class="text-center font-size: 12px; add-task" style="width: 10%;" id="addMemberButton"><i class="fas fa-plus"></i></span> --}}
                    </div>
                    <input type="hidden" id="task_count" value="{{ count($data) }}">
                    <input type="hidden" id="archieved_users" value="{{ count($data->where('is_archived', 1)) }}">
                    <input type="hidden" id="visible_users" value="{{ count($data->where('is_archived', 0)) }}">
                    <div class="not-archived">
                        @foreach ($data as $item)
                            @if ($item->is_archived == 0)
                                <!-- Main Team Member Row -->
                                <div class="task-item team-member-row data-id-{{ $item->id }}" data-user-id="{{ $item->id }}" style="position: unset">
                                    <span style="width: 40%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; align-items: center;">
                                        <div class="drag-handle" style="padding-left: 3px;"><img src="{{ asset('dots.svg') }}" style="margin-right: 5px;"></div>
                                        <img src="{{ $item->profile_image_url ? asset('storage/'.$item->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}">
                                        @php
                                            $nameParts = explode(' ', $item->name);
                                            $firstName = $nameParts[0] ?? '';
                                            $lastInitial = isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) . '.' : '';
                                            $displayName = trim($firstName . ' ' . $lastInitial);
                                        @endphp
                                        {{ $displayName }}
                                        <div class="expand-arrow" data-target="member-projects" data-id="{{ $item->id }}">▶</div>
                                    </span>
                                    <span style="width: 22%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                        {{ ucfirst(str_replace('_', ' ', $item->role)) }}
                                    </span>
                                        @php
                                            $es = DB::table('time_entries')->where('user_id',$item->id)->sum('hours');
                                        @endphp
                                    <span style="width: 15%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->id }}">{{ number_format($item->hourly_rate*$es) }}</span>
                                    <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-hour-{{ $item->id }}">
                                        {{ number_format($es) }}
                                    </span>
                                    <span style="width: 8%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                        <div class="approval-status-circle" data-user-id="{{ $item->id }}" style="width: 12px; height: 12px; border-radius: 50%; margin: 0 auto; background-color: #ccc;"></div>
                                    </span>
                                    {{-- @if ($item->is_archived == 0)
                                    <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye-slash hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                    @else
                                    <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                    @endif --}}
                                </div>
                                
                                <!-- Expandable Projects for this Team Member -->
                                <div class="member-projects" data-user-id="{{ $item->id }}">
                                    @foreach ($item->projects as $project)
                                        <!-- Project Row -->
                                        @if ($project->project->is_archived == 0)                                            
                                                <div class="task-item project-row hierarchy-level-1" data-project-id="{{ $project->project_id }}" data-user-id="{{ $item->id }}">
                                                    <span style="padding-left: 8px; width: 40%; font-size: 11px; display: inline-flex; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; align-items: center;">
                                                        <span style="width: 8px; height: 8px; background-color: black; border-radius: 50%; margin-right: 8px; display: inline-block;"></span>{{ $project->project->name ?? 'Unknown Project' }}
                                                    </span>
                                                    <span style="width: 22%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;">Project</span>
                                                    <span style="width: 15%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;">
                                                        @php
                                                            $projectCost = DB::table('time_entries')
                                                                ->where('user_id', $item->id)
                                                                ->where('project_id', $project->project_id)
                                                                ->sum('hours') * $item->hourly_rate;
                                                        @endphp
                                                        {{ number_format($projectCost) }}
                                                    </span>
                                                    <span style="width: 15%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;">
                                                        @php
                                                            $projectHours = DB::table('time_entries')
                                                                ->where('user_id', $item->id)
                                                                ->where('project_id', $project->project_id)
                                                                ->sum('hours');
                                                        @endphp
                                                        {{ number_format($projectHours) }}
                                                    </span>
                                                    <span style="width: 8%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;"></span>
                                                </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="archied" x-show="showArchivedUsers" x-transition>
                        @foreach ($data as $item)
                            @if ($item->is_archived == 1)
                            <div class="task-item team-member-row data-id-{{ $item->id }}" data-user-id="{{ $item->id }}" style="position: unset">
                                <span style="width: 40%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; align-items: center;">
                                    <div class="drag-handle">⋮⋮</div>
                                    <img src="{{ $item->profile_image_url ? asset('storage/'.$item->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}"> 
                                    @php
                                        $nameParts = explode(' ', $item->name);
                                        $firstName = $nameParts[0] ?? '';
                                        $lastInitial = isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) . '.' : '';
                                        $displayName = trim($firstName . ' ' . $lastInitial);
                                    @endphp
                                    {{ $displayName }}
                                    <div class="expand-arrow" data-target="member-projects" data-id="{{ $item->id }}">▶</div>
                                </span>
                                <span style="width: 22%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                    {{ ucfirst(str_replace('_', ' ', $item->role)) }}
                                </span>
                                    @php
                                        $es = DB::table('time_entries')->where('user_id',$item->id)->sum('hours');
                                    @endphp
                                <span style="width: 15%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->id }}">{{ $item->hourly_rate*$es }}</span>
                                <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-hour-{{ $item->id }}">
                                    {{ $es }}
                                </span>
                                <span style="width: 8%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                    <div class="approval-status-circle" data-user-id="{{ $item->id }}" style="width: 12px; height: 12px; border-radius: 50%; margin: 0 auto; background-color: #ccc;"></div>
                                </span>

                                {{-- @if ($item->is_archived == 0)
                                <span {{ $item->is_archived }} style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye-slash hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                @else
                                <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                @endif --}}
                            </div>
                            <!-- Expandable Projects for this Archived Team Member --><!-- Expandable Projects for this Team Member -->
                                <div class="member-projects" data-user-id="{{ $item->id }}">
                                    @foreach ($item->projects as $project)
                                        <!-- Project Row -->
                                        @if ($project->project->is_archived == 0)                                            
                                                <div class="task-item project-row hierarchy-level-1" data-project-id="{{ $project->project_id }}" data-user-id="{{ $item->id }}">
                                                    <span style="padding-left: 8px; width: 35%; font-size: 11px; display: inline-flex; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; align-items: center;">
                                                        <span style="width: 8px; height: 8px; background-color: black; border-radius: 50%; margin-right: 8px; display: inline-block;"></span>{{ $project->project->name ?? 'Unknown Project' }}
                                                    </span>
                                                    <span style="width: 20%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;">Project</span>
                                                    <span style="width: 18%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;">
                                                        @php
                                                            $projectCost = DB::table('time_entries')
                                                                ->where('user_id', $item->id)
                                                                ->where('project_id', $project->project_id)
                                                                ->sum('hours') * $item->hourly_rate;
                                                        @endphp
                                                        {{ number_format($projectCost) }}
                                                    </span>
                                                    <span style="width: 19%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;">
                                                        @php
                                                            $projectHours = DB::table('time_entries')
                                                                ->where('user_id', $item->id)
                                                                ->where('project_id', $project->project_id)
                                                                ->sum('hours');
                                                        @endphp
                                                        {{ number_format($projectHours) }}
                                                    </span>
                                                    <span style="width: 8%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;"></span>
                                                </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="scroll-container sss" style="border-bottom-right-radius: 4px;">
                    <div class="relative mt-4">
                        <div class="calendar-container second-calender">
                            <!-- JavaScript will populate the months and dates here -->
                        </div>
                        <div class="not-archived" style="margin-top: -4px">
                            @foreach ($data as $item)
                            @if ($item->is_archived == 0)
                                <!-- Main team member row (always visible) -->
                                <div class="second-input data-id-{{ $item->id }}" data-user-id="{{ $item->id }}"></div>
                                
                                @php
                                    $userProjects = \App\Models\Project::whereHas('members', function($query) use ($item) {
                                        $query->where('user_id', $item->id)->where('is_archived', 0);
                                    })->get();
                                    // if ($item->id == 2) {
                                    //     # code...
                                    //     dd($userProjects);
                                    // }
                                @endphp
                                @foreach($userProjects as $project)
                                    <!-- Project calendar row (initially hidden) -->
                                    <div class="second-input project-calendar-row member-project-{{ $item->id }}" data-project-id="{{ $project->id }}" data-user-id="{{ $item->id }}" style="display: none;"></div>
                                @endforeach
                            @endif
                            @endforeach
                        </div>
                        <div class="archied" x-show="showArchivedUsers" x-transition style="margin-top: -4px;">
                            @php $isFirstArchived = true; @endphp
                            @foreach ($data as $item)
                            @if ($item->is_archived == 1)
                                <!-- Main archived team member row -->
                                <div class="second-input data-id-{{ $item->id }} @if($isFirstArchived) first-archived-row @endif" data-user-id="{{ $item->id }}"></div>
                                @php $isFirstArchived = false; @endphp
                                
                                @php
                                    $userProjects = \App\Models\Project::whereHas('members', function($query) use ($item) {
                                        $query->where('user_id', $item->id)->where('is_archived', 0);
                                    })->get();
                                @endphp
                                @foreach($userProjects as $project)
                                    <!-- Project calendar row for archived user -->
                                    <div class="second-input project-calendar-row archived member-project-{{ $item->id }}" data-project-id="{{ $project->id }}" data-user-id="{{ $item->id }}" style="display: none;"></div>
                                @endforeach
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

    <!-- Add User Modal -->
    <div x-show="showAddUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" style="z-index: 9999; display: none;" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
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
                    <select id="add-user-role" name="role" class="mt-1 block w-full px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" required>
                        <option value="admin">Admin</option>
                        <option value="project_manager">Project Manager</option>
                        <option value="manager">Manager</option>
                        <option value="employee">Employee</option>
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
    </div>

    <script>
        $(function () {
            const calendarContainer = $('.calendar-container');
            const ganttBarContainer = $('.gantt-bar-container');
            const scrollContainer = $('.scroll-container');
            const st = $('#st_date').val();
            const en = $('#en_date').val();
            const startDate = new Date('2025-01-01');
            const endDate = new Date('2026-01-01');
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let isWeeklyView = false; // Default to daily view

            function renderCalendar() {
                calendarContainer.empty(); // Clear the calendar container
                let currentMonth = startDate.getMonth(); // Get the starting month index (0–11)
                let monthContainer = $('<div class="month-container"></div>');
                monthContainer.append(`<div class="month-header">${monthNames[currentMonth]}</div>`); // Use monthNames[currentMonth]

                inp = ``;

                // Base color for the fade effect
                const baseColor = [74, 85, 104]; // RGB for #4a5568 (dark gray-blue)
                const fadeStep = 2; // Amount to lighten the color for each month

                let fadeIndex = 0; // Initialize fade index

                for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                    const day = d.getDate().toString().padStart(2, '0'); // Pad day with leading zero
                    const month = (d.getMonth() + 1).toString().padStart(2, '0'); // Pad month with leading zero
                    const year = d.getFullYear();
                    const dayOfWeek = d.getDay();
                    const dateString = `${day}`;
                    const dayClass = (dayOfWeek === 0 || dayOfWeek === 6) ? 'calendar-day holiday' : 'calendar-day';

                    if (d.getMonth() !== currentMonth) {
                        // Apply fading color to the current month header
                        const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                        monthContainer.find('.month-header').css('background-color', fadeColor);

                        calendarContainer.append(monthContainer);
                        currentMonth = d.getMonth(); // Update currentMonth to the new month
                        monthContainer = $('<div class="month-container"></div>');
                        monthContainer.append(`<div class="month-header">${monthNames[currentMonth]}</div>`); // Use monthNames[currentMonth]

                        fadeIndex++; // Increment fade index for the next month
                    }

                    monthContainer.append(`<div class="calendar-day ${dayClass}" data-date="${year}-${month}-${day}">${dateString}</div>`);

                    inn = `<input readonly type="text" class="${dayClass} calendar-day inputss" onchange="convertTimeInput(this)" data-date="${year}-${month}-${day}">`;

                    inp += inn;
                }

                // Apply fading color to the last month header
                const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                monthContainer.find('.month-header').css('background-color', fadeColor);

                calendarContainer.append(monthContainer);

                // Store calendar template globally for later use
                window.calendarTemplate = inp;

                // Populate all calendar rows (visible and hidden)
                $('.second-input').each(function() {
                    const userId = $(this).attr('data-user-id');
                    const projectId = $(this).attr('data-project-id') || '';
                    
                    // Build input string with proper data attributes
                    let rowInp = inp;
                    if (userId) {
                        rowInp = rowInp.replace(/data-date="/g, `data-user-id="${userId}" data-date="`);
                    }
                    if (projectId) {
                        rowInp = rowInp.replace(/data-user-id="([^"]*)" data-date="/g, `data-user-id="$1" data-project-id="${projectId}" data-date="`);
                    }
                    
                    $(this).append(rowInp);
                });
            }

            // Initial render
            renderCalendar();
            
            // Scroll to current month after rendering
            scrollToCurrentMonth();
            // alignGanttBars();
            // $(window).resize(alignGanttBars);
        });

        // Function to scroll to current month
        function scrollToCurrentMonth() {
            const today = new Date();
            const currentYear = today.getFullYear();
            const currentMonth = today.getMonth() + 1; // getMonth() returns 0-11, so add 1
            const currentDay = today.getDate();
            
            // Create the date string for today
            const todayStr = `${currentYear}-${currentMonth.toString().padStart(2, '0')}-${currentDay.toString().padStart(2, '0')}`;
            
            // Find the calendar day element for today
            const $todayElement = $(`.calendar-day[data-date="${todayStr}"]`);
            
            if ($todayElement.length > 0) {
                const scrollContainer = $('.scroll-container');
                const elementLeft = $todayElement.position().left;
                const cellWidth = $todayElement.outerWidth();
                
                // Calculate position to show 7 days (1 week) before today - same as home button
                const weekOffset = cellWidth * 7; // 7 days worth of cells
                const scrollPosition = elementLeft - weekOffset;
                
                // Scroll to the calculated position
                scrollContainer.scrollLeft(Math.max(0, scrollPosition));
            }
        }
    </script>

    {{-- <script src="{{asset('js/projects.js')}}"></script> --}}
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
    $(document).ready(function () {
        const addTaskButton = $('#addTaskButton');
        const addTaskModal = $('#addTaskModal');
        const cancelButton = $('#cancelButton');
        const saveTaskButton = $('#saveTaskButton');
        const taskList = $('.task-list');

        const addMemberButton = $('#addMemberButton');
        const addMemberModal = $('#addMemberModal');
        const cancelButtonMember = $('#cancelButtonMember');

        // Open the modal
        addTaskButton.on('click', function () {
            addTaskModal.fadeIn();
        });

        // Close the modal
        cancelButton.on('click', function () {
            addTaskModal.fadeOut();
        });

        // Open the modal
        addMemberButton.on('click', function () {
            addMemberModal.fadeIn();
        });

        // Close the modal
        cancelButtonMember.on('click', function () {
            addMemberModal.fadeOut();

            return;
        });


        $('.calendar-day').each(function () {
            const $this = $(this);

            $this.attr('data-user-id', $(this).parent().data('user-id'));
        });
    });
</script>


<script>
$(document).ready(function () {
    // Enable drag-to-scroll for .calendar-container.second-calender and .second-input
    $('.scroll-container').each(function () {
        const scrollContainer = this;
        let isDragging = false;
        let startX;
        let scrollLeft;

        // Helper function to start drag
        function startDrag(e) {
            if (e.button !== 0) return; // Only left mouse button
            isDragging = true;
            startX = e.pageX;
            scrollLeft = scrollContainer.scrollLeft;
            scrollContainer.style.cursor = 'grabbing';
        }

        // Helper function to handle drag
        function duringDrag(e) {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX;
            const walk = x - startX;
            scrollContainer.scrollLeft = scrollLeft - walk;
        }

        // Helper function to end drag
        function endDrag() {
            isDragging = false;
            scrollContainer.style.cursor = 'grab';
        }

        // Attach events to both .calendar-container.second-calender and .second-input inside this scroll-container
        $(scrollContainer).find('.calendar-container.second-calender, .second-input, inputss').on('mousedown', startDrag);
        $(scrollContainer).on('mousemove', duringDrag);
        $(scrollContainer).on('mouseup mouseleave', endDrag);
    });
});
</script>

<script>
    // Function to convert time string (H:MM) to decimal format for display
    function convertTimeToDecimal(timeString) {
        if (!timeString || timeString === '0:00') return '';
        
        const parts = timeString.split(':');
        if (parts.length !== 2) return timeString;
        
        const hours = parseInt(parts[0]) || 0;
        const minutes = parseInt(parts[1]) || 0;
        
        return (hours + minutes / 60).toFixed(1);
    }

    // Function to convert decimal time to hours and minutes
    function convertToHoursAndMinutes(decimalTime) {
        const hours = Math.floor(decimalTime); // Get the whole number part as hours
        const minutes = Math.round((decimalTime - hours) * 60); // Get the fractional part as minutes
        return `${hours} hours ${minutes} minutes`;
    }

    // Function to convert decimal time input
        async function convertTimeInput(inputElement) {
            const decimalTime = parseFloat(inputElement.value); // Get the input value as a float

            const date = inputElement.getAttribute('data-date'); // Get the date from the data attribute
            const user_id = inputElement.getAttribute('data-user-id'); // Get the user ID from the data attribute
            const input_project_id = inputElement.getAttribute('data-project-id'); // Get project ID from input
            const data = inputElement.value; // Get the value from the input element
            const project_id = input_project_id || $('#project_id').val(); // Use input project ID or fallback to global

            if (isNaN(decimalTime) || decimalTime == 0) {
                inputElement.value = '';

                var project = $('#project_id').val();

                const data = 0;


                try {
                    // Prepare the payload with optional task_id
                    const payload = { user_id, date, data, project_id };
                    if (input_task_id) {
                        payload.task_id = input_task_id;
                    }

                    // Send the data to the server
                    const response = await fetch('/estimated-time-tracking/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                        },
                        body: JSON.stringify(payload),
                    });

                    if (response.ok) {
                        console.log('Data saved successfully.');

                        const responseData = await response.json(); // parse the JSON

                        $('.user-hour-'+user_id).html(responseData.data.total);

                        $('.user-cost-'+user_id).html(responseData.data.cost);

                        $('#fetch').load('/projects/reload-data/' + project_id, function() {
                            setTimeout(() => {
                                initProgressRings();
                            }, 10);
                        });
                    } else {
                        console.error('Failed to save data:', response.statusText);
                    }
                } catch (error) {
                    console.error('Error saving data:', error);
                }

                return;
            }

            if (decimalTime < 1 || decimalTime > 8) {
                alert('Value is Invalid.');
                inputElement.value = ''; // Clear the input field
                return; // Exit the function
            }

            if (!isNaN(decimalTime)) {
                // Keep the decimal format for display instead of converting to hours:minutes
                inputElement.value = decimalTime.toFixed(1);
            } else {
                inputElement.value = ''; // Clear the display if the input is invalid
            }



                try {
                    // Prepare the payload with optional task_id
                    const payload = { user_id, date, data, project_id };
                    if (input_task_id) {
                        payload.task_id = input_task_id;
                    }

                    // Send the data to the server
                    const response = await fetch('/estimated-time-tracking/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                        },
                        body: JSON.stringify(payload),
                    });                if (response.ok) {
                    console.log('Data saved successfully.');

                    const responseData = await response.json(); // parse the JSON

                    $('.user-hour-'+user_id).html(responseData.data.total);

                    $('.user-cost-'+user_id).html(responseData.data.cost);

                    $('#fetch').load('/projects/reload-data/' + project_id, function() {
                            setTimeout(() => {
                                initProgressRings();
                            }, 10);
                        });
                } else {
                    console.error('Failed to save data:', response.statusText);
                }
            } catch (error) {
                console.error('Error saving data:', error);
            }
        }

    document.addEventListener('DOMContentLoaded', function () {
        // Any additional DOMContentLoaded logic can go here
    });
</script>

<script>
    async function populateTimeTrackingData() {
        try {
            // Fetch the saved data from the server
            const response = await fetch('/estimated-time-tracking/get', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                },
            });

            if (!response.ok) {
                console.error('Failed to fetch time tracking data:', response.statusText);
                return;
            }

            const data = await response.json(); // Parse the JSON response


            // Iterate over the data and populate the input fields
            data.forEach(item => {
                const { task_id, user_id, date, time, project_id } = item;

                // Find the input field with the matching task_id and date
                const inputFields = document.querySelectorAll('.inputss');

                inputFields.forEach(inputField => {
                    const inputDate = inputField.getAttribute('data-date');
                    const inputUserId = inputField.getAttribute('data-user-id');
                    const inputProjectId = inputField.getAttribute('data-project-id');
                    const inputTaskId = inputField.getAttribute('data-task-id');

                    // Check for main team member entries (user-level)
                    if (inputDate === date && inputUserId == user_id && !inputProjectId && !inputTaskId) {
                        if (time !== '0:00') {
                            // Convert time from "H:MM" format to decimal format
                            const decimalTime = convertTimeToDecimal(time);
                            inputField.value = decimalTime; // Populate the input field with the decimal time
                        }
                    }
                    
                    // Check for project-specific entries
                    else if (inputDate === date && inputUserId == user_id && inputProjectId == project_id && !inputTaskId) {
                        if (time !== '0:00') {
                            const decimalTime = convertTimeToDecimal(time);
                            inputField.value = decimalTime;
                        }
                    }
                    
                    // Check for task-specific entries
                    else if (inputDate === date && inputUserId == user_id && inputProjectId == project_id && inputTaskId == task_id) {
                        if (time !== '0:00') {
                            const decimalTime = convertTimeToDecimal(time);
                            inputField.value = decimalTime;
                        }
                    }
                });


                // if (inputField) {
                //     inputField.value = time; // Populate the input field with the saved time
                // }
            });
        } catch (error) {
            console.error('Error fetching time tracking data:', error);
        }
    }

    // Call the function after the DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        populateTimeTrackingData();
        updateLastTaskItemBorder();
    });
</script>



<script>
    // Old jQuery show-user functionality - commented out as it conflicts with Alpine.js
    /*
    $('.show-user').on('click', function(){
        type = $(this).data('type');

        if (type == 'show') {
            $('.not-archived').hide();
            $('.archied').show();
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
        } else {
            $('.not-archived').show();
            $('.archied').hide();
            $(this).removeClass('fa-eye-slash');
            $(this).addClass('fa-eye');
        }

        $(this).data('type', type == 'show' ? 'hide' : 'show');
    })
    */
</script>

<script>
    // Function to create 1 progress ring
    // Function to create 1 progress ring
    function createProgressRing(container, value, customText) {
      container.innerHTML = '';

      const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      svg.setAttribute('viewBox', '0 0 250 250'); // Important for responsiveness
      svg.classList.add('progress-ring');


      const textWrapper = document.createElement('div');
      textWrapper.style.position = 'absolute';
      textWrapper.style.top = '50%';
      textWrapper.style.left = '50%';
      textWrapper.style.transform = 'translate(-50%, -50%)';
      textWrapper.style.textAlign = 'center';

      const percentText = document.createElement('div');
      percentText.classList.add('progress-text');
      percentText.textContent = null;

      const customLabel = document.createElement('div');
      customLabel.classList.add('custom-label');
      customLabel.textContent = customText || '';

      textWrapper.appendChild(percentText);
      textWrapper.appendChild(customLabel);

      container.appendChild(svg);
      container.appendChild(textWrapper);

      const fullCircles = Math.floor(value / 100);
      const remainingPercent = value % 100;
      const baseRadius = 70;
      const gap = 12;
      const strokeWidth = 10;

      let animationQueue = [];
      const normalColors = ['#4a5568'];
      const overLimitColor = '#e74c3c'; // Red if over 100%

      const isOverLimit = value > 100;

      for (let i = 0; i < fullCircles; i++) {
        animationQueue.push({
          radius: baseRadius - i * gap,
          percent: 100,
          color: isOverLimit ? overLimitColor : normalColors[i % normalColors.length],
          strokeWidth: strokeWidth,
        });
      }

      if (remainingPercent > 0) {
        animationQueue.push({
          radius: baseRadius - fullCircles * gap,
          percent: remainingPercent,
          color: isOverLimit ? overLimitColor : normalColors[fullCircles % normalColors.length],
          strokeWidth: strokeWidth,
        });
      }

      animateNextRing(svg, percentText, animationQueue, 0);
    }

    // Animate one ring
    function animateNextRing(svg, percentText, queue, index) {
      if (index >= queue.length) return;

      const { radius, percent, color, strokeWidth } = queue[index];
      const circumference = 2 * Math.PI * radius;

      const circleBackground = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      circleBackground.setAttribute('r', radius);
      circleBackground.setAttribute('cx', 125);
      circleBackground.setAttribute('cy', 125);
      circleBackground.setAttribute('fill', 'transparent');
      circleBackground.setAttribute('stroke', '#eee');
      circleBackground.setAttribute('stroke-width', strokeWidth);

      const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      circle.setAttribute('r', radius);
      circle.setAttribute('cx', 125);
      circle.setAttribute('cy', 125);
      circle.setAttribute('fill', 'transparent');
      circle.setAttribute('stroke', color);
      circle.setAttribute('stroke-width', strokeWidth);
      circle.setAttribute('stroke-dasharray', `${circumference} ${circumference}`);
      circle.setAttribute('stroke-dashoffset', circumference);

      svg.appendChild(circleBackground);
      svg.appendChild(circle);

      let start = null;
      const duration = 800;

      function animate(time) {
        if (!start) start = time;
        const elapsed = time - start;
        const progress = Math.min(elapsed / duration, 1);
        const offset = circumference - (percent / 100) * circumference * progress;

        circle.setAttribute('stroke-dashoffset', offset);

        let completedPercent = 0;
        for (let j = 0; j < index; j++) {
          completedPercent += queue[j].percent;
        }
        const currentProgress = completedPercent + (percent * progress);
        // percentText.textContent = `${Math.floor(currentProgress)}%`;

        if (progress < 1) {
          requestAnimationFrame(animate);
        } else {
          animateNextRing(svg, percentText, queue, index + 1);
        }
      }

      requestAnimationFrame(animate);
    }

    // Main code: Read all .progress-container elements
    document.addEventListener('DOMContentLoaded', function() {
        initProgressRings();
    });

</script>

<script>
    $(function() {
      $('input[name="date"]').daterangepicker({
        opens: 'left'
      }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
      });
    });
    </script>

    <script>
        function initProgressRings() {
            const containers = document.querySelectorAll('.progress-container');

            containers.forEach(container => {
                const value = parseFloat(container.getAttribute('data-value'));
                const customText = container.getAttribute('data-text') || '';
                if (!isNaN(value)) {
                    createProgressRing(container, value, customText);
                }
            });
        }
    </script>

    <script>
        function updateTodayLine(showArchived = false) {
            // Remove existing today line
            $('.today-line').remove();
            
            const today = new Date();
            const startDate = new Date('2025-01-01');
            const dayWidth = $(".calendar-day").outerWidth();
            
            // Count visible users
            let visibleUserCount = $('#visible_users').val();
            
            // Add archived users count if they should be shown
            if (showArchived) {
                visibleUserCount = parseInt(visibleUserCount) + parseInt($('#archieved_users').val());
            }

            console.log('Visible User Count:', visibleUserCount);
            
            // Calculate the number of days from the start date to today
            const daysFromStart = Math.floor((today - startDate) / (1000 * 60 * 60 * 24));
            
            // Calculate the left position for the today line
            const todayPosition = daysFromStart * dayWidth;
            
            // Calculate dynamic height: header (52px) + (visible users * 30px)
            const totalHeight = 21 + (visibleUserCount * 30);
            
            // Add the today line to the Gantt chart
            const todayLine = $('<div class="today-line"></div>');
            todayLine.css({
                left: todayPosition + 'px',
                height: totalHeight + 'px'
            });
            
            $('.scroll-container .relative').append(todayLine);
        }

        function highlightToday() {
            updateTodayLine(false); // Initially no archived users shown
        }

        // Call the function after the DOM is ready
        $(document).ready(function () {
            highlightToday();
        });
    </script>

<script>
    $(document).ready(function() {
    $('#home').on('click', function() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;

    // Find the calendar-day for today
    const $todayCell = $(`.calendar-day[data-date="${todayStr}"]`);
    if ($todayCell.length) {
        const scrollContainer = $('.scroll-container');
        const cellLeft = $todayCell.position().left;
        const cellWidth = $todayCell.outerWidth();
        
        // Calculate position to show 7 days (1 week) before today
        const weekOffset = cellWidth * 7; // 7 days worth of cells
        const scrollPosition = cellLeft - weekOffset;
        
        scrollContainer.animate({
            scrollLeft: Math.max(0, scrollPosition) // Ensure we don't scroll to negative position
        }, 400);
    }
});
});
</script>


<script>
// $(document).ready(function() {
//     let asc = true;
//     $('#sortProject').on('click', function() {
//         let items = $('.task-list .task-item').get();
//         items.sort(function(a, b) {
//             let keyA = $(a).find('span').first().text().trim().toLowerCase();
//             let keyB = $(b).find('span').first().text().trim().toLowerCase();
//             if (asc) {
//                 return keyA.localeCompare(keyB);
//             } else {
//                 return keyB.localeCompare(keyA);
//             }
//         });
//         $.each(items, function(i, item) {
//             $('.task-list').append(item);
//         });
//         asc = !asc;
//         $('#sortProjectIcon').toggleClass('fa-sort-alpha-down fa-sort-alpha-up');
//     });
// });

$(document).ready(function() {
    // Track sort direction for each column
    let sortDirections = {
        project: true,
        role: true,
        cost: true,
        hours: true
    };

    // Function to update border-radius on last visible task-item
    function updateLastTaskItemBorder() {
        // Remove border-radius from all task-items
        $('.task-item').css('border-bottom-left-radius', '');
        
        // Find the last visible task-item in not-archived section
        let lastNotArchived = $('.task-list .not-archived .task-item:visible').last();
        if (lastNotArchived.length) {
            lastNotArchived.css('border-bottom-left-radius', '4px');
        }
        
        // If archived users are shown, find the last visible in archived section
        if ($('.task-list .archied').is(':visible')) {
            let lastArchived = $('.task-list .archied .task-item:visible').last();
            if (lastArchived.length) {
                // Remove border from not-archived last item if archived is shown
                lastNotArchived.css('border-bottom-left-radius', '');
                lastArchived.css('border-bottom-left-radius', '4px');
            }
        }
    }

    function sortTaskItems(getKey, col, isNumeric = false) {
        // Get items from left table (task-list)
        let $leftContainer = $('.task-list .not-archived');
        let items = $leftContainer.children('.task-item').get();
        
        // Sort the items based on the key
        items.sort(function(a, b) {
            let keyA = getKey($(a));
            let keyB = getKey($(b));
            if (isNumeric) {
                keyA = parseFloat((keyA + '').replace(/,/g, '')) || 0;
                keyB = parseFloat((keyB + '').replace(/,/g, '')) || 0;
            }
            if (keyA < keyB) return sortDirections[col] ? -1 : 1;
            if (keyA > keyB) return sortDirections[col] ? 1 : -1;
            return 0;
        });
        
        // Get the order of data-ids from sorted items BEFORE removing them
        let sortedDataIds = items.map(item => $(item).attr('class').match(/data-id-(\d+)/)[1]);
        
        // Store all member-projects divs BEFORE removing them
        let memberProjectsMap = {};
        $leftContainer.children('.member-projects').each(function() {
            let userId = $(this).attr('data-user-id');
            memberProjectsMap[userId] = $(this).detach(); // Use detach to keep data and events
        });
        
        // Remove all .task-item elements from left table
        $leftContainer.children('.task-item').remove();
        
        // Append sorted items to left table along with their member-projects
        $.each(items, function(i, item) {
            let userId = $(item).attr('data-user-id');
            $leftContainer.append(item);
            // Also append the associated member-projects div right after the task-item
            if (memberProjectsMap[userId]) {
                $leftContainer.append(memberProjectsMap[userId]);
            }
        });
        
        // Update last-child border-radius
        updateLastTaskItemBorder();

        // Sort the right table (.second-input elements) in the same order
        let $rightContainer = $('.scroll-container .not-archived');
        let rightItems = $rightContainer.children('.second-input').not('.project-calendar-row').get();
        
        // Sort right items based on the same order
        rightItems.sort(function(a, b) {
            let dataIdA = $(a).attr('class').match(/data-id-(\d+)/)[1];
            let dataIdB = $(b).attr('class').match(/data-id-(\d+)/)[1];
            let indexA = sortedDataIds.indexOf(dataIdA);
            let indexB = sortedDataIds.indexOf(dataIdB);
            return indexA - indexB;
        });
        
        // Remove only main .second-input elements (not project rows) from right table
        $rightContainer.children('.second-input').not('.project-calendar-row').remove();
        
        // Append sorted items back to right table
        $.each(rightItems, function(i, item) {
            $rightContainer.append(item);
            // Also move the associated project rows
            let userId = $(item).attr('data-user-id');
            let projectRows = $(`.member-project-${userId}`);
            projectRows.each(function() {
                $rightContainer.append(this);
            });
        });
        
        sortDirections[col] = !sortDirections[col];
    }

    $('#sortProject').on('click', function() {
        sortTaskItems(item => item.find('span').eq(0).text().trim().toLowerCase(), 'project', false);
    });

    $('#sortRole').on('click', function() {
        sortTaskItems(item => item.find('span').eq(1).text().trim().toLowerCase(), 'role', false);
    });

    $('#sortCost').on('click', function() {
        sortTaskItems(item => item.find('span').eq(2).text().replace(/,/g, '').trim(), 'cost', true);
    });

    $('#sortHours').on('click', function() {
        sortTaskItems(item => item.find('span').eq(3).text().replace(/,/g, '').trim(), 'hours', true);
    });
});

</script>

<script>
// Function to check approval status for all users
async function checkUsersApprovalStatus() {
    try {
        // Get last week's date range (Monday to Sunday)
        const today = new Date();
        const lastWeekStart = new Date(today);
        lastWeekStart.setDate(today.getDate() - today.getDay() - 6); // Last Monday
        
        const lastWeekDates = [];
        for (let i = 0; i < 7; i++) {
            const date = new Date(lastWeekStart);
            date.setDate(lastWeekStart.getDate() + i);
            lastWeekDates.push(date.toISOString().split('T')[0]);
        }
        
        // Get all user IDs from the approval status circles
        const circles = document.querySelectorAll('.approval-status-circle');
        const userIds = Array.from(circles).map(circle => circle.getAttribute('data-user-id'));
        
        // Check approval status for each user
        for (const userId of userIds) {
            try {
                const params = new URLSearchParams({
                    user_id: userId,
                    dates: lastWeekDates.join(',')
                });
                
                const response = await fetch(`/time-tracking/approval-status?${params.toString()}`);
                if (response.ok) {
                    const data = await response.json();
                    updateApprovalStatusCircle(userId, data.is_approved || false);
                } else {
                    // If API fails, default to red (not approved)
                    updateApprovalStatusCircle(userId, false);
                }
            } catch (error) {
                console.error(`Error checking approval status for user ${userId}:`, error);
                // If request fails, default to red (not approved)
                updateApprovalStatusCircle(userId, false);
            }
        }
    } catch (error) {
        console.error('Error checking users approval status:', error);
    }
}

// Function to update the approval status circle color
function updateApprovalStatusCircle(userId, isApproved) {
    const circles = document.querySelectorAll(`.approval-status-circle[data-user-id="${userId}"]`);
    circles.forEach(circle => {
        if (isApproved) {
            circle.style.backgroundColor = '#22c55e'; // Green color for approved
            circle.title = 'Last week time entries approved';
        } else {
            circle.style.backgroundColor = '#ef4444'; // Red color for not approved
            circle.title = 'Last week time entries not approved';
        }
    });
}

// Initialize approval status check when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for the page to fully load
    setTimeout(checkUsersApprovalStatus, 1000);
});
</script>

<script>
    // Team member drag and drop functionality
    $(document).ready(function() {
        // Variable to prevent multiple simultaneous reorders
        var reorderTimeout = null;
        
        // Make the team member lists sortable - be VERY specific about which containers
        $('.task-list .not-archived, .task-list .archied').sortable({
            handle: '.drag-handle',
            axis: 'y',
            cursor: 'move',
            helper: function(e, item) {
                // Create a clone for dragging
                return item.clone();
            },
            placeholder: 'ui-sortable-placeholder',
            tolerance: 'pointer',
            items: '> .team-member-row', // Only sort direct team member rows
            connectWith: '.task-list .not-archived, .task-list .archied',
            start: function(event, ui) {
                // Store the member-projects element that follows this team member
                var userId = ui.item.data('user-id');
                var $memberProjects = $('.member-projects[data-user-id="' + userId + '"]');
                ui.item.data('memberProjects', $memberProjects);
                ui.item.data('memberProjectsParent', $memberProjects.parent());
                console.log('Dragging user:', userId, 'Has projects div:', $memberProjects.length > 0, 'Parent:', $memberProjects.parent().attr('class'));
            },
            stop: function(event, ui) {
                // Move the associated member-projects div after the team member
                var userId = ui.item.data('user-id');
                var $memberProjects = ui.item.data('memberProjects');
                
                console.log('Stop - User:', userId, 'Projects div exists:', $memberProjects && $memberProjects.length > 0);
                
                if ($memberProjects && $memberProjects.length > 0) {
                    // Detach and reattach the projects div right after the team member
                    $memberProjects.detach().insertAfter(ui.item);
                    console.log('Moved projects div for user:', userId, 'to after team member row');
                } else {
                    // Try to find it again in case it got lost
                    $memberProjects = $('.member-projects[data-user-id="' + userId + '"]');
                    if ($memberProjects.length > 0) {
                        $memberProjects.detach().insertAfter(ui.item);
                        console.log('Found and moved projects div for user:', userId);
                    } else {
                        console.warn('Could not find projects div for user:', userId);
                    }
                }
                
                // Debounce the reorder to avoid double-triggering
                clearTimeout(reorderTimeout);
                reorderTimeout = setTimeout(function() {
                    console.log('About to perform reorder, checking if user', userId, 'is in DOM...');
                    var $check = $('.team-member-row[data-user-id="' + userId + '"]');
                    console.log('User', userId, 'found:', $check.length, 'Parent:', $check.parent().attr('class'));
                    performReorder();
                }, 150); // Increased delay to ensure DOM has settled
            }
        });
        
        // Function to perform the actual reordering
        function performReorder() {
            console.log('Performing reorder');
            
            // Get the updated order of team members - be VERY specific about which container
            var memberOrder = [];
            
            // Get order from non-archived section (ONLY from task-list, not scroll-container)
            $('.task-list .not-archived > .team-member-row').each(function() {
                var userId = $(this).data('user-id');
                memberOrder.push(userId);
                console.log('Non-archived user:', userId);
            });
            
            // Get order from archived section (if visible) - again, ONLY from task-list
            $('.task-list .archied > .team-member-row').each(function() {
                var userId = $(this).data('user-id');
                memberOrder.push(userId);
                console.log('Archived user:', userId);
            });
            
            console.log('Final member order:', memberOrder);
            
            // Reorder the calendar input rows accordingly
            reorderCalendarInputs(memberOrder);
        }
        
        // Function to reorder calendar inputs based on team member order
        function reorderCalendarInputs(memberOrder) {
            console.log('Reordering calendar inputs for:', memberOrder);
            
            // Double-check we're targeting the right containers
            var $notArchivedContainer = $('.scroll-container .not-archived');
            var $archivedContainer = $('.scroll-container .archied');
            
            console.log('Calendar container (not-archived) found:', $notArchivedContainer.length);
            console.log('Calendar container (archived) found:', $archivedContainer.length);
            
            // Verify team member containers are NOT being touched
            console.log('Team member container children BEFORE:', $('.task-list .not-archived > .team-member-row').length);
            
            // Collect all calendar elements grouped by user
            var inputGroups = {};
            
            // Collect from both containers
            [$notArchivedContainer, $archivedContainer].forEach(function($container) {
                $container.children('.second-input').each(function() {
                    var $element = $(this);
                    var userId = $element.data('user-id');
                    
                    if (!userId) return;
                    
                    // Initialize group if not exists
                    if (!inputGroups[userId]) {
                        inputGroups[userId] = [];
                    }
                    
                    // Check if this is a main user row or project row
                    if ($element.hasClass('project-calendar-row')) {
                        // It's a project row, add to group
                        inputGroups[userId].push($element.detach());
                    } else {
                        // It's the main user row, add it at the beginning
                        inputGroups[userId].unshift($element.detach());
                    }
                });
            });
            
            console.log('Input groups collected:', Object.keys(inputGroups));
            
            // Clear containers
            $notArchivedContainer.empty();
            $archivedContainer.empty();
            
            console.log('Team member container children AFTER CLEAR:', $('.task-list .not-archived > .team-member-row').length);
            
            // Reorder inputs based on member order
            memberOrder.forEach(function(userId) {
                if (inputGroups[userId] && inputGroups[userId].length > 0) {
                    // Check if the corresponding team member is in archived section
                    var $teamMember = $('.team-member-row[data-user-id="' + userId + '"]');
                    var isArchived = $teamMember.closest('.archied').length > 0;
                    
                    var $targetContainer = isArchived ? $archivedContainer : $notArchivedContainer;
                    
                    console.log('Appending user', userId, 'to', isArchived ? 'archived' : 'not-archived', 'container. Elements:', inputGroups[userId].length);
                    
                    // Append all elements for this user (main row + project rows)
                    inputGroups[userId].forEach(function($element) {
                        $targetContainer.append($element);
                    });
                } else {
                    console.warn('No input elements found for user:', userId);
                }
            });
            
            console.log('Team member container children AFTER APPEND:', $('.task-list .not-archived > .team-member-row').length);
            
            console.log('Calendar reordering complete');
            
            // Debug: Check if all elements are actually in the DOM
            setTimeout(function() {
                console.log('POST-REORDER CHECK:');
                var teamCount = 0;
                var calendarCount = 0;
                $('.not-archived > .team-member-row').each(function() {
                    teamCount++;
                    var userId = $(this).data('user-id');
                    if (userId == 1) {
                        console.log('  *** USER 1 TEAM MEMBER FOUND! Visible:', $(this).is(':visible'), 'Display:', $(this).css('display'));
                    }
                });
                $('.scroll-container .not-archived > .second-input').not('.project-calendar-row').each(function() {
                    calendarCount++;
                    var userId = $(this).data('user-id');
                    if (userId == 1) {
                        console.log('  *** USER 1 CALENDAR INPUT FOUND! Visible:', $(this).is(':visible'), 'Display:', $(this).css('display'));
                    }
                });
                console.log('Total team members:', teamCount, 'Total calendar inputs:', calendarCount);
            }, 250);
            
            // Update today line height after reordering
            setTimeout(function() {
                if (typeof updateTodayLine === 'function') {
                    updateTodayLine();
                }
            }, 100);
        }
        
        // Optional: Add visual feedback when hovering over sortable items
        $('.not-archived, .archied').on('mouseenter', '.team-member-row', function() {
            $(this).css('background-color', '#f9fafb');
        }).on('mouseleave', '.team-member-row', function() {
            $(this).css('background-color', '#fff');
        });
    });

    // Expandable hierarchy functionality
    $(document).on('click', '.expand-arrow', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const arrow = $(this);
        const isExpanded = arrow.hasClass('expanded');
        const targetClass = arrow.data('target');
        const targetId = arrow.data('id');
        
        // Toggle arrow rotation
        if (isExpanded) {
            arrow.removeClass('expanded').html('▶');
        } else {
            arrow.addClass('expanded').html('▼');
        }
        
        // Toggle content visibility with animation - only handle member-projects
        if (targetClass === 'member-projects') {
            // Toggle projects for this user in left panel
            const projectsDiv = $(`.member-projects[data-user-id="${targetId}"]`);
            // Toggle project calendar rows for this user
            const projectCalendarRows = $(`.member-project-${targetId}`);
            
            if (isExpanded) {
                projectsDiv.slideUp(300);
                projectCalendarRows.slideUp(300);
            } else {
                projectsDiv.slideDown(300);
                projectCalendarRows.slideDown(300);
            }
        }
    });

</script>

</body>
</html>
