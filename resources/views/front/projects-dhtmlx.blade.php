<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projects - Project Management (DHTMLX Gantt)</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHTMLX Gantt Chart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    
    <!-- DHTMLX Gantt -->
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
    
    <style>
        /* DHTMLX Gantt Styles */
        #gantt_here {
            /* width: 100% !important; */
            position: relative !important;
            z-index: 1 !important;
            pointer-events: auto !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
        }
        
        .scroll-container .relative {
            position: relative !important;
            min-height: 450px !important;
        }
        
        .gantt_container {
            background: transparent !important;
            font-family: Arial, sans-serif;
            height: 100% !important;
        }
        
        .gantt_task_line {
            background-color: #4A5568 !important;
            border: 1px solid #fff !important;
            border-radius: 5px !important;
            opacity: 1 !important;
        }
        
        .gantt_task .gantt_task_content {
            color: white !important;
            font-size: 12px !important;
            font-weight: normal !important;
        }
        
        /* Hide the scale headers completely */
        .gantt_task_scale {
            height: 0px !important;
            overflow: hidden !important;
        }
        
        /* Make sure task rows are visible */
        .gantt_task_row {
            background-color: transparent !important;
        }
        
        .gantt_task_bg {
            background-color: transparent !important;
        }
        
        /* Ensure grid is hidden */
        .gantt_grid, .gantt_grid_scale, .gantt_grid_data {
            display: none !important;
            width: 0 !important;
        }
        
        /* Make timeline area visible */
        .gantt_layout_cell {
            overflow: visible !important;
        }
        
        .gantt_data_area {
            overflow-x: hidden !important;
        }

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
            overflow-x: auto;
            width: 100%;
            cursor: default; /* Changed from grab since drag-to-scroll is disabled */
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            /* Scrollbar visible for normal scrolling */
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

        /* Collapsible member time entries styles */
        .expand-arrow {
            cursor: pointer;
            font-size: 12px;
            margin-left: 8px;
            user-select: none;
            padding: 4px;
            display: inline-block;
            min-width: 16px;
            text-align: center;
            z-index: 10;
            position: relative;
        }

        .expand-arrow:hover {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .member-time-entries {
            background-color: #f9fafb;
            border-left: 0px solid #d1d5db;
        }

        .member-time-entries.expanded {
            display: block;
        }

        .time-entry-row {
            border-left: 0px solid #e5e7eb;
            background-color: #f8fafc;
        }

        .member-time-calendar-row {
            background-color: #f9fafb;
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

        /* Hide number input arrows/spinners */
        .inputsss::-webkit-outer-spin-button,
        .inputsss::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .inputsss[type=number] {
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
                            <!-- Undo/Redo Buttons -->
                            <button class="text-gray-600 hover:text-black" id="undoBtn" style="margin-right: 8px;" title="Undo" disabled>
                                <i class="fas fa-undo" style="border: 1px solid #eee; padding: 10px 12px; border-radius: 4px; font-size: 14px;"></i>
                            </button>
                            <button class="text-gray-600 hover:text-black" id="redoBtn" style="margin-right: 8px;" title="Redo" disabled>
                                <i class="fas fa-redo" style="border: 1px solid #eee; padding: 10px 12px; border-radius: 4px; font-size: 14px;"></i>
                            </button>
                            
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
                            
                            <style>
                                /* Undo/Redo button styles */
                                #undoBtn, #redoBtn {
                                    transition: all 0.2s ease;
                                }
                                
                                #undoBtn:disabled, #redoBtn:disabled {
                                    opacity: 0.5;
                                    cursor: not-allowed;
                                }
                                
                                #undoBtn:disabled i, #redoBtn:disabled i {
                                    border-color: #f3f4f6 !important;
                                    color: #9ca3af !important;
                                }
                                
                                #undoBtn:not(:disabled):hover i, #redoBtn:not(:disabled):hover i {
                                    border-color: #000 !important;
                                    background-color: #f9fafb;
                                }
                                
                                #undoBtn:not(:disabled) i, #redoBtn:not(:disabled) i {
                                    color: #374151;
                                    cursor: pointer;
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
                            <a href="/projects/{{ $data->id }}/v2" class="bg-green-600 text-white px-4 py-2 rounded" style="font-size: 13px; padding: 0.4rem 1rem; cursor: pointer; margin-right: 8px; background-color: #059669 !important; display: inline-flex; align-items: center; height: 34px;" title="New Clean Version">
                                <i class="fas fa-rocket" style="margin-right: 6px;"></i> V2
                            </a>
                            
                            <a href="/projects/{{ $data->id }}" class="bg-gray-600 text-white px-4 py-2 rounded" style="font-size: 13px; padding: 0.4rem 1rem; cursor: pointer; margin-right: 8px; background-color: #4b5563 !important; display: inline-flex; align-items: center; height: 34px;" title="Back to Original Gantt">
                                <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> Original View
                            </a>
                            
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
                        <!-- DHTMLX Gantt Chart Container (overlays on calendar) -->
                        <div id="gantt_here" style="width: 100%; height: {{ max((30*$height) + 50, 400) }}px;"></div>
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
                                    <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; align-items: center;">
                                        <img class="drag-handle" src="{{ asset('dots.svg') }}" style="margin-right: 5px;">
                                        <img src="{{ $item->user->profile_image_url ? asset('storage/'.$item->user->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}">
                                        {{ $item->user->name }}
                                        <div class="expand-arrow" data-target="member-time-entries" data-id="{{ $item->user_id }}" data-project-id="{{ $data->id }}">▶</div>
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
                                
                                <!-- Expandable Time Entries for this Team Member -->
                                <div class="member-time-entries" data-user-id="{{ $item->user_id }}" data-project-id="{{ $data->id }}">
                                    <!-- Summary row showing time entries with actual data -->
                                    <div class="task-item time-entry-row" data-user-id="{{ $item->user_id }}">
                                        <span style="padding-left: 20px; width: 50%; font-size: 11px; display: inline-flex; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; align-items: center;">
                                            <span style="width: 6px; height: 6px; background-color: #6b7280; border-radius: 50%; margin-right: 8px; display: inline-block;"></span>
                                            <!-- Blank space as requested -->
                                        </span>
                                        <span style="width: 25%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;" class="member-time-cost-{{ $item->user_id }}">
                                            @php
                                                $currentWeekStart = now()->startOfWeek();
                                                $currentWeekEnd = now()->endOfWeek();
                                                $timeEntries = DB::table('time_entries')
                                                    ->where('user_id', $item->user_id)
                                                    ->where('project_id', $data->id)
                                                    // ->whereBetween('entry_date', [$currentWeekStart, $currentWeekEnd])
                                                    ->get();
                                                $totalHours = $timeEntries->sum('hours');
                                                $totalCost = $totalHours * $item->user->hourly_rate;
                                                // dd($timeEntries);   
                                            @endphp
                                            {{ formatCurrency($totalCost) }}
                                        </span>
                                        <span style="width: 15%; font-size: 11px; border-right: 1px solid #eee; padding-top: 4px; padding-bottom: 4px; text-align: center;" class="member-time-hours-{{ $item->user_id }}">
                                            {{ number_format($totalHours, 0) }}
                                        </span>
                                        <span style="width: 10%; font-size: 11px; padding-top: 4px; padding-bottom: 4px; text-align: center;"></span>
                                    </div>
                                    
                                    <!-- Calendar input row (will be populated by JavaScript) -->
                                    <div class="member-time-calendar-row" data-user-id="{{ $item->user_id }}" data-project-id="{{ $data->id }}">
                                        <!-- Calendar inputs will be populated by JavaScript with time_entries data only -->
                                    </div>
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
                                
                                <!-- Member time calendar row (hidden initially, will be populated by JavaScript) -->
                                <div class="second-input member-time-calendar-row member-time-{{ $item->user_id }}" 
                                     data-user-id="{{ $item->user_id }}" 
                                     data-project-id="{{ $data->id }}" 
                                     style="display: none;"></div>
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
    <input type="hidden" id="currency_symbol" value="{{ str_replace(number_format(0, 0), '', formatCurrency(0)) }}">

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
            // Get dynamic currency symbol from backend settings
            const currencySymbol = document.getElementById('currency_symbol').value;
            const formattedAmount = Number(amount).toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
            return currencySymbol + formattedAmount;
        }
        
        // Undo/Redo functionality - Global scope
        let undoStack = [];
        let redoStack = [];
        const maxHistorySize = 20;
        let isRestoringState = false; // Flag to prevent state saving during restoration
        
        // Function to save current state
        function saveState() {
            // Don't save state if we're in the middle of restoring a state
            if (isRestoringState) return;
            
            const currentState = {
                tasks: [],
                ganttPositions: []
            };
            
            // Save task positions and dates
            $('.draggable').each(function() {
                const $task = $(this);
                currentState.tasks.push({
                    id: $task.attr('data-task-id'),
                    startDate: $task.attr('data-start-date'),
                    endDate: $task.attr('data-end-date'),
                    left: $task.css('left'),
                    width: $task.css('width'),
                    top: $task.css('top')
                });
            });
            
            // Save gantt positions
            $('.draggable').each(function() {
                const $task = $(this);
                currentState.ganttPositions.push({
                    id: $task.attr('data-task-id'),
                    position: {
                        left: $task.position().left,
                        width: $task.outerWidth(),
                        top: $task.position().top
                    }
                });
            });
            
            // Add to undo stack
            if (undoStack.length >= maxHistorySize) {
                undoStack.shift(); // Remove oldest state
            }
            undoStack.push(JSON.parse(JSON.stringify(currentState)));
            
            // Clear redo stack when new action is performed
            redoStack = [];
            
            updateUndoRedoButtons();
            
            console.log('State saved. Undo stack length:', undoStack.length);
        }
        
        // Function to save state before an action (this will be the state to restore to)
        function saveStateBeforeAction() {
            // Don't save state if we're in the middle of restoring a state
            if (isRestoringState) return;
            
            const currentState = {
                tasks: [],
                ganttPositions: []
            };
            
            // Save task positions and dates
            $('.draggable').each(function() {
                const $task = $(this);
                currentState.tasks.push({
                    id: $task.attr('data-task-id'),
                    startDate: $task.attr('data-start-date'),
                    endDate: $task.attr('data-end-date'),
                    left: $task.css('left'),
                    width: $task.css('width'),
                    top: $task.css('top')
                });
            });
            
            // Save gantt positions
            $('.draggable').each(function() {
                const $task = $(this);
                currentState.ganttPositions.push({
                    id: $task.attr('data-task-id'),
                    position: {
                        left: $task.position().left,
                        width: $task.outerWidth(),
                        top: $task.position().top
                    }
                });
            });
            
            // Add to undo stack
            if (undoStack.length >= maxHistorySize) {
                undoStack.shift(); // Remove oldest state
            }
            undoStack.push(JSON.parse(JSON.stringify(currentState)));
            
            // Clear redo stack when new action is performed
            redoStack = [];
            
            updateUndoRedoButtons();
            
            console.log('State before action saved. Undo stack length:', undoStack.length);
        }
        
        // Function to save task dates to server (used during undo/redo)
        function saveTaskDatesToServer(taskId, startDate, endDate) {
            $.ajax({
                url: '/projects/save-dates',
                type: 'POST',
                data: {
                    stoppedStartDate: startDate,
                    stoppedEndDate: endDate,
                    task_id: taskId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log('Task dates saved to server for task:', taskId);
                    // Update the date display
                    $(`.start-${taskId}`).html(formatDateForDisplay(startDate));
                },
                error: function(xhr, status, error) {
                    console.error('Error saving task dates:', error);
                }
            });
        }
        
        // Function to restore state
        function restoreState(state) {
            if (!state) return;
            
            isRestoringState = true; // Set flag to prevent state saving during restoration
            
            // Restore task positions and dates
            state.tasks.forEach(taskState => {
                const $task = $(`.draggable[data-task-id="${taskState.id}"]`);
                if ($task.length) {
                    // First restore the visual position
                    $task.css({
                        left: taskState.left,
                        width: taskState.width,
                        top: taskState.top
                    });
                    
                    // Recalculate dates based on the restored position (using gantt logic)
                    const dayWidth = $(".calendar-day").outerWidth();
                    let ganttStartDate = new Date();
                    ganttStartDate.setFullYear(ganttStartDate.getFullYear() - 1);
                    
                    const startOffset = $task.position().left;
                    const endOffset = startOffset + $task.outerWidth();
                    
                    // Calculate the correct start and end dates using gantt offset logic
                    const calculatedStartDate = new Date(ganttStartDate);
                    calculatedStartDate.setDate(ganttStartDate.getDate() + Math.round(startOffset / dayWidth));
                    
                    const calculatedEndDate = new Date(ganttStartDate);
                    calculatedEndDate.setDate(ganttStartDate.getDate() + Math.round(endOffset / dayWidth) - 1);
                    
                    const formattedStartDate = calculatedStartDate.toISOString().split('T')[0];
                    const formattedEndDate = calculatedEndDate.toISOString().split('T')[0];
                    
                    // Update task attributes with recalculated dates
                    $task.attr('data-start-date', formattedStartDate);
                    $task.attr('data-end-date', formattedEndDate);
                    
                    // Update the corresponding date display
                    $(`.start-${taskState.id}`).html(formatDateForDisplay(formattedStartDate));
                    
                    // Save the recalculated dates to server
                    saveTaskDatesToServer(taskState.id, formattedStartDate, formattedEndDate);
                }
            });
            
            isRestoringState = false; // Reset flag
            updateUndoRedoButtons();
            
            // Check for overlaps after restore operation
            setTimeout(() => {
                if (typeof highlightOverlappingBars === 'function') {
                    highlightOverlappingBars();
                }
            }, 100); // Small delay to ensure all DOM updates are complete
        }
        
        // Function to update button states
        function updateUndoRedoButtons() {
            $('#undoBtn').prop('disabled', undoStack.length === 0);
            $('#redoBtn').prop('disabled', redoStack.length === 0);
        }
        
        // Undo function
        function performUndo() {
            if (undoStack.length === 0) return;
            
            console.log('Performing undo. Undo stack length before:', undoStack.length);
            
            // Capture current state for redo stack before undoing
            const currentState = {
                tasks: [],
                ganttPositions: []
            };
            
            $('.draggable').each(function() {
                const $task = $(this);
                currentState.tasks.push({
                    id: $task.attr('data-task-id'),
                    startDate: $task.attr('data-start-date'),
                    endDate: $task.attr('data-end-date'),
                    left: $task.css('left'),
                    width: $task.css('width'),
                    top: $task.css('top')
                });
            });
            
            // Add current state to redo stack
            redoStack.push(currentState);
            
            // Get and restore previous state (this is the "before" state)
            const previousState = undoStack.pop();
            console.log('Undo stack length after pop:', undoStack.length);
            
            restoreState(previousState);
        }
        
        // Redo function
        function performRedo() {
            if (redoStack.length === 0) return;
            
            console.log('Performing redo. Redo stack length before:', redoStack.length);
            
            // Get current state and save it to undo stack
            const currentState = {
                tasks: [],
                ganttPositions: []
            };
            
            $('.draggable').each(function() {
                const $task = $(this);
                currentState.tasks.push({
                    id: $task.attr('data-task-id'),
                    startDate: $task.attr('data-start-date'),
                    endDate: $task.attr('data-end-date'),
                    left: $task.css('left'),
                    width: $task.css('width'),
                    top: $task.css('top')
                });
            });
            
            // Add current state to undo stack
            undoStack.push(currentState);
            
            // Get and restore next state
            const nextState = redoStack.pop();
            console.log('Redo stack length after pop:', redoStack.length);
            
            restoreState(nextState);
        }
        
        // Global overlap checking functions
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
                        // Both bars should turn red when they overlap
                        console.log('Overlap detected between bar', $barA.attr('data-task-id'), 'and bar', $barB.attr('data-task-id'));
                        $barA.addClass('alert-danger');
                        $barB.addClass('alert-danger');
                    }
                });
            });
        }

        $(function () {
            // Event listeners for undo/redo buttons
            $('#undoBtn').on('click', function() {
                if (!$(this).prop('disabled')) {
                    performUndo();
                }
            });
            
            $('#redoBtn').on('click', function() {
                if (!$(this).prop('disabled')) {
                    performRedo();
                }
            });
            
            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    performUndo();
                } else if ((e.ctrlKey && e.shiftKey && e.key === 'Z') || (e.ctrlKey && e.key === 'y')) {
                    e.preventDefault();
                    performRedo();
                }
            });
            
            // Save initial state immediately as the first undo state
            setTimeout(() => {
                const initialState = {
                    tasks: [],
                    ganttPositions: []
                };
                
                $('.draggable').each(function() {
                    const $task = $(this);
                    initialState.tasks.push({
                        id: $task.attr('data-task-id'),
                        startDate: $task.attr('data-start-date'),
                        endDate: $task.attr('data-end-date'),
                        left: $task.css('left'),
                        width: $task.css('width'),
                        top: $task.css('top')
                    });
                });
                
                $('.draggable').each(function() {
                    const $task = $(this);
                    initialState.ganttPositions.push({
                        id: $task.attr('data-task-id'),
                        position: {
                            left: $task.position().left,
                            width: $task.outerWidth(),
                            top: $task.position().top
                        }
                    });
                });
                
                // Add initial state directly to undo stack
                undoStack.push(JSON.parse(JSON.stringify(initialState)));
                updateUndoRedoButtons();
                
                // Check for overlaps after initial load
                highlightOverlappingBars();
                
                console.log('Initial state saved to undo stack. Length:', undoStack.length);
            }, 1000);

            // Pass server-side time entry data to JavaScript
            @php
                // Collect all time entries for all project members (only actual time entries, not estimates)
                $allTimeEntries = collect();
                foreach ($data->members as $member) {
                    $memberTimeEntries = DB::table('time_entries')
                        ->where('user_id', $member->user_id)
                        ->where('project_id', $data->id)
                        ->select('user_id', 'entry_date', 'hours') // Only select fields we need
                        ->get();
                    $allTimeEntries = $allTimeEntries->concat($memberTimeEntries);
                }
                
                // Group by user_id and entry_date, then sum hours for each date
                $groupedTimeEntries = $allTimeEntries->groupBy('user_id')->map(function($userEntries) {
                    return $userEntries->groupBy('entry_date')->map(function($dateEntries) {
                        return $dateEntries->sum('hours');
                    });
                });
            @endphp
            
            window.memberTimeEntries = @json($groupedTimeEntries);
            
            // Debug: Log the time entry data structure
            console.log('Member time entries data (time_entries only, no estimates):', window.memberTimeEntries);

            const calendarContainer = $('.calendar-container');
            const ganttBarContainer = $('.gantt-bar-container');
            const scrollContainer = $('.scroll-container');
            const st = $('#st_date').val();
            const en = $('#en_date').val();
            
            // Set start date to 1 year before today
            let startDate = new Date();
            startDate.setFullYear(startDate.getFullYear() - 1);
            
            // Set end date to 10 years after project end date
            let endDate = new Date(en);
            endDate.setFullYear(endDate.getFullYear() + 10);
            
            const projectDurationDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            
            console.log('Calendar range:', startDate, 'to', endDate, '(' + Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + ' days)');
            
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

                // Only append to regular second-input elements, not member-time-calendar-row
                $('.second-input:not(.member-time-calendar-row)').append(inp);
                
                // Set data-user-id for the newly added input fields
                $('.second-input:not(.member-time-calendar-row)').each(function() {
                    const userId = $(this).data('user-id');
                    const taskId = $(this).data('task-id');
                    $(this).find('.inputss').attr('data-user-id', userId);
                    $(this).find('.inputss').attr('data-task-id', taskId);
                });

                // Calendar rows are now preloaded with actual data from the server
                
                // Populate member time calendar rows with time entry data
                populateMemberTimeCalendarRows();
            }

            // Function to populate member calendar rows with time entry data
            function populateMemberTimeCalendarRows() {
                $('.member-time-calendar-row').each(function() {
                    const userId = $(this).data('user-id');
                    const projectId = $(this).data('project-id');
                    const memberRow = $(this);
                    
                    // Clear any existing content
                    memberRow.empty();
                    
                    // Get time entry data for this user from the server-side rendered data
                    const userTimeEntries = window.memberTimeEntries && window.memberTimeEntries[userId] ? window.memberTimeEntries[userId] : {};
                    
                    // Create the same calendar structure but for member time entries
                    let memberInp = '';
                    
                    // Use the same date iteration as the main calendar
                    const currentDate = new Date(startDate);
                    while (currentDate <= endDate) {
                        const day = currentDate.getDate().toString().padStart(2, '0');
                        const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
                        const year = currentDate.getFullYear();
                        const dayOfWeek = currentDate.getDay();
                        const dateString = `${year}-${month}-${day}`;
                        const dayClass = (dayOfWeek === 0 || dayOfWeek === 6) ? 'calendar-day holiday' : 'calendar-day';
                        
                        // Get existing hours for this date (handle undefined gracefully)
                        const existingHours = userTimeEntries[dateString] || '';
                        
                        // Create input field for member time entry
                        memberInp += `<input type="number" 
                                             min="1" 
                                             max="8" 
                                             step="1" 
                                             class="${dayClass} inputsss member-time-input" 
                                             style="min-width: 24px;" 
                                             data-user-id="${userId}" 
                                             data-project-id="${projectId}" 
                                             data-date="${dateString}"
                                             value="${existingHours}"
                                             disabled
                                             >`;
                        
                        currentDate.setDate(currentDate.getDate() + 1);
                    }
                    
                    memberRow.append(memberInp);
                });
                
                // Load actual time entry data for these inputs
                loadMemberTimeEntryData();
            }

            // Function to load actual time entry data from the server
            function loadMemberTimeEntryData() {
                // Data is now preloaded server-side, no AJAX needed
                console.log('Member time entry data loaded from server-side rendering');
            }

            // Align Gantt bars with the calendar
            function alignGanttBars() {
                const dayWidth = $(".calendar-day").outerWidth(); // Width of a single day or week
                let ganttStartDate = new Date(); // Gantt chart start date (1 year before today)
                ganttStartDate.setFullYear(ganttStartDate.getFullYear() - 1);

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
                        
                        // Save state before drag action for undo functionality
                        saveStateBeforeAction();
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
                        let ganttStartDate = new Date(); // Gantt chart start date (1 year before today)
                        ganttStartDate.setFullYear(ganttStartDate.getFullYear() - 1);

                        // Calculate the start and end offsets
                        const startOffset = ui.position.left;
                        const endOffset = startOffset + $task.outerWidth();

                        // Calculate the current start and end dates
                        const currentStartDate = new Date(ganttStartDate);
                        currentStartDate.setDate(ganttStartDate.getDate() + Math.round(startOffset / dayWidth));

                        const currentEndDate = new Date(ganttStartDate);
                        currentEndDate.setDate(ganttStartDate.getDate() + Math.round(endOffset / dayWidth) - 1);

                        checkDates(initialStartDate, initialEndDate, currentStartDate, currentEndDate, $task.attr("data-task-id"));

                        // Update the task attributes with the correctly calculated dates
                        $task.attr('data-start-date', currentStartDate.toISOString().split('T')[0]);
                        $task.attr('data-end-date', currentEndDate.toISOString().split('T')[0]);
                        
                        updateTaskDates($task);
                        
                        // Check for overlaps after drag operation (with a slight delay to ensure DOM is updated)
                        setTimeout(() => {
                            if (typeof highlightOverlappingBars === 'function') {
                                highlightOverlappingBars();
                            }
                        }, 10);
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
                        
                        // Save state before resize action for undo functionality
                        saveStateBeforeAction();
                    },
                    resize: function(event, ui) {
                        const $task = $(this);
                        const dayWidth = $(".calendar-day").outerWidth();
                        let ganttStartDate = new Date();
                        ganttStartDate.setFullYear(ganttStartDate.getFullYear() - 1);

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
                        let ganttStartDate = new Date(); // Gantt chart start date (1 year before today)
                        ganttStartDate.setFullYear(ganttStartDate.getFullYear() - 1);
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
                        
                        // Check for overlaps after resize operation (with a slight delay to ensure DOM is updated)
                        setTimeout(() => {
                            if (typeof highlightOverlappingBars === 'function') {
                                highlightOverlappingBars();
                            }
                        }, 10);

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
                        // Always save the dates, regardless of overlap status
                        // Our comprehensive overlap detection will handle the visual highlighting
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
                                
                                // Re-check overlaps after saving
                                setTimeout(() => {
                                    if (typeof highlightOverlappingBars === 'function') {
                                        highlightOverlappingBars();
                                    }
                                }, 50);
                            }
                        });
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
                        // Always save the dates, regardless of overlap status
                        // Our comprehensive overlap detection will handle the visual highlighting
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
                                
                                // Re-check overlaps after saving
                                setTimeout(() => {
                                    if (typeof highlightOverlappingBars === 'function') {
                                        highlightOverlappingBars();
                                    }
                                }, 50);
                            }
                        });
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
            
            // Hide all member time calendar rows initially
            $('.member-time-calendar-row').hide();
            $('.member-time-entries').hide();
            
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
        // DISABLED: Drag-to-scroll conflicts with DHTMLX task drag/resize
        // Scroll using scrollbar or mousewheel instead
        console.log('Drag-to-scroll DISABLED to allow DHTMLX task interactions');
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

        // Function to save member order to server
        function saveMemberOrder(memberOrder) {
            const projectId = $('#project_id').val();
            
            $.ajax({
                url: '/projects/' + projectId + '/save-member-order',
                type: 'POST',
                data: {
                    member_order: memberOrder,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Member order saved successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error saving member order:', error);
                }
            });
        }

        // Function to load and apply saved member order
        function loadSavedMemberOrder() {
            const projectId = $('#project_id').val();
            
            $.ajax({
                url: '/projects/' + projectId + '/get-member-order',
                type: 'GET',
                success: function(response) {
                    if (response.member_order && response.member_order.length > 0) {
                        applyMemberOrder(response.member_order);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading member order:', error);
                }
            });
        }

        // Function to apply member order to the DOM
        function applyMemberOrder(memberOrder) {
            const $membersList = $('#team-members-list');
            const $timeInputContainer = $('#team-time-inputs');
            
            // Detach all member rows and time inputs
            const $memberRows = $membersList.find('.team-member-row').detach();
            const $timeInputs = $timeInputContainer.find('.time-input-row').detach();
            
            // Reorder based on saved order
            memberOrder.forEach(function(memberId) {
                const $matchingMember = $memberRows.filter('[data-member-id="' + memberId + '"]');
                const $matchingInput = $timeInputs.filter('[data-member-id="' + memberId + '"]');
                
                if ($matchingMember.length > 0) {
                    $membersList.append($matchingMember);
                }
                if ($matchingInput.length > 0) {
                    $timeInputContainer.append($matchingInput);
                }
            });
            
            // Append any members not in the saved order (in case new members were added)
            $memberRows.each(function() {
                if (!$(this).parent().length) {
                    $membersList.append($(this));
                }
            });
            $timeInputs.each(function() {
                if (!$(this).parent().length) {
                    $timeInputContainer.append($(this));
                }
            });
            
            refreshInputFieldAttributes();
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
                
                // Save the new order to the server
                saveMemberOrder(memberOrder);
                
                console.log('Team members reordered:', memberOrder);
            }
        });

        // Load saved member order on page load
        loadSavedMemberOrder();
        
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
          format: 'YYYY-MM-DD'
        }
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
        
        // Check for overlaps after sorting
        setTimeout(() => {
            highlightOverlappingBars();
        }, 100); // Small delay to ensure all DOM updates are complete
        
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
    
    // Save initial state for undo/redo functionality
    setTimeout(function() {
        saveState();
    }, 100); // Small delay to ensure all elements are fully rendered
});

