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
            width: 32px !important;
            height: 20px !important;
            text-align: center;
        }

        .second-input .calendar-day{
            height: 30px !important;
            font-size: 10px;
            padding: 0px !important;
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
            border-top-left-radius: 4px;
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
        .holiday{
            background: #eee;
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
            top: 0;
            bottom: 0;
            width: 2px;
            height: 55px;
            background-color: #D9534F; /* Red color for the today line */
            z-index: 100; /* Ensure it appears above other elements */
        }

        .today-line::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -2px;
            width: 6px;
            height: 6px;
            background-color: #37352f;
            border-radius: 10px;

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

        .alert-danger{
            background: red !important
        }

        .open-edit-task-modal{
            font-size: 13px;
            background: #fff !important;
            color: #4b5563 !important;
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
                                    <a href="/projects/{{ $data->id }}" class="toggle-btn">Daily</a>
                                    <a href="/projects/weekly/{{ $data->id }}" class="toggle-btn active">Weekly</a>
                                </div>
                                <style>
                                    .toggle-btn {
                                background: #000;
                                color: #fff;
                                border: 1px solid #000;
                                border-radius: 2px;
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
                                        <input type="text" name="budget_total" data-task-id="{{ $item->id }}" value="{{ formatCurrency(round($item->budget_total)) }}" class="budget_total px-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black" style="width: 100%; font-size: 12px; text-align: center;">
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
                        <div class="gantt-bar-container" style="height: {{ 24*count($data->tasks) +5}}px">
                            @foreach ($data->tasks as $key => $item)
                            @if ($item->start_date != null)
                                <div class="draggable bg-blue-600 text-white text-center py-1 px-2" data-task-id="{{ $item->id }}" data-task="task{{$key + 1}}" style="left: calc(3.225% * 5); position: absolute;" data-start-date="{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}" data-end-date="{{ \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') }}">
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
            <div class="content" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
                <div class="task-list" style="border-right: 0px; padding-right: 0px; padding-top: 0px">
                    <div class="task-header" style="margin-bottom: 0px; padding: 10px; padding-right: 0px;">
                        <span style="width: 50%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProject">
                            Name
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortProjectIcon" alt="">
                        </span>
                        <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Cost</span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Hours</span>
                        <span style="font-size: 12px; width: 10%; padding-top: 17px; padding-bottom: 17px; text-align: center; border-right: 1px solid #eee;"> <i class="fas fa-eye show-user" data-type="show"></i> </span>
                        {{-- <span class="text-center font-size: 12px; add-task" style="width: 10%;" id="addMemberButton"><i class="fas fa-plus"></i></span> --}}
                    </div>
                    <div class="not-archived">
                        @foreach ($data->members as $item)
                            @if ($item->archieve == 0)
                                <div class="task-item data-id-{{ $item->id }}" data-task="task{{ $item->task_id }}" data-member-id="{{ $item->id }}" style="position: unset">
                                    <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px;">
                                        <img src="{{ $item->user->profile_image_url ? asset('storage/'.$item->user->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}">
                                        {{ $item->user->name }}
                                    </span>
                                        @php
                                            $es = DB::table('estimated_time_entries')->where('project_id',$data->id)->where('user_id',$item->user_id)->sum('hours');
                                        @endphp
                                    <span style="width: 25%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->user_id }}">{{ number_format($item->user->hourly_rate*$es) }}</span>
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
                                <span style="width: 25%; font-size: 12px; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-cost-{{ $item->user_id }}">{{ number_format($item->user->hourly_rate*$es) }}</span>
                                <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;" class="user-hour-{{ $item->user_id }}">
                                    {{ number_format($es) }}
                                </span>
                                @if ($item->archieve == 0)
                                <span {{ $item->archieve }} style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class="fas fas fa-eye hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
                                @else
                                <span style="display: block; width:10%; padding-top: 6px; padding-bottom: 6px; text-align: center;"> <i class=" fa-eye-slash hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px;"></i> </span>
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
                        <div class="not-archived" style="margin-top: -4px">
                            @foreach ($data->members as $item)
                            @if ($item->archieve == 0)
                                <div class="second-input data-id-{{ $item->id }}" data-task-id="{{ $item->task_id }}" data-user-id="{{ $item->user_id }}" data-member-id="{{ $item->id }}"></div>
                            @endif
                            @endforeach
                        </div>
                        <div class="archied" style="margin-top: -4px">
                            @foreach ($data->members as $item)
                                <div class="second-input data-id-{{ $item->id }}" data-task-id="{{ $item->task_id }}" data-user-id="{{ $item->user_id }}" data-member-id="{{ $item->id }}"></div>
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
    <input type="hidden" id="en_date" value={{ $data->end_date }}>
    <input type="hidden" id="task_count" value={{ count($data->tasks) }}>
    <input type="hidden" id="project_id" value={{$data->id }}>
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
        
        $(function () {
            const calendarContainer = $('.calendar-container');
            const ganttBarContainer = $('.gantt-bar-container');
            const scrollContainer = $('.scroll-container');
            const st = $('#st_date').val();
            const en = $('#en_date').val();
            const startDate = new Date(st);
            const endDate = new Date(en);
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const totalMonthsToShow = 480; // Show at least 12 months of weeks

            function renderCalendar() {
                calendarContainer.empty(); // Clear the calendar container

                // Get the project's start date
                let projectStartDate = new Date($('#st_date').val());
                projectStartDate.setDate(projectStartDate.getDate() - 30);
                const projectYear = projectStartDate.getFullYear();

                // Find the 1st Monday of January of the project's year
                let firstMondayOfYear = new Date(projectYear, 0, 1); // January 1st of the project's year
                const dayOfWeek = firstMondayOfYear.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                const daysToMonday = (dayOfWeek === 0 ? 1 : 8 - dayOfWeek); // Calculate days to the first Monday
                firstMondayOfYear.setDate(firstMondayOfYear.getDate() + daysToMonday);

                // Set the start date to the 1st day of the month of the project's start date
                let startDate = new Date(projectStartDate.getFullYear(), projectStartDate.getMonth(), 1);

                let currentMonth = startDate.getMonth(); // Get the starting month index (0–11)
                let currentYear = startDate.getFullYear(); // Get the starting year
                let monthContainer = $('<div class="month-container"></div>');
                const monthStartDate = new Date(currentYear, currentMonth, 1);
                let weekNumber = getWeekNumberFromFirstMonday(new Date(monthStartDate)); // Start with Week 1

                inp = ``;

                // Base color for the fade effect
                const baseColor = [74, 85, 104]; // RGB for #4a5568 (dark gray-blue)
                const fadeStep = 1; // Amount to lighten the color for each month

                let fadeIndex = 0; // Initialize fade index

                for (let i = 0; i < totalMonthsToShow; i++) {
                    const monthStartDate = new Date(currentYear, currentMonth, 1);

                    // Adjust to the first Monday of the month
                    const dayOfWeek = monthStartDate.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                    const daysToMonday = (dayOfWeek === 0 ? 1 : 8 - dayOfWeek); // Calculate days to the first Monday
                    monthStartDate.setDate(monthStartDate.getDate() + daysToMonday);
                    const monthEndDate = new Date(currentYear, currentMonth + 1, 0); // Last day of the month

                    monthContainer.append(`<div class="month-header">${monthNames[currentMonth]} ${currentYear}</div>`);

                    for (let d = new Date(monthStartDate); d <= monthEndDate; d.setDate(d.getDate() + 7)) {
                        const weekBlock = $(`<div class="calendar-day week-block" data-week="${weekNumber}" data-month="${currentMonth}">W${weekNumber}</div>`);
                        monthContainer.append(weekBlock);
                        weekNumber++;

                        const day = d.getDate().toString().padStart(2, '0'); // Pad day with leading zero
                        const month = (d.getMonth() + 1).toString().padStart(2, '0'); // Pad month with leading zero
                        const year = d.getFullYear();
                        const dateString = `${year}-${month}-${day}`;

                        inp += `<input type="text" class="calendar-day inputss" onchange="convertTimeInput(this)" data-date="${dateString}">`;

                        const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                            ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                        monthContainer.find('.month-header').css('background-color', fadeColor);

                        // console.log(fadeColor);

                        fadeIndex++; // Increment fade index for the next month
                    }

                    calendarContainer.append(monthContainer);

                    // Move to the next month
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }

                    // Reset the month container for the next month
                    monthContainer = $('<div class="month-container"></div>');
                }

                // Apply fading color to the last month header
                const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                    ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                monthContainer.find('.month-header').css('background-color', fadeColor);

                calendarContainer.append(monthContainer);

                $('.second-input').append(inp);
            }

            function getWeekNumberFromFirstMonday(date) {
                // Get the year of the given date
                const year = date.getFullYear();

                // Find the 1st Monday of January of the given year
                let firstMonday = new Date(year, 0, 1); // January 1st of the year
                const dayOfWeek = firstMonday.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                const daysToMonday = (dayOfWeek === 0 ? 1 : 8 - dayOfWeek); // Calculate days to the first Monday
                firstMonday.setDate(firstMonday.getDate() + daysToMonday);

                // Calculate the difference in days between the given date and the 1st Monday
                const diffInTime = date - firstMonday;
                const diffInDays = Math.floor(diffInTime / (1000 * 60 * 60 * 24));

                // Calculate the week number (add 1 to include the first week)
                const weekNumber = Math.floor(diffInDays / 7) + 2; // Add 2 to account for the first week starting from the first Monday

                return weekNumber;
            }

            function alignGanttBars() {
                const weekWidth = $(".week-block").outerWidth(); // Width of a single week
                let ganttStartDate = new Date($('#st_date').val()); // Gantt chart start date
                ganttStartDate.setDate(ganttStartDate.getDate() - 30);

                // Set the startDate to the 1st day of the month
                ganttStartDate.setDate(1);

                // Adjust to the first Monday of the month
                const dayOfWeek = ganttStartDate.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                const daysToMonday = (dayOfWeek === 0 ? 1 : 8 - dayOfWeek); // Calculate days to the first Monday
                ganttStartDate.setDate(ganttStartDate.getDate() + daysToMonday);

                $('.draggable').each(function (index) {
                    const $task = $(this);
                    const taskStartDate = new Date($task.attr('data-start-date'));
                    const taskEndDate = new Date($task.attr('data-end-date'));
                    const taskTop = 30 * index;
                    const taskHeight = $(this).outerHeight();

                    console.log('height: '+taskTop)


                    // Calculate the number of weeks from the Gantt start date to the task start and end dates
                    const weeksFromStart = Math.floor((taskStartDate - ganttStartDate) / (1000 * 60 * 60 * 24 * 7));
                    const taskDurationWeeks = Math.ceil((taskEndDate - taskStartDate) / (1000 * 60 * 60 * 24 * 7));

                    // Calculate the left position and width of the task bar
                    const leftPosition = weeksFromStart * weekWidth;
                    const barWidth = taskDurationWeeks * weekWidth;

                    if (isNaN(taskStartDate) || isNaN(taskEndDate)) {
                        console.warn("Invalid dates for task:", $task.attr('data-task'), taskStartDate, taskEndDate);
                        return; // skip this task
                    }

                    if (taskDurationWeeks <= 0) {
                        console.warn("Task duration invalid or zero:", $task.attr('data-task'), taskDurationWeeks);
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
                    grid: [$(".week-block").outerWidth(), 0],
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

                        // Stop propagation to prevent scroll container from reacting
                        event.stopPropagation();
                    },
                    drag: function(event, ui) {
                        if ($(event.originalEvent.target).hasClass('ui-resizable-handle')) {
                            return false;
                        }
                        const $task = $(this);
                        const weekWidth = $(".week-block").outerWidth();
                        const startOffset = ui.position.left;
                        const startDate = calculateDateFromOffset(startOffset, weekWidth);
                        $task.attr('data-start-date', startDate);

                        // Stop propagation to prevent scroll container from reacting
                        event.stopPropagation();
                    },
                    stop: function(event, ui) {
                        const $task = $(this);

                        const initialStartDate = $task.data("initialStartDate");
                        const initialEndDate = $task.data('initialEndDate');

                        const dayWidth = $(".week-block").outerWidth(); // Width of a single day
                        const ganttStartDate = new Date($('#st_date').val()); // Gantt chart start date

                        // Set the startDate to the 1st day of the month
                        ganttStartDate.setDate(1);

                        // Calculate the start and end offsets
                        const startOffset = ui.position.left;
                        const endOffset = startOffset + $task.outerWidth();

                        // Calculate the current start and end dates
                        const currentStartDate = new Date(ganttStartDate);
                        currentStartDate.setDate(ganttStartDate.getDate() + Math.round(startOffset / dayWidth));

                        const currentEndDate = new Date(ganttStartDate);
                        currentEndDate.setDate(ganttStartDate.getDate() + Math.round(endOffset / dayWidth) - 1);

                        checkDates(initialStartDate, initialEndDate, currentStartDate, currentEndDate, $task.attr("data-task-id"));

                        updateTaskDates($task);

                        // Stop propagation to prevent scroll container from reacting
                        event.stopPropagation();
                    }
                }).resizable({
                    handles: {
                        'e': '.ui-resizable-e',
                        'w': '.ui-resizable-w'
                    },
                    grid: [$(".week-block").outerWidth(), 0],
                    containment: "document",
                    stop: function (event, ui) {
                        const $task = $(this);
                        updateTaskDates($task);
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
                                }
                            });
                        }
                    }
                });
            }

            function updateTaskDates($task) {
                const weekWidth = $(".week-block").outerWidth();
                const startOffset = $task.position().left;
                const endOffset = startOffset + $task.outerWidth();

                const startDate = calculateDateFromOffset(startOffset, weekWidth);
                const endDate = calculateDateFromOffset(endOffset - weekWidth, weekWidth);

                $task.attr('data-start-date', startDate);
                $task.attr('data-end-date', endDate);
            }

            function calculateDateFromOffset(offset, weekWidth) {
                const weeksFromStart = Math.round(offset / weekWidth);
                const date = new Date($('#st_date').val());
                date.setDate(date.getDate() + weeksFromStart * 7);
                return date.toISOString().split('T')[0];
            }

            // Prevent scroll-container from scrolling when dragging bars
            $(".draggable").on("mousedown", function (event) {
                event.stopPropagation(); // Stop the event from propagating to the scroll-container
            });

            // Initial render
            renderCalendar();
            alignGanttBars();
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


        $('.calendar-day').each(function () {
            const $this = $(this);

            $this.attr('data-task-id', $(this).parent().data('task-id'));

            $this.attr('data-user-id', $(this).parent().data('user-id'));
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
            const user_id = inputElement.getAttribute('data-user-id'); // Get the date from the data attribute
            const project_id = $('#project_id').val(); // Get the task ID from the data attribute
            const data = inputElement.value; // Get the value from the input element

            if (isNaN(decimalTime) || decimalTime == 0) {
                inputElement.value = '';

                var project = $('#project_id').val();

                const data = 0;


                try {
                    // Send the data to the server
                    const response = await fetch('/estimated-time-tracking/weekly/'+project+'/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                        },
                        body: JSON.stringify({ project_id, date, data, user_id }),
                    });

                    if (response.ok) {
                        // console.log('Data saved successfully.');

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

            if (decimalTime % 5 != 0) {
                alert('Please enter a value that is a multiple of 5.');
                inputElement.value = ''; // Clear the input field
                return; // Exit the function
            }

            if (decimalTime > 0 && decimalTime < 5 || decimalTime > 40) {
                alert('Value is Invalid.');
                inputElement.value = ''; // Clear the input field
                return; // Exit the function
            }

            if (!isNaN(decimalTime)) {
                const hours = Math.floor(decimalTime); // Get the whole number part as hours
                const minutes = Math.round((decimalTime - hours) * 60); // Get the fractional part as minutes
                inputElement.value = `${hours}:${minutes}`; // Display the converted time in the input field
            } else {
                inputElement.value = ''; // Clear the display if the input is invalid
            }


            var project = $('#project_id').val();


            try {
                // Send the data to the server
                const response = await fetch('/estimated-time-tracking/weekly/'+project+'/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                    },
                    body: JSON.stringify({ project_id, date, data, user_id }),
                });

                if (response.ok) {
                    // console.log('Data saved successfully.');

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
            var project = $('#project_id').val();

            const response = await fetch('/estimated-time-tracking-weekly/'+project+'/get', {
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


            console.log(data);

            // Iterate over the data and populate the input fields
            Object.keys(data).forEach(userId => {
                const userWeeks = data[userId]; // Get the array of weeks for the user

                // Iterate over the weeks for the user
                userWeeks.forEach(week => {
                    const { week_start_date, user_id, total_time } = week;

                    // Find the input fields for the user and week_start_date
                    const inputFields = document.querySelectorAll('.inputss');

                    inputFields.forEach(inputField => {
                        const inputDate = inputField.getAttribute('data-date');
                        const inputUserId = inputField.getAttribute('data-user-id');

                        // console.log(week_start_date);

                        // Convert inputDate and week_start_date to Date objects
                        const inputDateObj = new Date(inputDate);
                        const weekStartDateObj = new Date(week_start_date);

                        // Calculate the end date of the week (6 days after the week_start_date)
                        const weekEndDateObj = new Date(weekStartDateObj);
                        weekEndDateObj.setDate(weekEndDateObj.getDate() + 6);

                        // Check if the inputDate falls within the week range and matches the user_id
                        if (
                            inputUserId == user_id &&
                            inputDateObj >= weekStartDateObj &&
                            inputDateObj <= weekEndDateObj
                        ) {

                            const hours = Math.floor(total_time); // Get the whole number part as hours
                            const minutes = Math.round((total_time - hours) * 60); // Get the fractional part as minutes
                            const va = `${hours}:${minutes}`;

                            inputField.value = va; // Populate the input field with the total time
                        }
                    });
                });
            });
        } catch (error) {
            console.error('Error fetching time tracking data:', error);
        }
    }

    // Call the function after the DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        populateTimeTrackingData();
    });
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
        let items = $('.not-archived .task-item').get();
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
        
        // Reorder task items
        $.each(items, function(i, item) {
            $('.not-archived').first().append(item);
        });
        
        // Clear and reorder Gantt bars with new positions
        $('.gantt-bar-container').empty();
        $.each(ganttBars, function(index, bar) {
            const $bar = $(bar);
            const weekWidth = $(".week-block").outerWidth() || 50; // Fallback width for weekly view
            const taskTop = 30 * index;
            $bar.css({
                top: taskTop + 'px'
            });
            $('.gantt-bar-container').append($bar);
        });
        
        // Reorder calendar inputs
        $.each(calendarInputs, function(i, input) {
            $('.scroll-container .not-archived').append(input);
        });
        
        // Re-initialize draggable and resizable functionality
        if (typeof makeDraggableAndResizable === 'function') {
            makeDraggableAndResizable();
        }
        
        asc = !asc;
        $('#sortProjectIcon').toggleClass('fa-sort-alpha-down fa-sort-alpha-up');
    });
});
</script>


</body>
</html>
