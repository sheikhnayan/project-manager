<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projects - Project Management</title>

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
            margin-top: 25rem;
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
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .second-input .calendar-day{
            height: 30px !important;
            font-size: 10px;
            padding: 0px !important;
            width: 24px !important;
            border-right: 1px solid #eee;
            border-top: 1px solid #eee;
            border-bottom: unset;
            border-left: unset;
            box-sizing: border-box;
        }

        .draggable[data-task="task1"] {
            margin-top: 3px !important;
        }

        .second-input{
            display: flex;
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
            width: 24px !important;
            height: 20px !important;
            text-align: center;
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

        .task-item:last-child{
            border-bottom-left-radius: 4px;
        }

        .task-item img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

                        .task-circle {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background-color: #000;
            margin-right: 10px;
            display: inline-block;
        }

        .team-member-row {
            cursor: move;
        }

        .team-member-row.ui-sortable-helper {
            background-color: #f3f4f6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .team-member-row.ui-sortable-placeholder {
            background-color: #e5e7eb;
            visibility: visible !important;
            height: 40px;
        }

        .drag-handle {
            color: #9ca3af;
            margin-right: 8px;
            cursor: move;
            font-size: 12px;
        }

        .drag-handle:hover {
            color: #4b5563;
        }
        .holiday{
            color: #d32f2f;
            background-color: #f7f7f7 !important;
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

        .alert-danger{
            background: red !important
        }

        .today-line {
            position: absolute;
            top: -25px;
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

        .archied{
            display: none;
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

        .open-edit-task-modal{
            font-size: 13px;
            background: #fff !important;
            color: #4b5563 !important;
        }

        /* Hide number input arrows/spinners */
        .inputss::-webkit-outer-spin-button,
        .inputss::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .inputss[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
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
<body class="bg-gray-50">
    @include('front.nav')
    <div class="mx-auto shadow border rounded-lg overflow-hidden">
        <div class="p-4 rounded-lg" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
            <div class="content" style="display: block; margin-bottom: 40px;">
                <div style="float: left; margin-top: 6px;">
                    <h5 style="font-size: 20px; font-weight: 600; margin-left: 7px;">{{ $data->name }}</h5>
                </div>
                <div class="flex items-center " style="float: right;">
                            <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;">
                                <img src="{{ asset('house.png') }}" style="border: 1px solid #000;padding: 10px 12px;border-radius: 4px;border-color: #eee; ">
                            </button>
                            <div style="border: 1px solid #eee; border-radius: 4px;  margin-right: 8px; height: 34px;width: 170px; display:flex;justify-content: center;">
                                <a href="/projects" class="toggle-btn active" style="border-top-left-radius: 4px;border-bottom-left-radius: 4px;">Daily</a>
                                <a href="/projects/weekly/{{ $data->id }}" class="toggle-btn" style="border-top-right-radius: 4px;border-bottom-right-radius: 4px;">Weekly</a>
                            </div>
                            <style>
                                .toggle-btn {
                                        background: #000;
                                        color: #fff;
                                        border: 1px solid #000;

                                        padding: 7px 19px;
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
                            <script>
                                // Toggle active class on click
                                $('#toggleDaily, #toggleWeekly').on('click', function() {
                                    $('.toggle-btn').removeClass('active');
                                    $(this).addClass('active');
                                    // Add your view switching logic here if needed
                                });
                            </script>
                            <a class="bg-black text-white px-4 py-2 rounded" id="addMemberButton" style="font-size: 13px; padding:0.4rem 1rem; cursor: pointer; margin-right: 8px;">+  Add Member</a>
                            {{-- <a href="/projects/create" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.4rem 1rem;">+  Add Project</a> --}}
                </div>
            </div>
            <div class="content mains">
                <div class="task-list" style="padding-right: 0px; margin-top: 0px;">
                    <div class="task-header" style="margin-bottom: 0px; border-top-left-radius: 4px; padding-right: 0px;">
                        <span style="width: 50%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProject">
                            Title
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortProjectIcon" alt="">
                        </span>
                        <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Date</span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Budget</span>
                        <span class="text-center add-task" style="font-size: 12px; width: 10%; padding-top: 17px; padding-bottom: 17px; text-align: center" id="addTaskButton"><i class="fas fa-plus"></i></span>
                    </div>
                    @foreach ($data->tasks as $key => $item)
                        @if ($item->start_date != null)
                            <div class="task-item" data-task="task{{ $key + 1 }}" style="margin-bottom: 0px; border-bottom: 1px solid #eee; margin-left: 0px; background: #fff;">
                                {{-- <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User 1"> --}}
                                <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px;">
                                    <div class="task-circle"></div>
                                    {{ $item->name }}
                                </span>
                                <span class="start-{{ $item->id }}" style="width: 25%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">{{ formatDate($item->start_date) }} </span>
                                <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                    <input type="text" name="budget_total" data-task-id="{{ $item->id }}" value="{{ formatCurrency(round($item->budget_total)) }}" class="budget_total" style="width: 100%; font-size: 12px; text-align: center;">
                                </span>
                                <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                                    <button class="open-edit-task-modal bg-blue-500 text-white px-2 py-1 rounded"
                                            data-task-id="{{ $item->id }}"
                                            data-task-name="{{ $item->name }}"
                                            data-task-date="{{ formatDate($item->start_date) }} - {{ formatDate($item->end_date) }}"
                                            data-task-budget="{{ $item->budget_total }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="scroll-container">
                    <div class="relative">
                        <div class="calendar-container">
                            <!-- JavaScript will populate the months and dates here -->
                        </div>
                        @php

                            $height = 0;

                            foreach ($data->tasks as $key => $value) {
                                # code...
                                if ($value->start_date != null) {
                                    # code...
                                    $height +=1;
                                }
                            }

                        @endphp
                        <div class="gantt-bar-container" style="height: {{ (24*$height) +5}}px">
                            @foreach ($data->tasks as $key => $item)
                            @if ($item->start_date != null)
                                <div class="draggable bg-blue-600 text-white text-center py-1 px-2" data-task-id="{{ $item->id }}" data-task="task{{$key + 1}}" style="left: calc(3.225% * 5); position: absolute; padding: 0px; padding-top: 1px;" data-start-date="{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}" data-end-date="{{ \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') }}">
                                    <span>T{{ $key + 1 }}</span>
                                    <div class="ui-resizable-handle ui-resizable-e"></div>
                                    <div class="ui-resizable-handle ui-resizable-w"></div>
                                </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="content us" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
                <div class="task-list" style="border-right: 0px; padding-right: 0px; padding-top: 0px">
                    <div class="task-header" style="margin-bottom: 0px; padding: 10px; padding-right: 0px;">
                        <span style="width: 50%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProjectuser">
                            Name
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortProjectIcon" alt="">
                        </span>
                        <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Cost</span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Hours</span>
                        <span style="font-size: 12px; width: 10%; padding-top: 17px; padding-bottom: 17px; text-align: center; border-right: 1px solid #eee;"> <i class="fas fa-eye show-user" data-type="show"></i> </span>
                        {{-- <span class="text-center font-size: 12px; add-task" style="width: 10%;" id="addMemberButton"><i class="fas fa-plus"></i></span> --}}
                    </div>
                    <div class="not-archived names" id="team-members-list">
                        @foreach ($data->members as $item)
                            @if ($item->archieve == 0)
                                <div class="task-item team-member-row data-id-{{ $item->id }}" data-task="task{{ $item->task_id }}" data-member-id="{{ $item->id }}" data-user-id="{{ $item->user_id }}" style="position: unset">
                                    <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px;">
                                        <img class="drag-handle" src="{{ asset('dots.svg') }}" style="margin-right: 5px;">
                                        <img src="{{ $item->user->profile_image_url ? asset('storage/'.$item->user->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}">
                                        {{ $item->user->name }}
                                    </span>
                                        @php
                                            $es = DB::table('estimated_time_entries')->where('project_id',$data->id)->where('user_id',$item->user_id)->sum('hours');
                                        @endphp
                                    <span style="width: 25%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->user_id }}">{{ formatCurrency($item->user->hourly_rate*$es) }}</span>
                                    <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-hour-{{ $item->user_id }}">
                                        {{ number_format($es) }}
                                    </span>
                                    @if ($item->archieve == 0)
                                    <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye  hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                    @else
                                    <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye-slash" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="archied">
                        @foreach ($data->members as $item)
                            <div class="task-item data-id-{{ $item->id }}" data-task="task{{ $item->task_id }}" style="position: unset">
                                <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px;"><img src="{{ $item->user->profile_image_url ? asset('storage/'.$item->user->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}" alt="User 4"> {{ $item->user->name }}</span>
                                    @php
                                        $es = DB::table('estimated_time_entries')->where('project_id',$data->id)->where('user_id',$item->user_id)->sum('hours');
                                    @endphp
                                <span style="width: 25%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->user_id }}">{{ formatCurrency($item->user->hourly_rate*$es) }}</span>
                                <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-hour-{{ $item->user_id }}">
                                    {{ number_format($es) }}
                                </span>
                                @if ($item->archieve == 0)
                                <span {{ $item->archieve }} style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                @else
                                <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fa-eye-slash  hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="scroll-container sss">
                    <div class="relative mt-4">
                        <div class="calendar-container second-calender">
                            <!-- JavaScript will populate the months and dates here -->
                        </div>
                        <div class="not-archived" style="margin-top: -4px" id="team-time-inputs">
                            @foreach ($data->members as $item)
                            @if ($item->archieve == 0)
                                <div class="second-input time-input-row data-id-{{ $item->id }}" data-task-id="{{ $item->task_id }}" data-user-id="{{ $item->user_id }}" data-member-id="{{ $item->id }}"></div>
                            @endif
                            @endforeach
                        </div>
                        <div class="archied" style="margin-top: -4px">
                            @foreach ($data->members as $item)
                                <div class="second-input data-id-{{ $item->id }}" data-task-id="{{ $item->task_id }}" data-user-id="{{ $item->user_id }}"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="fetch">
            @include('front.estimate',['data' => $data])
        </div>
    </div>

    <input type="hidden" id="st_date" value={{ $data->start_date }}>
    <input type="hidden" id="project_id" value={{ $data->id }}>
    <input type="hidden" id="en_date" value={{ $data->end_date }}>
    <input type="hidden" id="task_count" value={{ count($data->tasks) }}>
    <input type="hidden" id="date_format" value="{{ globalSettings('date_format') }}">

    <script>
        // Function to format date according to user settings
        function formatDateForDisplay(dateString) {
            const dateFormat = document.getElementById('date_format').value;
            const date = new Date(dateString);
            
            switch(dateFormat) {
                case 'Y-m-d':
                    return date.toISOString().split('T')[0];
                case 'm/d/Y':
                    return (date.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                           date.getDate().toString().padStart(2, '0') + '/' + 
                           date.getFullYear();
                case 'd/m/Y':
                    return date.getDate().toString().padStart(2, '0') + '/' + 
                           (date.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                           date.getFullYear();
                case 'd-m-Y':
                    return date.getDate().toString().padStart(2, '0') + '-' + 
                           (date.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                           date.getFullYear();
                case 'M j, Y':
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
                default:
                    return date.toISOString().split('T')[0];
            }
        }

        // Function to format currency with symbol
        function formatCurrency(amount) {
            // You can customize the currency symbol and format here
            const currencySymbol = '$'; // Change this to your preferred currency symbol
            const formattedAmount = Number(amount).toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
            return currencySymbol + formattedAmount;
        }
        
        $(function () {
            const calendarContainer = $('.calendar-container');
            const ganttBarContainer = $('.gantt-bar-container');
            const scrollContainer = $('.scroll-container');
            const st = $('#st_date').val();
            const en = $('#en_date').val();
            let startDate = new Date(st);
            startDate.setDate(startDate.getDate() - 30); // Add this line to go 30 days before
            const endDate = new Date(en);
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

                // Use safer date iteration to prevent date calculation issues
                const currentDate = new Date(startDate);
                while (currentDate <= endDate) {
                    const day = currentDate.getDate().toString().padStart(2, '0'); // Pad day with leading zero
                    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Pad month with leading zero
                    const year = currentDate.getFullYear();
                    const dayOfWeek = currentDate.getDay();
                    const dateString = `${day}`;
                    const dayClass = (dayOfWeek === 0 || dayOfWeek === 6) ? 'calendar-day holiday' : 'calendar-day';

                    if (currentDate.getMonth() !== currentMonth) {
                        // Apply fading color to the current month header
                        const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                        monthContainer.find('.month-header').css('background-color', fadeColor);

                        calendarContainer.append(monthContainer);
                        currentMonth = currentDate.getMonth(); // Update currentMonth to the new month
                        monthContainer = $('<div class="month-container"></div>');
                        monthContainer.append(`<div class="month-header">${monthNames[currentMonth]}</div>`); // Use monthNames[currentMonth]

                        fadeIndex++; // Increment fade index for the next month
                    }

                    monthContainer.append(`<div class="calendar-day ${dayClass}" data-date="${year}-${month}-${day}">${dateString}</div>`);

                    inn = `<input type="number" min="1" max="8" step="1" class="${dayClass} calendar-day inputss" style="min-width: 24px;" onchange="convertTimeInput(this)" oninput="restrictToInteger(this)" data-date="${year}-${month}-${day}">`;

                    inp += inn;
                    
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                // Apply fading color to the last month header
                const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                monthContainer.find('.month-header').css('background-color', fadeColor);

                calendarContainer.append(monthContainer);

                $('.second-input').append(inp);
                
                // Set data-user-id for the newly added input fields
                $('.second-input').each(function() {
                    const userId = $(this).data('user-id');
                    const taskId = $(this).data('task-id');
                    $(this).find('.inputss').attr('data-user-id', userId);
                    $(this).find('.inputss').attr('data-task-id', taskId);
                });
            }




            // Align Gantt bars with the calendar
            function alignGanttBars() {
                const dayWidth = $(".calendar-day").outerWidth(); // Width of a single day or week
                let ganttStartDate = new Date($('#st_date').val()); // Gantt chart start date
                ganttStartDate.setDate(ganttStartDate.getDate() - 30);

                $('.draggable').each(function (index) {
                    const $task = $(this);
                    const taskStartDate = new Date($task.attr('data-start-date'));
                    const taskEndDate = new Date($task.attr('data-end-date'));
                    const taskTop = 30 * index;
                    const taskHeight = $(this).outerHeight();

                    console.log('height: '+taskTop)

                    // Calculate the number of days/weeks from the Gantt start date to the task start and end dates
                    const daysFromStart = Math.floor((taskStartDate - ganttStartDate) / (1000 * 60 * 60 * 24));
                    const taskDuration = Math.floor((taskEndDate - taskStartDate) / (1000 * 60 * 60 * 24)) + 1;

                    // Calculate the left position and width of the task bar
                    const leftPosition = daysFromStart * dayWidth;
                    const barWidth = taskDuration * dayWidth;

                    if (isNaN(taskStartDate) || isNaN(taskEndDate)) {
                        console.warn("Invalid dates for task:", $task.attr('data-task'), taskStartDate, taskEndDate);
                        return; // skip this task
                    }

                    // const taskDuration = Math.floor((taskEndDate - taskStartDate) / (1000 * 60 * 60 * 24)) + 1;

                    if (taskDuration <= 0) {
                        console.warn("Task duration invalid or zero:", $task.attr('data-task'), taskDuration);
                        return;
                    }

                    // Apply the calculated styles to the task bar
                    $task.css({
                        left: `${leftPosition}px`,
                        width: `${barWidth}px`,
                        top: taskTop + 'px',
                        height: '24px'
                    });
                });

                // Add this after setting bars:
                makeDraggableAndResizable();
            }

            function makeDraggableAndResizable() {
                $(".draggable").draggable({
                    axis: "x",
                    grid: [$(".calendar-day").outerWidth(), 0],
                    containment: "document",
                    cancel: ".ui-resizable-handle",
                    start: function(event, ui) {
                        if ($(event.originalEvent.target).hasClass('ui-resizable-handle')) {
                            return false;
                        }
                        const $task = $(this);
                        $task.data("initialLeft", ui.position.left); // Store the initial position
                        $task.data("initialStartDate", $task.attr("data-start-date")); // Store the initial start date
                        $task.data("initialEndDate", $task.attr("data-end-date")); // Store the initial end date
                    },
                    drag: function(event, ui) {
                        if ($(event.originalEvent.target).hasClass('ui-resizable-handle')) {
                            return false;
                        }
                        const $task = $(this);
                        const dayWidth = $(".calendar-day").outerWidth();
                        const startOffset = ui.position.left;
                        const startDate = calculateDateFromOffset(startOffset, dayWidth);
                        $task.attr('data-start-date', startDate);
                    },
                    stop: function(event, ui) {

                        const $task = $(this);
                        const initialStartDate = $task.data("initialStartDate");
                        const initialEndDate = $task.data('initialEndDate');

                        const dayWidth = $(".calendar-day").outerWidth(); // Width of a single day
                        let ganttStartDate = new Date($('#st_date').val()); // Gantt chart start date
                        ganttStartDate.setDate(ganttStartDate.getDate() - 30);

                        // Calculate the start and end offsets
                        const startOffset = ui.position.left;
                        const endOffset = startOffset + $task.outerWidth();

                        // Calculate the current start and end dates
                        const currentStartDate = new Date(ganttStartDate);
                        currentStartDate.setDate(ganttStartDate.getDate() + Math.round(startOffset / dayWidth));

                        const currentEndDate = new Date(ganttStartDate);
                        currentEndDate.setDate(ganttStartDate.getDate() + Math.round(endOffset / dayWidth) - 1);

                        checkDates(initialStartDate, initialEndDate, currentStartDate, currentEndDate, $task.attr("data-task-id"));


                        // checkDates();

                        updateTaskDates($task);
                    }
                }).resizable({
                    handles: {
                        'e': '.ui-resizable-e',
                        'w': '.ui-resizable-w'
                    },
                    grid: [$(".calendar-day").outerWidth(), 0],
                    containment: "document",
                    start: function (event, ui) {
                        ui.element.addClass("ui-resizable-resizing");
                        const $task = $(this);
                        $task.data("initialLeft", ui.position.left); // Store the initial position
                        $task.data("initialWidth", ui.size.width); // Store the initial width
                        $task.data("initialStartDate", $task.attr("data-start-date")); // Store the initial start date
                        $task.data("initialEndDate", $task.attr("data-end-date")); // Store the initial end date
                    },
                    resize: function(event, ui) {
                        const $task = $(this);
                        const dayWidth = $(".calendar-day").outerWidth();
                        let ganttStartDate = new Date($('#st_date').val());
                        ganttStartDate.setDate(ganttStartDate.getDate() - 30);

                        // Snap the start and end offsets to the nearest day boundary
                        const startOffset = Math.round(ui.position.left / dayWidth) * dayWidth;
                        const endOffset = Math.round((ui.position.left + ui.size.width) / dayWidth) * dayWidth;

                        // Calculate the current start and end dates using calculateDateFromOffset
                        const currentStartDate = calculateDateFromOffset(startOffset, dayWidth);
                        const currentEndDate = calculateDateFromOffset(endOffset, dayWidth);

                        // Update the task's attributes dynamically during resizing
                        $task.attr("data-start-date", currentStartDate);
                        $task.attr("data-end-date", currentEndDate);

                        // Debugging (optional)
                        // console.log("Resizing - Start Date:", currentStartDate);
                        // console.log("Resizing - End Date:", currentEndDate);
                    },
                    stop: function (event, ui) {
                        ui.element.removeClass("ui-resizable-resizing");

                        const $task = $(this);
                        const initialStartDate = $task.data("initialStartDate");
                        const initialEndDate = $task.data("initialEndDate");

                        const dayWidth = $(".calendar-day").outerWidth(); // Width of a single day
                        let ganttStartDate = new Date($('#st_date').val()); // Gantt chart start date
                        ganttStartDate.setDate(ganttStartDate.getDate() - 30);
                        // Snap the start and end offsets to the nearest day boundary
                        const startOffset = Math.round(ui.position.left / dayWidth) * dayWidth;
                        const endOffset = Math.round((ui.position.left + ui.size.width) / dayWidth) * dayWidth;

                        // Calculate the start and end offsets
                        // const startOffset = ui.position.left;
                        // const endOffset = startOffset + ui.size.width;

                        // Calculate the final start and end dates
                        const finalStartDate = new Date(ganttStartDate);
                        finalStartDate.setDate(ganttStartDate.getDate() + Math.floor(startOffset / dayWidth));

                        const finalEndDate = new Date(ganttStartDate);
                        finalEndDate.setDate(ganttStartDate.getDate() + Math.floor(endOffset / dayWidth) - 1);

                        // Call the checkDates function to validate the new dates
                        checkDatesforresize(initialStartDate, initialEndDate, finalStartDate, finalEndDate, $task.attr("data-task-id"));

                        // Update the task's attributes with the final dates
                        $task.attr("data-start-date", finalStartDate.toISOString().split('T')[0]);
                        $task.attr("data-end-date", finalEndDate.toISOString().split('T')[0]);

                        // Optionally, call a function to save the updated dates to the server
                        updateTaskDates($task);

                        // Log the final dates (optional)
                        // console.log("Resize Complete - Start Date:", finalStartDate.toISOString().split('T')[0]);
                        // console.log("Resize Complete - End Date:", finalEndDate.toISOString().split('T')[0]);
                    }
                });
            }

            function checkDatesforresize(startDate, endDate, stoppedStartDate, stoppedEndDate, taskId) {
                const stoppedStartDateFormatted = stoppedStartDate.toISOString().split('T')[0];
                const stoppedEndDateFormatted = stoppedEndDate.toISOString().split('T')[0];

                $.ajax({
                    url: '/projects/check-dates',
                    type: 'POST',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        stoppedStartDate: stoppedStartDateFormatted,
                        stoppedEndDate: stoppedEndDateFormatted,
                        task_id: taskId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.overlap) {
                            $task = $('.gantt-bar-container [data-task-id="'+taskId+'"]')

                            // $task.attr('data-start-date', startDate);
                            // $task.attr('data-end-date', endDate);
                            // alignGanttBars();
                            // alert('Dates overlap with another task.');
                            $task.addClass('alert-danger')
                            $.ajax({
                                url: '/projects/save-dates',
                                type: 'POST',
                                data: {
                                    stoppedStartDate: stoppedStartDateFormatted,
                                    stoppedEndDate: stoppedEndDateFormatted,
                                    task_id: taskId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    $task = $('.gantt-bar-container [data-task-id="'+response.data.id+'"]')
                                    $('.start-'+taskId).html(formatDateForDisplay(stoppedStartDateFormatted));
                                }
                            });
                        } else {
                            $.ajax({
                                url: '/projects/save-dates',
                                type: 'POST',
                                data: {
                                    stoppedStartDate: stoppedStartDateFormatted,
                                    stoppedEndDate: stoppedEndDateFormatted,
                                    task_id: taskId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    $task = $('.gantt-bar-container [data-task-id="'+response.data.id+'"]')
                                    $task.removeClass('alert-danger')
                                    $('.start-'+taskId).html(formatDateForDisplay(stoppedStartDateFormatted));
                                    // window.location.reload();
                                }
                            });
                        }
                    }
                });
            }



            function checkDates(startDate, endDate, stoppedstartDate, stoppedendDate, taskId){
                const stoppedStartDateFormatted = stoppedstartDate.toISOString().split('T')[0];
                const stoppedEndDateFormatted = stoppedendDate.toISOString().split('T')[0];
                    $.ajax({
                    url: '/projects/check-dates',
                    type: 'POST',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        stoppedStartDate: stoppedStartDateFormatted,
                        stoppedEndDate: stoppedEndDateFormatted,
                        task_id: taskId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.overlap) {
                            $task = $('.gantt-bar-container [data-task-id="'+taskId+'"]')

                            // $task.attr('data-start-date', startDate);
                            // $task.attr('data-end-date', endDate);
                            // alignGanttBars();
                            // alert('Dates overlap with another task.');
                            $task.addClass('alert-danger')
                            $.ajax({
                                url: '/projects/save-dates',
                                type: 'POST',
                                data: {
                                    stoppedStartDate: stoppedStartDateFormatted,
                                    stoppedEndDate: stoppedEndDateFormatted,
                                    task_id: taskId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    $task = $('.gantt-bar-container [data-task-id="'+response.data.id+'"]')
                                    $('.start-'+taskId).html(formatDateForDisplay(stoppedStartDateFormatted));
                                }
                            });
                        }else{
                            $.ajax({
                                url: '/projects/save-dates',
                                type: 'POST',
                                data: {
                                    stoppedStartDate: stoppedStartDateFormatted,
                                    stoppedEndDate: stoppedEndDateFormatted,
                                    task_id: taskId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    $task = $('.gantt-bar-container [data-task-id="'+response.data.id+'"]')
                                    $task.removeClass('alert-danger')
                                    $('.start-'+taskId).html(formatDateForDisplay(stoppedStartDateFormatted));
                                    // window.location.reload();

                                }
                            });
                        }
                    }
                });
            }


            // Update task dates based on position and width
            function updateTaskDates($task) {
                const dayWidth = $(".calendar-day").outerWidth();
                const startOffset = $task.position().left;
                const endOffset = startOffset + $task.outerWidth();

                const startDate = calculateDateFromOffset(startOffset, dayWidth);
                const endDate = calculateDateFromOffset(endOffset - dayWidth, dayWidth);

                $task.attr('data-start-date', startDate);
                $task.attr('data-end-date', endDate);


            }

            // Calculate date from offset
            function calculateDateFromOffset(offset, dayWidth) {
                const daysFromStart = Math.round(offset / dayWidth);
                const date = new Date($('#st_date').val());
                date.setDate(date.getDate() + daysFromStart * (isWeeklyView ? 7 : 1));
                return date.toISOString().split('T')[0];
            }

            // Toggle between daily and weekly views
            $('#toggleView').on('click', function () {
                isWeeklyView = !isWeeklyView; // Toggle the view mode
                renderCalendar(); // Re-render the calendar
                alignGanttBars(); // Re-align the Gantt bars
            });

            // Prevent scroll-container from scrolling when dragging bars
            $(".draggable").on("mousedown", function (event) {
                event.stopPropagation(); // Stop the event from propagating to the scroll-container
            });

            // Initial render
            renderCalendar();
            alignGanttBars();
            
            // Populate time tracking data after calendar is rendered
            setTimeout(function() {
                // Debug: Check if input fields have proper attributes
                console.log('Checking input fields after calendar render...');
                const inputFields = document.querySelectorAll('.inputss');
                console.log('Total input fields found:', inputFields.length);
                
                if (inputFields.length > 0) {
                    const firstInput = inputFields[0];
                    console.log('First input attributes:', {
                        'data-date': firstInput.getAttribute('data-date'),
                        'data-user-id': firstInput.getAttribute('data-user-id'),
                        'data-task-id': firstInput.getAttribute('data-task-id')
                    });
                }
                
                populateTimeTrackingData();
            }, 200);
            
            // $(window).resize(alignGanttBars);
        });
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
        function highlightToday() {
                const today = new Date();

                const st = $('#st_date').val();
                const en = $('#en_date').val();

                const startDate = new Date(st); // Adjust this to your Gantt chart's start date
                startDate.setDate(startDate.getDate() - 30);
                const dayWidth = $(".calendar-day").outerWidth();
                const coun = $('#task_count').val();

                // Calculate the number of days from the start date to today
                const daysFromStart = Math.floor((today - startDate) / (1000 * 60 * 60 * 24));

                // Calculate the left position for the today line
                const todayPosition = daysFromStart * dayWidth;


                // Add the today line to the Gantt chart
                const todayLine = $('<div class="today-line"></div>');
                
                // Calculate height: (total tasks × individual task height) + date cell height
                const totalTasks = coun; // Total number of tasks
                const individualTaskHeight = 30; // Height of each individual task row
                const dateCellHeight = 20; // Height of the date showing cell
                const totalHeight = (totalTasks * individualTaskHeight) + dateCellHeight;
                
                todayLine.css({
                    left: todayPosition + 'px',
                    height: totalHeight + 'px'
                });

                $('.gantt-bar-container').append(todayLine);
            }

            // Call the function after the DOM is ready
            $(document).ready(function () {
                highlightToday();
            });
    </script>

    <script>
        function highlightHolidays() {

            const st = $('#st_date').val();
            const en = $('#en_date').val();

            const startDate = new Date(st); // Adjust this to your Gantt chart's start date (April 1, 2025)
            const endDate = new Date(en); // Adjust this to your Gantt chart's end date (December 31, 2025)
            const dayWidth = $(".calendar-day").outerWidth();

            // Calculate all weekend dates (Saturdays and Sundays) within the date range
            const holidays = [];
            
            // Use a safer date iteration approach
            const currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                const dayOfWeek = currentDate.getDay(); // 0 = Sunday, 6 = Saturday
                if (dayOfWeek === 2 || dayOfWeek === 1) {
                    holidays.push(new Date(currentDate)); // Add weekend date to the holidays array
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }

            // Clear existing holiday highlights
            $('.gantt-bar-container .holiday-highlight').remove();

            // Highlight each holiday in the Gantt chart
            holidays.forEach(holiday => {
                const daysFromStart = Math.floor((holiday - startDate) / (1000 * 60 * 60 * 24));

                // Calculate the left position for the holiday highlight
                const holidayPosition = daysFromStart * dayWidth;

                // Add the holiday highlight to the Gantt chart
                const holidayHighlight = $('<div class="holiday-highlight"></div>');
                holidayHighlight.css({
                    left: holidayPosition + 'px',
                    width: dayWidth + 'px',
                });

                $('.gantt-bar-container').append(holidayHighlight);
            });
        }

        // Call the function after the DOM is ready
        $(document).ready(function () {
            highlightHolidays();
        });
    </script>


<script>
    $(document).ready(function () {
        const addTaskButton = $('#addTaskButton');
        const addTaskButton2 = $('#addTaskButton2');
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

        // Open the modal
        addTaskButton2.on('click', function () {
            addTaskModal.fadeIn();
        });

        // Close the modal
        cancelButton.on('click', function () {
            addTaskModal.fadeOut();

            return false;
        });

        // Open the modal
        addMemberButton.on('click', function () {
            addMemberModal.fadeIn();
        });

        // Close the modal
        cancelButtonMember.on('click', function () {
            addMemberModal.fadeOut();

            return false;
        });


        $('.calendar-day.inputss').each(function () {
            const $this = $(this);
            const $parent = $this.parent();
            
            $this.attr('data-task-id', $parent.data('task-id'));
            $this.attr('data-user-id', $parent.data('user-id'));
        });
    });
</script>


<script>
    $(document).ready(function () {
        // Drag-to-scroll functionality for each scrollable container
        $('.scroll-container').each(function () {
            const scrollContainer = $('.scroll-container'); // Target the specific scroll-container
            let isDragging = false;
            let startX;
            let scrollLeft;

            // Mouse down event to start dragging
            scrollContainer.on('mousedown', function (e) {
                isDragging = true;
                startX = e.pageX - scrollContainer.offset().left;
                scrollLeft = scrollContainer.scrollLeft();
                scrollContainer.css('cursor', 'grabbing'); // Change cursor to grabbing
            });

            // Mouse move event to handle scrolling
            scrollContainer.on('mousemove', function (e) {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offset().left;
                const walk = (x - startX) * 1; // Adjust the multiplier for faster/slower scrolling
                scrollContainer.scrollLeft(scrollLeft - walk);
            });

            // Mouse up or leave event to stop dragging
            scrollContainer.on('mouseup mouseleave', function () {
                isDragging = false;
                scrollContainer.css('cursor', 'grab'); // Reset cursor to grab
            });
        });
    });
</script>

<script>
    // Function to restrict input to integers only
    function restrictToInteger(inputElement) {
        inputElement.value = inputElement.value.replace(/[^0-9]/g, '');
        
        // Also check the range
        const value = parseInt(inputElement.value);
        if (value > 8) {
            inputElement.value = '8';
        } else if (value < 1 && inputElement.value !== '') {
            inputElement.value = '1';
        }
    }

    // Function to handle integer time input
        async function convertTimeInput(inputElement) {
            const integerTime = parseInt(inputElement.value); // Get the input value as an integer

            const date = inputElement.getAttribute('data-date'); // Get the date from the data attribute
            const user_id = inputElement.getAttribute('data-user-id'); // Get the user ID from the data attribute
            const data = inputElement.value; // Get the value from the input element
            const project_id = $('#project_id').val(); // Get the project ID from the input element

            if (isNaN(integerTime) || integerTime == 0) {
                inputElement.value = '';

                var project = $('#project_id').val();

                const data = 0;

                try {
                    // Send the data to the server
                    const response = await fetch('/estimated-time-tracking/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                        },
                        body: JSON.stringify({ user_id, date, data, project_id }),
                    });

                    if (response.ok) {
                        // console.log('Data saved successfully.');

                        const responseData = await response.json(); // parse the JSON

                        $('.user-hour-'+user_id).html(responseData.data.total);

                        $('.user-cost-'+user_id).html(formatCurrency(responseData.data.cost));

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

            if (integerTime < 1 || integerTime > 8) {
                alert('Value is Invalid. Please enter a number between 1 and 8.');
                inputElement.value = ''; // Clear the input field
                return; // Exit the function
            }

            if (!isNaN(integerTime)) {
                inputElement.value = integerTime; // Display the integer value as-is
            } else {
                inputElement.value = ''; // Clear the display if the input is invalid
            }

            try {
                // Send the data to the server
                const response = await fetch('/estimated-time-tracking/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                    },
                    body: JSON.stringify({ user_id, date, data, project_id }),
                });

                if (response.ok) {
                    // console.log('Data saved successfully.');

                    const responseData = await response.json(); // parse the JSON

                    $('.user-hour-'+user_id).html(responseData.data.total);

                    $('.user-cost-'+user_id).html(formatCurrency(responseData.data.cost));

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
</script>

<script>
    async function populateTimeTrackingData() {
        project = $('#project_id').val(); // Get the project ID from the hidden input field
        console.log('Starting to populate time tracking data for project:', project); // Debug log
        
        try {
            // Fetch the saved data from the server
            const response = await fetch('/estimated-time-tracking/'+project+'/get', {
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
            
            console.log('Fetched time tracking data:', data); // Debug log to see the data structure
            console.log('Data length:', data.length); // Debug log

            // Iterate over the data and populate the input fields
            data.forEach(item => {
                const { task_id, user_id, date, time } = item;
                
                console.log('Processing item:', { task_id, user_id, date, time }); // Debug log

                // Find the input field with the matching user_id and date
                const inputFields = document.querySelectorAll('.inputss');

                inputFields.forEach(inputField => {
                    const inputDate = inputField.getAttribute('data-date');
                    const userId = inputField.getAttribute('data-user-id');
                    // Match the user_id and date
                    if (inputDate === date && userId == user_id) {
                        console.log('Found matching input field for:', { inputDate, userId, time }); // Debug log
                        
                        // Convert time format to integer (e.g., "5:00" -> 5, "8:00" -> 8)
                        let integerTime;
                        if (typeof time === 'string' && time.includes(':')) {
                            // Parse time format like "5:00" or "08:00"
                            integerTime = parseInt(time.split(':')[0]);
                        } else {
                            // Already in integer format
                            integerTime = parseInt(time);
                        }
                        
                        console.log('Converted time to integer:', integerTime); // Debug log
                        
                        // Only set if it's a valid integer between 1-8
                        if (!isNaN(integerTime) && integerTime >= 1 && integerTime <= 8) {
                            inputField.value = integerTime;
                            console.log('Successfully populated input field with:', integerTime); // Debug log
                        } else {
                            console.log('Invalid time value, skipping:', integerTime); // Debug log
                        }
                    }
                });
            });
        } catch (error) {
            console.error('Error fetching time tracking data:', error);
        }
    }
</script>

<script>
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
</script>

<script>
    // Team member drag and drop functionality
    $(document).ready(function() {
        // Function to refresh input field attributes
        function refreshInputFieldAttributes() {
            $('.time-input-row .inputss').each(function() {
                const $this = $(this);
                const $parent = $this.parent();
                
                $this.attr('data-task-id', $parent.data('task-id'));
                $this.attr('data-user-id', $parent.data('user-id'));
                $this.attr('data-member-id', $parent.data('member-id'));
            });
        }

        // Make the team member list sortable
        $('#team-members-list').sortable({
            handle: '.drag-handle',
            axis: 'y',
            cursor: 'move',
            helper: 'clone',
            placeholder: 'ui-sortable-placeholder',
            tolerance: 'pointer',
            update: function(event, ui) {
                // Get the new order of team members
                var memberOrder = [];
                $('#team-members-list .team-member-row').each(function() {
                    memberOrder.push($(this).data('member-id'));
                });
                
                // Reorder the time input rows accordingly
                var $timeInputContainer = $('#team-time-inputs');
                var $timeInputs = $timeInputContainer.find('.time-input-row').detach();
                
                // Reorder time inputs based on the new member order
                memberOrder.forEach(function(memberId) {
                    var $matchingInput = $timeInputs.filter('[data-member-id="' + memberId + '"]');
                    if ($matchingInput.length > 0) {
                        $timeInputContainer.append($matchingInput);
                    }
                });
                
                // Refresh input field attributes after reordering
                refreshInputFieldAttributes();
                
                console.log('Team members reordered:', memberOrder);
            }
        });
        
        // Optional: Add visual feedback when hovering over sortable items
        $('#team-members-list').on('mouseenter', '.team-member-row', function() {
            $(this).css('background-color', '#f9fafb');
        }).on('mouseleave', '.team-member-row', function() {
            $(this).css('background-color', '#fff');
        });

        // Initial setup for input field attributes
        refreshInputFieldAttributes();
    });
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
      // Initialize daterangepicker for both add and edit task date inputs
      $('input[name="date"], #startDate, #startDateedit').daterangepicker({
        opens: 'left',
        locale: {
          format: 'DD/MM/YYYY'
        }
      }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('DD/MM/YYYY') + ' to ' + end.format('DD/MM/YYYY'));
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
$(document).ready(function() {
    let asc = true;
    $('#sortProject').on('click', function() {
        let items = $('.mains .task-list .task-item').get();
        items.sort(function(a, b) {
            let keyA = $(a).find('span').first().text().trim().toLowerCase();
            let keyB = $(b).find('span').first().text().trim().toLowerCase();
            if (asc) {
                return keyA.localeCompare(keyB);
            } else {
                return keyB.localeCompare(keyA);
            }
        });
        
        // Get corresponding Gantt bars and sort them based on task order
        let ganttBars = $('.gantt-bar-container .draggable').get();
        let taskOrder = items.map(function(item) {
            return $(item).data('task');
        });
        
        ganttBars.sort(function(a, b) {
            let taskA = $(a).data('task');
            let taskB = $(b).data('task');
            let indexA = taskOrder.indexOf(taskA);
            let indexB = taskOrder.indexOf(taskB);
            
            if (indexA === -1) indexA = 999;
            if (indexB === -1) indexB = 999;
            
            return indexA - indexB;
        });
        
        // Reorder task items
        $.each(items, function(i, item) {
            $('.mains .task-list').append(item);
        });
        
        // Clear and reorder Gantt bars with new positions
        $('.gantt-bar-container').empty();
        $.each(ganttBars, function(index, bar) {
            const $bar = $(bar);
            const taskTop = 30 * index;
            $bar.css({
                top: taskTop + 'px'
            });
            $('.gantt-bar-container').append($bar);
        });
        
        // Re-initialize draggable and resizable functionality
        makeDraggableAndResizable();
        
        // Regenerate today line and holiday highlights with new task count and order
        $('.today-line').remove();
        $('.holiday-highlight').remove();
        highlightToday();
        highlightHolidays();
        
        asc = !asc;
        $('#sortProjectIcon').toggleClass('fa-sort-alpha-down fa-sort-alpha-up');
    });
});
</script>

    <script>
$(document).ready(function() {
    let asc = true;
    $('#sortProjectuser').on('click', function() {
        let items = $('.us .names .task-item').get();
        items.sort(function(a, b) {
            let keyA = $(a).find('span').first().text().trim().toLowerCase();
            let keyB = $(b).find('span').first().text().trim().toLowerCase();
            if (asc) {
                return keyA.localeCompare(keyB);
            } else {
                return keyB.localeCompare(keyA);
            }
        });
        
        // Sort calendar inputs to match task order
        let calendarInputs = $('.not-archived .second-input').get();
        let memberIdOrder = items.map(function(item) {
            return $(item).data('member-id');
        });
        
        calendarInputs.sort(function(a, b) {
            let memberIdA = $(a).data('member-id');
            let memberIdB = $(b).data('member-id');
            let indexA = memberIdOrder.indexOf(memberIdA);
            let indexB = memberIdOrder.indexOf(memberIdB);
            
            if (indexA === -1) indexA = 999;
            if (indexB === -1) indexB = 999;
            
            return indexA - indexB;
        });
        
        // Reorder both task items and calendar inputs
        $.each(items, function(i, item) {
            $('.us .names').append(item);
        });
        
        $.each(calendarInputs, function(i, input) {
            $('#team-time-inputs').append(input);
        });
        
        asc = !asc;
        $('#sortProjectIcon').toggleClass('fa-sort-alpha-down fa-sort-alpha-up');
    });
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
        scrollContainer.animate({
            scrollLeft: cellLeft - scrollContainer.width()/14 + $todayCell.outerWidth()/2
        }, 400);
    }
});

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
        scrollContainer.animate({
            scrollLeft: cellLeft - scrollContainer.width()/14 + $todayCell.outerWidth()/2
        }, 400);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function isOverlap(startA, endA, startB, endB) {
        return (startA <= endB && endA >= startB);
    }

    function highlightOverlappingBars() {
        const bars = $('.gantt-bar-container .draggable');
        bars.removeClass('alert-danger'); // Remove previous highlights

        bars.each(function(i, barA) {
            const $barA = $(barA);
            const startA = new Date($barA.attr('data-start-date'));
            const endA = new Date($barA.attr('data-end-date'));

            bars.each(function(j, barB) {
                if (i === j) return; // Skip self
                const $barB = $(barB);
                const startB = new Date($barB.attr('data-start-date'));
                const endB = new Date($barB.attr('data-end-date'));

                if (isOverlap(startA, endA, startB, endB)) {
                    $barA.addClass('alert-danger');
                }
            });
        });
    }

    // Call after bars are rendered and aligned
    highlightOverlappingBars();
});
</script>

<script>
    // --- Improved Robust Auto-scroll for Gantt drag/resize ---
function enableAutoScrollOnDragResize() {
    let autoScrollInterval = null;
    let lastDirection = null;
    let isBarActive = false;
    let $activeScrollContainer = null;
    let $activeBar = null;
    let lastMouseX = null;
    let lastScrollLeft = null;
    let isResizing = false;
    let activeDragEvent = null;
    const scrollSpeed = 30; // px per interval
    const edgeThreshold = 60; // px from edge to trigger scroll

    // Detect if a bar is being dragged or resized
    $(document).on('mousedown touchstart', '.draggable, .ui-resizable-handle', function(e) {
        isBarActive = true;
        $activeBar = $(e.target).closest('.draggable');
        $activeScrollContainer = $activeBar.closest('.scroll-container');
        isResizing = $(e.target).hasClass('ui-resizable-handle');
        if (e.type === 'touchstart') {
            lastMouseX = e.originalEvent.touches[0].clientX;
        } else {
            lastMouseX = e.pageX;
        }
        lastScrollLeft = $activeScrollContainer.scrollLeft();
    });
    $(document).on('mouseup touchend', function() {
        isBarActive = false;
        stopAutoScroll();
        $activeScrollContainer = null;
        $activeBar = null;
        lastMouseX = null;
        lastScrollLeft = null;
        isResizing = false;
        activeDragEvent = null;
    });

    // Patch jQuery UI drag/resize to expose the current event
    $(document).on('dragstart', '.draggable', function(event, ui) {
        activeDragEvent = ui;
    });
    $(document).on('resizestart', '.draggable', function(event, ui) {
        activeDragEvent = ui;
    });

    function triggerSyntheticMove(newX) {
        if (!isBarActive) return;
        let event;
        if ('ontouchstart' in window) {
            event = new TouchEvent('touchmove', {
                touches: [new Touch({
                    identifier: Date.now(),
                    target: $activeBar[0],
                    clientX: newX,
                    clientY: 0
                })],
                bubbles: true,
                cancelable: true
            });
        } else {
            event = new MouseEvent('mousemove', {
                clientX: newX,
                bubbles: true,
                cancelable: true
            });
        }
        document.dispatchEvent(event);
    }

    function startAutoScroll(direction) {
        if (autoScrollInterval && lastDirection === direction) return;
        stopAutoScroll();
        lastDirection = direction;
        autoScrollInterval = setInterval(() => {
            if (!$activeScrollContainer || !$activeBar) return;
            let prevScrollLeft = $activeScrollContainer.scrollLeft();
            if (direction === 'left') {
                $activeScrollContainer.scrollLeft(prevScrollLeft - scrollSpeed);
            } else if (direction === 'right') {
                $activeScrollContainer.scrollLeft(prevScrollLeft + scrollSpeed);
            }
            let newScrollLeft = $activeScrollContainer.scrollLeft();
            let scrollDelta = newScrollLeft - prevScrollLeft;
            if (scrollDelta !== 0 && lastMouseX !== null) {
                // Use jQuery UI's API to update the bar position if possible
                if (activeDragEvent && activeDragEvent.position) {
                    activeDragEvent.position.left += scrollDelta;
                    $activeBar.css('left', activeDragEvent.position.left + 'px');
                } else {
                    // fallback
                    let barOffset = $activeBar.position().left;
                    $activeBar.css('left', (barOffset + scrollDelta) + 'px');
                }
                triggerSyntheticMove(lastMouseX + scrollDelta);
            }
        }, 20);
    }
    function stopAutoScroll() {
        if (autoScrollInterval) {
            clearInterval(autoScrollInterval);
            autoScrollInterval = null;
            lastDirection = null;
        }
    }

    // For drag/resize (mouse and touch)
    $(document).on('mousemove touchmove', function(e) {
        if (!isBarActive || !$activeScrollContainer) { stopAutoScroll(); return; }
        let mouseX;
        if (e.type === 'touchmove') {
            mouseX = e.originalEvent.touches[0].clientX;
        } else {
            mouseX = e.pageX;
        }
        lastMouseX = mouseX;
        const containerOffset = $activeScrollContainer.offset();
        if (!containerOffset) return;
        const leftEdge = containerOffset.left;
        const rightEdge = leftEdge + $activeScrollContainer.outerWidth();
        if (mouseX < leftEdge + edgeThreshold) {
            startAutoScroll('left');
        } else if (mouseX > rightEdge - edgeThreshold) {
            startAutoScroll('right');
        } else {
            stopAutoScroll();
        }
    });
}

$(function() {
    enableAutoScrollOnDragResize();
});
</script>

</body>
</html>