// Handle expand/collapse functionality for member time entries
$(document).on('click', '.expand-arrow', function(e) {
    e.preventDefault();
    e.stopPropagation();
    console.log('Arrow clicked!'); // Debug log
    
    const arrow = $(this);
    const targetClass = arrow.data('target');
    const targetId = arrow.data('id');
    const projectId = arrow.data('project-id');
    
    console.log('Target class:', targetClass); // Debug log
    console.log('Target ID:', targetId); // Debug log
    console.log('Project ID:', projectId); // Debug log
    
    if (targetClass === 'member-time-entries') {
        const isExpanded = arrow.hasClass('expanded');
        
        console.log('Is expanded:', isExpanded); // Debug log
        
        // Toggle arrow state
        if (isExpanded) {
            arrow.removeClass('expanded').html('▶');
        } else {
            arrow.addClass('expanded').html('▼');
        }
        
        // Toggle time entries visibility - show/hide the preloaded calendar
        const timeEntriesDiv = $(`.member-time-entries[data-user-id="${targetId}"][data-project-id="${projectId}"]`);
        const calendarRow = timeEntriesDiv.find('.member-time-calendar-row');
        
        console.log('Time entries div found:', timeEntriesDiv.length); // Debug log
        console.log('Calendar row found:', calendarRow.length); // Debug log
        console.log('Calendar row HTML:', calendarRow.html() ? 'Has content' : 'Empty'); // Debug log
        console.log('Calendar row parent:', calendarRow.parent().attr('class')); // Debug log
        
        if (isExpanded) {
            // Collapse - hide the entire time entries container and calendar input row
            console.log('Collapsing...');
            timeEntriesDiv.slideUp(300, function() {
                console.log('Collapse complete');
            });
            
            // Also hide the calendar input row in the bottom section
            $(`.member-time-${targetId}`).slideUp(300);
        } else {
            // Expand - show the entire time entries container and calendar input row
            console.log('Expanding...');
            console.log('Time entries div is currently visible:', timeEntriesDiv.is(':visible'));
            console.log('Time entries div display style:', timeEntriesDiv.css('display'));
            timeEntriesDiv.slideDown(300, function() {
                console.log('Expand complete - now visible:', timeEntriesDiv.is(':visible'));
            });
            
            // Also show the calendar input row in the bottom section
            $(`.member-time-${targetId}`).slideDown(300);
        }
    }
});

