<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resources Weekly - Project Management</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='2' y='6' fill='%23000'/><rect width='24' height='2' y='11' fill='%23000'/><rect width='24' height='2' y='16' fill='%23000'/></svg>">
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

        .calendar-day{
            width: 32px !important;
            min-width: 32px !important;
            max-width: 32px !important;
            flex: 0 0 32px !important; /* Prevent flexbox shrink/stretch */
            height: 20px !important;
            text-align: center;
            border: 1px solid #ccc;
            box-sizing: border-box;
            display: inline-block;
            vertical-align: top;
            padding-top: 1px;
            font-size: 10px;
            margin: 0;
            border-top: unset;
            flex-shrink: 0;
        }

        .second-input .calendar-day{
            height: 30px !important;
            font-size: 10px;
            padding: 0px !important;
            width: 32px !important;
            min-width: 32px !important;
            max-width: 32px !important;
            flex: 0 0 32px !important; /* Lock width inside flex rows */
            border-right: 1px solid #eee;
            border-top: 1px solid #eee;
            border-bottom: unset;
            border-left: unset;
            box-sizing: border-box;
        }

        .second-input{
            display: flex;
        }

        .calendar-container {
            display: block;
            white-space: nowrap;
            position: relative;
        }
        
        .month-header-row {
            display: flex;
            white-space: nowrap;
            width: fit-content;
        }
        
        .week-cell-row {
            display: flex;
            white-space: nowrap;
            width: fit-content;
        }
        
        .month-header {
            display: inline-block;
            vertical-align: top;
            margin: 0;
            padding: 5px 0;
            font-size: 14px;
            background-color: #000 !important;
            border-right: 1px solid #fff;
            color: white;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            height: 32px;
            line-height: 22px;
            box-sizing: border-box;
        }

        .scroll-container {
            overflow-x: scroll;
            width: 100%;
            cursor: grab;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }

        .scroll-container::-webkit-scrollbar {
            display: none !important; /* Chrome, Safari, and Opera */
            width: 0 !important;
            height: 0 !important;
        }

        .content {
            display: flex;
            border-bottom-left-radius: 0px !important;
            border-bottom-right-radius: 0px !important;
        }
        
        .task-list {
            width: 600px;
            background-color: #f7fafc;
            border-right: 1px solid #ccc;
            /* padding: 10px; */
            padding-bottom: 0px;
            border-bottom-left-radius: 4px;
            border-bottom-left-radius: 0px !important;
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
            border-radius: 4px;
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 0px;
            border-bottom: 1px solid #eee;
            margin-left: 0px;
            background: #fff;
            height: 30px;
        }

        .task-item:last-child{
            border-bottom-left-radius: 4px;
        }
        
        .task-item img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .drag-handle {
            color: #9ca3af;
            margin-right: 8px;
            cursor: move;
            font-size: 12px;
            padding: 6px 8px;
            display: inline-flex;
            align-items: center;
        }

        .drag-handle:hover {
            color: #4b5563;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .holiday{
            color: #d32f2f;
            background-color: #f7f7f7 !important;
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
            border-left: 3px solid #000;
        }

        /* Calendar styling for expanded rows */
        .project-calendar-row {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .project-calendar-row.archived {
            background-color: rgba(156, 163, 175, 0.1);
            border-left-color: #9ca3af;
        }

        /* Border for first archived user row in calendar */
        .first-archived-row .calendar-day {
            border-top: 1px solid #ccc !important;
            height: 30px !important;
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

        .mains{
            border: 1px solid #D1D5DB;
            border-radius: 4px;
        }

        .content{
            margin-top: 0px !important;
        }

        /* Hide number input spin buttons */
        .inputss::-webkit-outer-spin-button,
        .inputss::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .inputss[type=number] {
            -moz-appearance: textfield;
        }
        
        /* Disable focus on readonly inputs */
        .inputss[readonly] {
            pointer-events: none;
            cursor: default;
        }

        /* Sortable styles */
        .team-member-row.ui-sortable-helper {
            background-color: #f9fafb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            opacity: 0.9;
            z-index: 1000;
        }

        .team-member-row.ui-sortable-placeholder {
            background-color: #e5e7eb;
            visibility: visible !important;
            height: 50px;
            border: 2px dashed #9ca3af;
        }
    </style>

    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>
    <script src='https://cdn.tailwindcss.com'></script>
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/date-fns/2.30.0/date-fns.min.js'></script>
    <script src='https://unpkg.com/lucide@latest'></script>
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>
</head>
<body class="bg-gray-50" x-data="{
    showAddUserModal: false,
    showArchivedUsers: false
}" x-init="$watch('showArchivedUsers', value => { setTimeout(() => { if (typeof updateLastTaskItemBorder === 'function') updateLastTaskItemBorder(); }, 400); });">
    @include('front.nav')
    <div class="mx-auto p-4 shadow rounded-lg border" style="background: #fff !important; border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
        <div class="content p-2" style="padding-left: 0px !important; display: block; margin-bottom: 40px;">
            <div style="float: left; margin-top: 6px;">
                <h5 style="font-size: 20px; font-weight: 600; padding-left: 0px;">Team Members</h5>
            </div>
            <div class="flex items-center " style="float: right;">
                <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;">
                    <i class="fas fa-home" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; color: #000;"></i>
                </button>
                <div style="border: 1px solid #eee; border-radius: 4px; margin-right: 8px; height: 34px; width: 170px; display:flex; justify-content: center;">
                    <a href="/resources" class="toggle-btn" style="border-top-left-radius: 4px;border-bottom-left-radius: 4px;">Daily</a>
                    <a href="/resources/weekly" class="toggle-btn active" style="border-top-right-radius: 4px;border-bottom-right-radius: 4px;">Weekly</a>
                </div>
                <style>
                    .toggle-btn {
                        background: #000;
                        color: #fff;
                        border: 1px solid #000;
                        padding: 5px 19px;
                        font-size: 13px;
                        font-weight: 500;
                        margin-right: 0;
                        transition: background 0.2s, color 0.2s;
                        align-items: center;
                        text-align: center;
                        height: 33px;
                        width: 170px;
                    }
                    .toggle-btn:not(.active) {
                        background: transparent;
                        color: #000;
                    }
                    .toggle-btn.active {
                        background: #000;
                        color: #fff;
                        border: 1px solid #000;
                    }
                    .toggle-btn:focus {
                        outline: none;
                    }
                </style>

                <button 
                    @click="showArchivedUsers = !showArchivedUsers" 
                    class="ml-2 px-4 py-2 rounded bg-black text-white"
                    style="font-size: 13px; padding:0.4rem 1rem;"
                    x-text="showArchivedUsers ? 'Hide Archived Users' : 'Show Archived Users'"
                ></button>
            </div>
        </div>
        <div class="content mains" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
            <div class="task-list" style="border-right: 0px; padding-right: 0px; padding-top: 0px">
                <div class="task-header" style="margin-bottom: 0px; padding: 10px; padding-right: 0px; border-top-left-radius: 4px;">
                    <span style="width: 40%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;">
                        Team Member
                    </span>
                    <span style="width: 22%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                        Role
                    </span>
                    <span style="width: 15%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                        Cost
                    </span>
                    <span style="width: 15%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 6px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                        Hours
                    </span>
                    <span class="custom-offset" style="width: 8%; font-size: 12px; display: inherit; padding-left: 10px; padding-top: 17px; padding-bottom: 17px; text-align: center;">
                    </span>
                </div>
                <div class="not-archived">
                    @foreach ($data as $item)
                        @if ($item->is_archived == 0)
                            <div class="task-item team-member-row data-id-{{ $item->id }}" data-user-id="{{ $item->id }}" style="position: unset">
                                <span style="width: 40%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; align-items: center;">
                                    <span class="drag-handle" style="display: inline-flex; align-items: center; margin-right: 10px; margin-left: 3px; cursor: move;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0; display: block;"><rect width="24" height="2.5" y="5.5" fill="#000"/><rect width="24" height="2.5" y="10.75" fill="#000"/><rect width="24" height="2.5" y="16" fill="#000"/></svg>
                                    </span>
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
                                <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">{{ number_format($item->hourly_rate*$es) }}</span>
                                <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                    {{ number_format($es) }}
                                </span>
                                <span style="width: 8%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                    <div style="width: 12px; height: 12px; border-radius: 50%; margin: 0 auto; background-color: #ccc;"></div>
                                </span>
                            </div>
                            
                            <div class="member-projects" data-user-id="{{ $item->id }}">
                                @foreach ($item->projects as $project)
                                    @if ($project->project->is_archived == 0)                                            
                                        <div class="task-item project-row hierarchy-level-1" data-project-id="{{ $project->project_id }}" data-user-id="{{ $item->id }}">
                                            <span style="padding-left: 17px; width: 40%; font-size: 11px; display: inline-flex; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; align-items: center;">
                                                {{ $project->project->name ?? 'Unknown Project' }}
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
                                <span class="drag-handle" style="display: inline-flex; align-items: center; margin-right: 10px; margin-left: 3px; cursor: move;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0; display: block;"><rect width="24" height="2.5" y="5.5" fill="#000"/><rect width="24" height="2.5" y="10.75" fill="#000"/><rect width="24" height="2.5" y="16" fill="#000"/></svg>
                                </span>
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
                            <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">{{ $item->hourly_rate*$es }}</span>
                            <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                {{ $es }}
                            </span>
                            <span style="width: 8%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                <div style="width: 12px; height: 12px; border-radius: 50%; margin: 0 auto; background-color: #ccc;"></div>
                            </span>
                        </div>
                        
                        <div class="member-projects" data-user-id="{{ $item->id }}">
                            @foreach ($item->projects as $project)
                                @if ($project->project->is_archived == 0)                                            
                                    <div class="task-item project-row hierarchy-level-1" data-project-id="{{ $project->project_id }}" data-user-id="{{ $item->id }}">
                                        <span style="padding-left: 17px; width: 40%; font-size: 11px; display: inline-flex; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; align-items: center;">
                                            {{ $project->project->name ?? 'Unknown Project' }}
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
            </div>
            <div class="scroll-container sss" style="border-bottom-right-radius: 4px; border-top-right-radius: 4px;">
                <div class="relative mt-4" style="margin-top: 0px !important;">
                    <div class="calendar-container second-calender">
                        <!-- JavaScript will populate the months and weeks here -->
                    </div>
                    <div class="not-archived" style="margin-top: -4px">
                        @foreach ($data as $item)
                        @if ($item->is_archived == 0)
                            <div class="second-input data-id-{{ $item->id }}" data-user-id="{{ $item->id }}"></div>
                            
                            @php
                                $userProjects = \App\Models\Project::whereHas('members', function($query) use ($item) {
                                    $query->where('user_id', $item->id)->where('is_archived', 0);
                                })->get();
                            @endphp
                            @foreach($userProjects as $project)
                                <div class="second-input project-calendar-row member-project-{{ $item->id }}" data-project-id="{{ $project->id }}" data-user-id="{{ $item->id }}" style="display: none;"></div>
                            @endforeach
                        @endif
                        @endforeach
                    </div>
                    <div class="archied" x-show="showArchivedUsers" x-transition style="margin-top: -4px;">
                        @foreach ($data as $item)
                        @if ($item->is_archived == 1)
                            <div class="second-input data-id-{{ $item->id }}" data-user-id="{{ $item->id }}"></div>
                            
                            @php
                                $userProjects = \App\Models\Project::whereHas('members', function($query) use ($item) {
                                    $query->where('user_id', $item->id)->where('is_archived', 0);
                                })->get();
                            @endphp
                            @foreach($userProjects as $project)
                                <div class="second-input project-calendar-row member-project-{{ $item->id }}" data-project-id="{{ $project->id }}" data-user-id="{{ $item->id }}" style="display: none;"></div>
                            @endforeach
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            const calendarContainer = $('.calendar-container');
            const startDate = new Date('2025-01-01');
            const endDate = new Date('2026-01-01');
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            function renderCalendar() {
                calendarContainer.empty();
                
                // Align start date to Monday
                const alignedStartDate = new Date(startDate);
                const startDayOfWeek = alignedStartDate.getDay();
                const daysToMonday = (startDayOfWeek === 0 ? -6 : 1 - startDayOfWeek);
                alignedStartDate.setDate(alignedStartDate.getDate() + daysToMonday);
                
                const currentDate = new Date(alignedStartDate);
                
                function getISOWeekNumber(date) {
                    const tempDate = new Date(date.getTime());
                    tempDate.setHours(0, 0, 0, 0);
                    tempDate.setDate(tempDate.getDate() + 3 - (tempDate.getDay() + 6) % 7);
                    const week1 = new Date(tempDate.getFullYear(), 0, 4);
                    return 1 + Math.round(((tempDate.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
                }
                
                let monthSegments = [];
                let inp = ``;
                
                while (currentDate < endDate) {
                    const weekStartDate = new Date(currentDate);
                    const weekStartDay = weekStartDate.getDate().toString().padStart(2, '0');
                    const weekStartMonth = (weekStartDate.getMonth() + 1).toString().padStart(2, '0');
                    const weekStartYear = weekStartDate.getFullYear();
                    const weekNumber = getISOWeekNumber(currentDate);
                    
                    let weekMonthDistribution = {};
                    
                    for (let dayOffset = 0; dayOffset < 7; dayOffset++) {
                        const dayInWeek = new Date(currentDate);
                        dayInWeek.setDate(dayInWeek.getDate() + dayOffset);
                        
                        if (dayInWeek >= endDate) break;
                        
                        const dayMonth = dayInWeek.getMonth();
                        const dayYear = dayInWeek.getFullYear();
                        const monthKey = `${dayYear}-${String(dayMonth).padStart(2, '0')}`;
                        
                        weekMonthDistribution[monthKey] = (weekMonthDistribution[monthKey] || 0) + 1;
                    }
                    
                    Object.keys(weekMonthDistribution).forEach(monthKey => {
                        const [year, month] = monthKey.split('-').map(Number);
                        const dayCount = weekMonthDistribution[monthKey];
                        const width = Math.round((dayCount / 7) * 32);
                        
                        if (monthSegments.length > 0 && 
                            monthSegments[monthSegments.length - 1].monthKey === monthKey) {
                            monthSegments[monthSegments.length - 1].width += width;
                        } else {
                            monthSegments.push({
                                monthKey: monthKey,
                                year: year,
                                month: month,
                                width: width
                            });
                        }
                    });

                    inp += `<input readonly type="text" class="calendar-day inputss" style="min-width: 32px;" data-date="${weekStartYear}-${weekStartMonth}-${weekStartDay}" data-week="${weekNumber}">`;
                    
                    currentDate.setDate(currentDate.getDate() + 7);
                }
                
                const monthHeaderRow = $('<div class="month-header-row"></div>');
                const weekCellRow = $('<div class="week-cell-row"></div>');
                
                const currentDate2 = new Date(alignedStartDate);
                let totalWeeks = 0;
                while (currentDate2 < endDate) {
                    totalWeeks++;
                    currentDate2.setDate(currentDate2.getDate() + 7);
                }
                const totalExpectedWidth = totalWeeks * 32;
                
                let accumulatedWidth = 0;
                monthSegments.forEach(function(segment, index) {
                    let monthWidth = segment.width;
                    
                    if (index === monthSegments.length - 1) {
                        monthWidth = totalExpectedWidth - accumulatedWidth;
                    }
                    
                    const monthHeader = $('<div class="month-header"></div>');
                    monthHeader.text(monthNames[segment.month] + ' ' + segment.year);
                    monthHeader.css({
                        'width': monthWidth + 'px',
                        'display': 'inline-block',
                        'vertical-align': 'top'
                    });
                    monthHeaderRow.append(monthHeader);
                    
                    accumulatedWidth += monthWidth;
                });
                
                const currentDate3 = new Date(alignedStartDate);
                while (currentDate3 < endDate) {
                    const weekStartDate = new Date(currentDate3);
                    const weekStartDay = weekStartDate.getDate().toString().padStart(2, '0');
                    const weekStartMonth = (weekStartDate.getMonth() + 1).toString().padStart(2, '0');
                    const weekStartYear = weekStartDate.getFullYear();
                    const weekNumber = getISOWeekNumber(currentDate3);
                    
                    // Convert week 53 to week 1 for display
                    const displayWeekNumber = weekNumber === 53 ? 1 : weekNumber;
                    
                    const weekCell = $(`<div class="calendar-day" data-date="${weekStartYear}-${weekStartMonth}-${weekStartDay}" data-week="${weekNumber}">W${displayWeekNumber}</div>`);
                    weekCellRow.append(weekCell);
                    
                    currentDate3.setDate(currentDate3.getDate() + 7);
                }
                
                calendarContainer.append(monthHeaderRow);
                calendarContainer.append(weekCellRow);

                // Populate user-level calendar rows
                $('.second-input:not(.project-calendar-row)').each(function() {
                    const userId = $(this).attr('data-user-id');
                    let rowInp = inp;
                    if (userId) {
                        rowInp = rowInp.replace(/data-date="/g, `data-user-id="${userId}" data-date="`);
                    }
                    $(this).append(rowInp);
                });
                
                // Populate project-level calendar rows
                $('.second-input.project-calendar-row').each(function() {
                    const userId = $(this).attr('data-user-id');
                    const projectId = $(this).attr('data-project-id');
                    let rowInp = inp;
                    if (userId && projectId) {
                        rowInp = rowInp.replace(/data-date="/g, `data-user-id="${userId}" data-project-id="${projectId}" data-date="`);
                    }
                    $(this).append(rowInp);
                });
            }

            renderCalendar();
            
            // Function to populate time tracking data from database
            async function populateTimeTrackingData() {
                try {
                    const response = await fetch('/estimated-time-tracking/get', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    });

                    if (!response.ok) {
                        console.error('Failed to fetch time tracking data:', response.statusText);
                        return;
                    }

                    const data = await response.json();

                    // Create a map to store weekly totals
                    const weeklyTotals = {};

                    // Iterate over the data and aggregate hours by week
                    data.forEach(item => {
                        const { user_id, date, time, project_id } = item;
                        
                        // Parse the date and get the week start date
                        const itemDate = new Date(date);
                        const weekNumber = getISOWeekNumber(itemDate);
                        
                        // Get Monday of this week
                        const dayOfWeek = itemDate.getDay();
                        const diff = itemDate.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
                        const monday = new Date(itemDate.setDate(diff));
                        const weekStartDay = monday.getDate().toString().padStart(2, '0');
                        const weekStartMonth = (monday.getMonth() + 1).toString().padStart(2, '0');
                        const weekStartYear = monday.getFullYear();
                        const weekKey = `${weekStartYear}-${weekStartMonth}-${weekStartDay}`;

                        // Convert time from "H:MM" format to decimal
                        const decimalTime = convertTimeToDecimal(time);
                        if (decimalTime === 0) return;

                        // Create unique keys for different aggregation levels
                        const userKey = `${user_id}-${weekKey}`;
                        const projectKey = `${user_id}-${project_id}-${weekKey}`;

                        // Aggregate for user level (no project filter)
                        if (!weeklyTotals[userKey]) {
                            weeklyTotals[userKey] = { total: 0, userId: user_id, weekKey: weekKey };
                        }
                        weeklyTotals[userKey].total += decimalTime;

                        // Aggregate for project level
                        if (project_id) {
                            if (!weeklyTotals[projectKey]) {
                                weeklyTotals[projectKey] = { total: 0, userId: user_id, projectId: project_id, weekKey: weekKey };
                            }
                            weeklyTotals[projectKey].total += decimalTime;
                        }
                    });

                    // Populate the input fields with weekly totals
                    Object.values(weeklyTotals).forEach(weekData => {
                        const selector = weekData.projectId 
                            ? `.inputss[data-user-id="${weekData.userId}"][data-project-id="${weekData.projectId}"][data-date="${weekData.weekKey}"]`
                            : `.inputss[data-user-id="${weekData.userId}"][data-date="${weekData.weekKey}"]:not([data-project-id])`;
                        
                        const inputField = document.querySelector(selector);
                        if (inputField && weekData.total > 0) {
                            inputField.value = weekData.total.toFixed(2);
                        }
                    });
                } catch (error) {
                    console.error('Error fetching time tracking data:', error);
                }
            }

            // Helper function to convert time from H:MM to decimal
            function convertTimeToDecimal(time) {
                if (!time || time === '0:00') return 0;
                const [hours, minutes] = time.split(':').map(Number);
                return hours + (minutes / 60);
            }

            // Helper function to get ISO week number
            function getISOWeekNumber(date) {
                const tempDate = new Date(date.getTime());
                tempDate.setDate(tempDate.getDate() + 3 - (tempDate.getDay() + 6) % 7);
                const week1 = new Date(tempDate.getFullYear(), 0, 4);
                return 1 + Math.round(((tempDate.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
            }

            // Call populate function after calendar is rendered
            populateTimeTrackingData();
            
            function scrollToCurrentMonth() {
                const today = new Date();
                const currentYear = today.getFullYear();
                const currentMonth = today.getMonth() + 1;
                const currentDay = today.getDate();
                
                const todayStr = `${currentYear}-${currentMonth.toString().padStart(2, '0')}-${currentDay.toString().padStart(2, '0')}`;
                const $todayElement = $(`.calendar-day[data-date="${todayStr}"]`);
                
                if ($todayElement.length > 0) {
                    const scrollContainer = $('.scroll-container');
                    const elementLeft = $todayElement.position().left;
                    const cellWidth = $todayElement.outerWidth();
                    const weekOffset = cellWidth * 7;
                    const scrollPosition = elementLeft - weekOffset;
                    scrollContainer.scrollLeft(Math.max(0, scrollPosition));
                }
            }
            
            scrollToCurrentMonth();
        });

        lucide.createIcons();

        $(document).on('click', '.expand-arrow', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const arrow = $(this);
            const isExpanded = arrow.hasClass('expanded');
            const targetClass = arrow.data('target');
            const targetId = arrow.data('id');
            
            if (isExpanded) {
                arrow.removeClass('expanded').html('▶');
            } else {
                arrow.addClass('expanded').html('▼');
            }
            
            if (targetClass === 'member-projects') {
                const projectsDiv = $(`.member-projects[data-user-id="${targetId}"]`);
                const projectCalendarRows = $(`.project-calendar-row.member-project-${targetId}`);
                
                if (isExpanded) {
                    projectsDiv.slideUp(300, function() {
                        updateLastTaskItemBorder();
                    });
                    projectCalendarRows.slideUp(300, function() {
                        $(this).hide();
                    });
                } else {
                    projectsDiv.slideDown(300, function() {
                        updateLastTaskItemBorder();
                    });
                    projectCalendarRows.slideDown(300, function() {
                        $(this).show();
                    });
                }
            }
        });

        $(document).ready(function () {
            $('.scroll-container').each(function () {
                const scrollContainer = this;
                let isDragging = false;
                let startX;
                let scrollLeft;

                function startDrag(e) {
                    if (e.button !== 0) return;
                    isDragging = true;
                    startX = e.pageX;
                    scrollLeft = scrollContainer.scrollLeft;
                    scrollContainer.style.cursor = 'grabbing';
                }

                function duringDrag(e) {
                    if (!isDragging) return;
                    e.preventDefault();
                    const x = e.pageX;
                    const walk = x - startX;
                    scrollContainer.scrollLeft = scrollLeft - walk;
                }

                function endDrag() {
                    isDragging = false;
                    scrollContainer.style.cursor = 'grab';
                }

                $(scrollContainer).find('.calendar-container.second-calender, .second-input').on('mousedown', startDrag);
                $(scrollContainer).on('mousemove', duringDrag);
                $(scrollContainer).on('mouseup mouseleave', endDrag);
            });
            
            // Mouse wheel scroll handler
            $('.scroll-container').on('wheel', function(e) {
                const deltaY = e.originalEvent.deltaY;
                const deltaX = e.originalEvent.deltaX;
                
                if (Math.abs(deltaY) > Math.abs(deltaX)) {
                    e.preventDefault();
                    const scrollAmount = deltaY * 0.8;
                    const currentScroll = $(this).scrollLeft();
                    const newScroll = currentScroll + scrollAmount;
                    $(this).scrollLeft(newScroll);
                }
            });
        });

        // Function to update border-radius and border-bottom on last visible task-item
        function updateLastTaskItemBorder() {
            // Remove border-radius and border-bottom from all task-items
            $('.task-item').css({
                'border-bottom-left-radius': '',
                'border-bottom': '1px solid #eee'
            });
            
            // Find the last visible team-member-row in not-archived section
            let lastNotArchived = $('.task-list .not-archived > .team-member-row:visible').last();
            
            // Check if there are any expanded projects after the last team member
            if (lastNotArchived.length) {
                let userId = lastNotArchived.data('user-id');
                let memberProjects = $(`.member-projects[data-user-id="${userId}"]`);
                
                // If projects are expanded and visible, find the last project row
                if (memberProjects.is(':visible') && memberProjects.find('.project-row:visible').length > 0) {
                    let lastProject = memberProjects.find('.project-row:visible').last();
                    lastProject.css({
                        'border-bottom-left-radius': '4px',
                        'border-bottom': 'none'
                    });
                } else {
                    // No expanded projects, so the team member row is last
                    lastNotArchived.css({
                        'border-bottom-left-radius': '4px',
                        'border-bottom': 'none'
                    });
                }
            }
            
            // If archived users are shown, find the last visible in archived section
            if ($('.task-list .archied').is(':visible')) {
                let lastArchived = $('.task-list .archied > .team-member-row:visible').last();
                
                if (lastArchived.length) {
                    let userId = lastArchived.data('user-id');
                    let memberProjects = $(`.archied .member-projects[data-user-id="${userId}"]`);
                    
                    // Reset the not-archived section since archived is showing
                    if (lastNotArchived.length) {
                        let notArchivedUserId = lastNotArchived.data('user-id');
                        let notArchivedProjects = $(`.not-archived .member-projects[data-user-id="${notArchivedUserId}"]`);
                        if (notArchivedProjects.is(':visible') && notArchivedProjects.find('.project-row:visible').length > 0) {
                            notArchivedProjects.find('.project-row:visible').last().css({
                                'border-bottom-left-radius': '',
                                'border-bottom': '1px solid #eee'
                            });
                        } else {
                            lastNotArchived.css({
                                'border-bottom-left-radius': '',
                                'border-bottom': '1px solid #eee'
                            });
                        }
                    }
                    
                    // Check if archived section has expanded projects
                    if (memberProjects.is(':visible') && memberProjects.find('.project-row:visible').length > 0) {
                        let lastProject = memberProjects.find('.project-row:visible').last();
                        lastProject.css({
                            'border-bottom-left-radius': '4px',
                            'border-bottom': 'none'
                        });
                    } else {
                        lastArchived.css({
                            'border-bottom-left-radius': '4px',
                            'border-bottom': 'none'
                        });
                    }
                }
            }
        }

        $('#home').on('click', function() {
            const today = new Date();
            const scrollContainer = $('.scroll-container');
            const todayStr = `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
            const $todayElement = $(`.calendar-day[data-date="${todayStr}"]`);
            
            if ($todayElement.length > 0) {
                const elementLeft = $todayElement.position().left;
                const cellWidth = $todayElement.outerWidth();
                const weekOffset = cellWidth * 7;
                const scrollPosition = elementLeft - weekOffset;
                scrollContainer.animate({
                    scrollLeft: Math.max(0, scrollPosition)
                }, 400);
            }
        });

        // Call updateLastTaskItemBorder after page loads
        $(document).ready(function() {
            setTimeout(updateLastTaskItemBorder, 100);
        });

        // Team member drag and drop functionality
        $(document).ready(function() {
            // Variable to prevent multiple simultaneous reorders
            var reorderTimeout = null;
            
            // Make the team member lists sortable
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
                items: '> .team-member-row',
                connectWith: '.task-list .not-archived, .task-list .archied',
                start: function(event, ui) {
                    // Store the member-projects element that follows this team member
                    var userId = ui.item.data('user-id');
                    var $memberProjects = $('.member-projects[data-user-id="' + userId + '"]');
                    ui.item.data('memberProjects', $memberProjects);
                    ui.item.data('memberProjectsParent', $memberProjects.parent());
                },
                stop: function(event, ui) {
                    // Move the associated member-projects div after the team member
                    var userId = ui.item.data('user-id');
                    var $memberProjects = ui.item.data('memberProjects');
                    
                    if ($memberProjects && $memberProjects.length > 0) {
                        // Detach and reattach the projects div right after the team member
                        $memberProjects.detach().insertAfter(ui.item);
                    } else {
                        // Try to find it again in case it got lost
                        $memberProjects = $('.member-projects[data-user-id="' + userId + '"]');
                        if ($memberProjects.length > 0) {
                            $memberProjects.detach().insertAfter(ui.item);
                        }
                    }
                    
                    // Debounce the reorder to avoid double-triggering
                    clearTimeout(reorderTimeout);
                    reorderTimeout = setTimeout(function() {
                        performReorder();
                    }, 150);
                }
            });
            
            // Function to perform the actual reordering
            function performReorder() {
                // Get the updated order of team members
                var memberOrder = [];
                
                // Get order from non-archived section
                $('.task-list .not-archived > .team-member-row').each(function() {
                    var userId = $(this).data('user-id');
                    memberOrder.push(userId);
                });
                
                // Get order from archived section (if visible)
                $('.task-list .archied > .team-member-row').each(function() {
                    var userId = $(this).data('user-id');
                    memberOrder.push(userId);
                });
                
                // Reorder the calendar input rows accordingly
                reorderCalendarInputs(memberOrder);
            }
            
            // Function to reorder calendar inputs based on team member order
            function reorderCalendarInputs(memberOrder) {
                // Collect all calendar elements grouped by user
                var inputGroups = {};
                
                // Collect from both archived and not-archived containers
                var $notArchivedContainer = $('.scroll-container .not-archived');
                var $archivedContainer = $('.scroll-container .archied');
                
                [$notArchivedContainer, $archivedContainer].forEach(function($container) {
                    $container.children('.second-input').each(function() {
                        var $element = $(this);
                        var userId = $element.data('user-id');
                        
                        if (!userId) return;
                        
                        // Initialize group if not exists
                        if (!inputGroups[userId]) {
                            inputGroups[userId] = [];
                        }
                        
                        // Add element to group
                        inputGroups[userId].push($element.detach());
                    });
                });
                
                // Reorder elements according to memberOrder
                memberOrder.forEach(function(userId) {
                    if (inputGroups[userId]) {
                        // Determine target container based on archived status
                        var $teamMember = $('.task-list .team-member-row[data-user-id="' + userId + '"]');
                        var isArchived = $teamMember.closest('.archied').length > 0;
                        var $targetContainer = isArchived ? $archivedContainer : $notArchivedContainer;
                        
                        // Append all elements for this user
                        inputGroups[userId].forEach(function($element) {
                            $targetContainer.append($element);
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>