// Function to update time entry data when inputs change
function updateMemberTimeSummary(userId, projectId) {
    let totalHours = 0;
    
    // Calculate total hours from all inputs for this member
    $(`.member-time-input[data-user-id="${userId}"][data-project-id="${projectId}"]`).each(function() {
        const hours = parseFloat($(this).val()) || 0;
        totalHours += hours;
    });
    
    // Get hourly rate from the existing data
    const hourlyRateElement = $(`.user-cost-${userId}`);
    const hourlyRateText = hourlyRateElement.text().replace(/[^0-9.-]+/g,"");
    const hourlyRate = parseFloat(hourlyRateText) || 0;
    
    const totalCost = totalHours * hourlyRate;
    
    // Update the summary display
    $(`.member-time-cost-${userId}`).text(formatCurrency(totalCost.toFixed(2)));
    $(`.member-time-hours-${userId}`).text(totalHours);
}

// Add event listener for input changes
$(document).on('input change', '.member-time-input', function() {
    const userId = $(this).data('user-id');
    const projectId = $(this).data('project-id');
    updateMemberTimeSummary(userId, projectId);
});
</script>

<script>
// ============================================
// DHTMLX GANTT INITIALIZATION
// ============================================
$(document).ready(function() {
    // Wait for calendar to be rendered first
    setTimeout(function() {
        // Get calendar start date to calculate accurate positioning
        // CRITICAL: Calendar starts 1 year before TODAY, not 1 year before project start!
        let calendarStartDate = new Date();
        calendarStartDate.setFullYear(calendarStartDate.getFullYear() - 1);
        
        console.log('Calendar start date (1 year before TODAY):', calendarStartDate.toISOString().split('T')[0]);
        console.log('Project start date from input:', $('#st_date').val());
        
        // Calculate width based on number of days - MUST be 25px per day
        const EXACT_DAY_WIDTH = 24; // FIXED: Each day cell MUST be exactly 25px
        const totalDays = $('.calendar-day').length;
        const calculatedWidth = EXACT_DAY_WIDTH * totalDays;
        
        console.log('EXACT Day width (FIXED):', EXACT_DAY_WIDTH, 'px');
        console.log('Total days rendered:', totalDays);
        console.log('Calculated total width:', calculatedWidth, 'px');
        
        // Set gantt_here container to match calculated calendar width
        $('#gantt_here').css('width', calculatedWidth + 'px');
        console.log('Set gantt_here width to:', calculatedWidth, 'px');
        
        // Configure date format
        gantt.config.date_format = "%Y-%m-%d";
        
        // Timeline configuration - FORCE 25px per day to match calendar
        gantt.config.scale_unit = "day";
        gantt.config.date_scale = "%d %M";
        gantt.config.step = 1;
        gantt.config.min_column_width = EXACT_DAY_WIDTH;
        gantt.config.max_column_width = EXACT_DAY_WIDTH; // Lock it to exactly 25px
        gantt.config.scale_height = 27;
        
        // Hide the grid completely
        gantt.config.grid_width = 0;
        
        // Task bar sizing
        gantt.config.bar_height = 20;
        gantt.config.row_height = 30;
        
        console.log('DHTMLX column width LOCKED to:', EXACT_DAY_WIDTH, 'px (matching calendar day width)');
        
        // Enable interactions with custom positioning handler
        gantt.config.drag_move = true;
        gantt.config.drag_resize = true;
        gantt.config.drag_progress = false;
        gantt.config.readonly = false;
        
        // Custom function to fix task positioning (now works WITH gantt.templates.task_position)
        function fixTaskPosition(taskId) {
            const task = gantt.getTask(taskId);
            
            // Force DHTMLX to re-render the task with our template
            gantt.refreshTask(taskId);
            
            const taskBar = $(`.gantt_task_line[task_id="${taskId}"]`);
            
            if (taskBar.length > 0) {
                const startDate = new Date(task.start_date);
                const endDate = new Date(task.end_date);
                
                const daysFromCalendarStart = Math.floor((startDate - calendarStartDate) / (1000 * 60 * 60 * 24)) + 1;
                const taskDurationDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                
                const exactLeft = daysFromCalendarStart * EXACT_DAY_WIDTH;
                const exactWidth = taskDurationDays * EXACT_DAY_WIDTH;
                
                console.log(`✓ Task "${task.text}" positioned: left=${exactLeft}px, width=${exactWidth}px`);
                
                return { exactLeft, exactWidth, daysFromCalendarStart, taskDurationDays };
            }
            return null;
        }
        
        // Hook into DHTMLX events to maintain positioning
        gantt.attachEvent("onBeforeTaskDrag", function(id, mode, e) {
            console.log('Starting drag for task:', id, 'mode:', mode);
            return true;
        });
        
        gantt.attachEvent("onTaskDrag", function(id, mode, task, original) {
            // During drag, DHTMLX will use our template automatically
            // No need to manually fix position here
            return true;
        });
        
        gantt.attachEvent("onAfterTaskDrag", function(id, mode, e) {
            // After drag, refresh the task to ensure template is applied
            const task = gantt.getTask(id);
            console.log('✓ Task dragged:', task.text, 'New dates:', task.start_date, '->', task.end_date);
            
            // Refresh to apply template positioning
            fixTaskPosition(id);
            
            // Save to server
            saveTaskToServer(id, task.start_date, task.end_date);
            
            return true;
        });
        
        gantt.attachEvent("onAfterTaskUpdate", function(id, item) {
            // Refresh task positioning after any update
            fixTaskPosition(id);
            return true;
        });
        
        // Function to save task dates to server
        function saveTaskToServer(taskId, startDate, endDate) {
            const formattedStartDate = gantt.date.date_to_str("%Y-%m-%d")(startDate);
            const formattedEndDate = gantt.date.date_to_str("%Y-%m-%d")(endDate);
            
            $.ajax({
                url: '/projects/save-dates',
                type: 'POST',
                data: {
                    task_id: taskId,
                    start_date: formattedStartDate,
                    end_date: formattedEndDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('✓ Task dates saved to server:', formattedStartDate, '->', formattedEndDate);
                },
                error: function(xhr, status, error) {
                    console.error('✗ Failed to save task dates:', error);
                }
            });
        }
        
        // Set start date to match calendar (1 year before project start)
        gantt.config.start_date = calendarStartDate;
        
        console.log('Gantt start date set to:', calendarStartDate);
        
        // CRITICAL FIX: Override DHTMLX's task positioning template to use our exact calculations
        gantt.templates.task_position = function(start, end, task) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            
            // Calculate exact position using our formula (matching fixTaskPosition)
            const daysFromCalendarStart = Math.floor((startDate - calendarStartDate) / (1000 * 60 * 60 * 24)) + 1;
            const taskDurationDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            
            const left = daysFromCalendarStart * EXACT_DAY_WIDTH;
            const width = taskDurationDays * EXACT_DAY_WIDTH;
            
            console.log(`Template positioning for "${task.text}": left=${left}px, width=${width}px`);
            
            return {
                left: left,
                width: width
            };
        };
        
        // Initialize gantt on the container
        gantt.init("gantt_here");
        
        console.log("DHTMLX Gantt container initialized on element:", document.getElementById('gantt_here'));
        console.log("DHTMLX Gantt container actual width:", $('#gantt_here').outerWidth(), 'px');
        
        // Load tasks from Laravel data
        const ganttTasks = {
            data: [
                @foreach ($data->tasks as $key => $item)
                @if ($item->start_date != null && $item->end_date != null)
                {
                    id: {{ $item->id }},
                    text: "T{{ $key + 1 }}",
                    start_date: "{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}",
                    end_date: "{{ \Carbon\Carbon::parse($item->end_date)->addDay()->format('Y-m-d') }}",
                    duration: {{ \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1 }},
                    progress: 0
                },
                @endif
                @endforeach
            ],
            links: []
        };
        
        console.log("Loading DHTMLX Gantt tasks:", ganttTasks.data.length, "tasks");
        console.log("Task data:", ganttTasks.data);
        
        // Parse and load data
        gantt.parse(ganttTasks);
        
        console.log("DHTMLX Gantt tasks parsed and loaded successfully");
        console.log("Gantt task count:", gantt.getTaskCount());
        console.log("Gantt visible tasks:", gantt.getVisibleTaskCount());
        
        // Render the gantt
        gantt.render();
        console.log("DHTMLX Gantt initialized successfully");
        
        // After render, verify and FORCE CORRECT positioning and width
        setTimeout(function() {
            const ganttCellWidth = $('.gantt_task_cell').outerWidth();
            console.log('Actual gantt_task_cell width after render:', ganttCellWidth, 'px');
            console.log('Expected:', EXACT_DAY_WIDTH, 'px');
            
            if (ganttCellWidth !== EXACT_DAY_WIDTH) {
                console.error('WARNING: gantt_task_cell width mismatch! Expected', EXACT_DAY_WIDTH, 'but got', ganttCellWidth);
            } else {
                console.log('SUCCESS: gantt_task_cell width matches exactly!');
            }
            
            // MANUALLY FIX each task bar position and width
            ganttTasks.data.forEach(task => {
                const taskBar = $(`.gantt_task_line[task_id="${task.id}"]`);
                
                if (taskBar.length > 0) {
                    const startDate = new Date(task.start_date);
                    const endDate = new Date(task.end_date);
                    
                    // Calculate days from calendar start + 1 day fix
                    const daysFromCalendarStart = Math.floor((startDate - calendarStartDate) / (1000 * 60 * 60 * 24)) + 1;
                    
                    // Calculate task duration in days
                    const taskDurationDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                    
                    // Calculate EXACT position and width
                    const exactLeft = daysFromCalendarStart * EXACT_DAY_WIDTH;
                    const exactWidth = taskDurationDays * EXACT_DAY_WIDTH;
                    
                    // FORCE the correct position and width
                    taskBar.css({
                        'left': exactLeft + 'px',
                        'width': exactWidth + 'px'
                    });
                    
                    console.log(`Task ${task.text}:`, {
                        startDate: task.start_date,
                        endDate: task.end_date,
                        daysFromStart: daysFromCalendarStart,
                        duration: taskDurationDays,
                        calculatedLeft: exactLeft + 'px',
                        calculatedWidth: exactWidth + 'px',
                        formula: `(${daysFromCalendarStart - 1} + 1 day fix) × ${EXACT_DAY_WIDTH}px = ${exactLeft}px position`,
                        widthFormula: `${taskDurationDays} days × ${EXACT_DAY_WIDTH}px = ${exactWidth}px width`
                    });
                }
            });
            
            console.log('✓ All task bars manually positioned with pixel-perfect accuracy!');
        }, 500);
        
    }, 1500); // Wait for calendar rendering
});
</script>

</body>
</html>
