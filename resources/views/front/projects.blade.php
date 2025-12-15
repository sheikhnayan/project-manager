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
     <!-- DHTMLX Gantt -->
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
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

        .second-input{
            display: flex;
        }

        .calendar-container {
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
        .scroll-container {
            overflow-x: scroll;
            width: 100%;
            cursor: grab;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
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

        /* Task bar styling */
        .gantt_task_line {
            background-color: #4A5568 !important;
            border: 1px solid #fff !important;
            border-radius: 5px;
        }
        
        .gantt_task_content {
            color: white;
            font-size: 12px;
            text-align: center;
        }
        
        /* Remove default task bar borders/shadows */
        .gantt_task_line.gantt_selected {
            box-shadow: none;
        }
        
        .gantt_task_row {
            border-bottom: 1px solid #ebebeb;
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
        
        /* Today marker line in gantt */
        .today-marker {
            background-color: #ff0000 !important;
            opacity: 0.8;
            width: 2px !important;
        }

        .gantt-weekend-cell{
            border-bottom: 1px solid #ccc !important;
        }
        
        /* Weekend styling for gantt scale cells */
        .gantt-weekend-cell span {
            color: #d32f2f !important;
            font-weight: 400 !important;
            background-color: #f7f7f7 !important;
        }
        
        .gantt-weekday-cell span {
            color: #000 !important;
            font-weight: normal !important;
        }

        /* Weekend styling for gantt timeline cells (below the scale) */
        .gantt-timeline-weekend {
            background-color: #f7f7f7 !important;
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

        /* .gantt_task_scale{
            display: none;
        } */

        .gantt_scale_line:first-child{
            background: #000 !important;
        }

         .gantt_scale_line:first-child .gantt_scale_cell span{
            color: #fff !important;
            font-size: 14px;
        }

        .gantt_scale_line:not(:first-child) .gantt_scale_cell span{
            display: inline-block;
            vertical-align: top;
            border: 1px solid #ccc;
            /* padding-top: 1px; */
            font-size: 10px;
            box-sizing: border-box;
            margin: 0;
            border-top: unset;
            width: 24px !important;
            height: 20px !important;
            text-align: center;
        }

        

        /* Top scale row (month) */
.gantt_scale_line:first-child {
    height: 32px !important;
    line-height: 32px !important;  /* vertical center text */
}

/* All other scale rows (days, etc.) */
.gantt_scale_line:not(:first-child) {
    height: 20px !important;
    line-height: 20px !important;  /* vertical center text */
}

/* Weekend and weekday styling - only apply to day row (second scale line) */
.gantt_scale_line:not(:first-child) .gantt-weekend-cell span {
    color: #d32f2f !important;
}

.gantt_scale_line:not(:first-child) .gantt-weekday-cell span {
    color: #000 !important;
}

/* Make vertical scrollbar background transparent */
.gantt_ver_scroll {
    background-color: transparent !important;
}

.gantt_layout_cell.scrollVer_cell {
    background-color: transparent !important;
}

.gantt_layout_cell{
    border: unset !important;
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
        <div class="p-4 rounded-lg" style="background: #fff !important;; border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
            <div class="content p-2" style="padding-left: 0px !important; display: block; margin-bottom: 40px;">
                <div style="float: left; margin-top: 6px;">
                    <h5 style="font-size: 20px; font-weight: 600;">{{ $data->name }}</h5>
                </div>
                <div class="flex items-center " style="float: right;">
                            <!-- Undo/Redo Buttons -->
                            <div style="border: 1px solid #eee; border-radius: 4px; margin-right: 8px;">
                                <button class="text-gray-600 hover:text-black" id="undoBtn" title="Undo" disabled>
                                    <i class="fas fa-undo" style="padding: 0.6rem 0.8rem; font-size: 0.8rem; color: #000;"></i>
                                </button>
                                <span style='content: ""; height: 24px; width: 1px; background: #eee; display: inline-block; padding-top: 0px; margin-top: 5px; margin-bottom: -5px;'></span>
                                <button class="text-gray-600 hover:text-black" id="redoBtn" title="Redo" disabled>
                                    <i class="fas fa-redo" style="padding: 0.6rem 0.8rem; font-size: 0.8rem; color: #000;"></i>
                                </button>
                            </div>
                            
                            <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;">
                                <i class="fas fa-home" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; color: #000;"></i>
                                {{-- <img src="{{ asset('house.png') }}" style="border: 1px solid #000;padding: 10px 12px;border-radius: 4px;border-color: #eee; "> --}}
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
                                    border-color: #eee !important;
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
                                /* Overlapping task bar styling */
                                .gantt_task_line.task-overlap {
                                    background-color: #dc2626 !important; /* red */
                                    border-color: #dc2626 !important;
                                }
                                .gantt_task_line.task-overlap .gantt_task_progress {
                                    background-color: rgba(255,255,255,0.4) !important;
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
                            {{-- <a href="/projects/{{ $data->id }}/v2" class="bg-green-600 text-white px-4 py-2 rounded" style="font-size: 13px; padding: 0.4rem 1rem; cursor: pointer; margin-right: 8px; background-color: #059669 !important; display: inline-flex; align-items: center; height: 34px;" title="New Clean Version">
                                <i class="fas fa-rocket" style="margin-right: 6px;"></i> V2
                            </a>
                            
                            <a href="/projects/{{ $data->id }}/dhtmlx" class="bg-blue-600 text-white px-4 py-2 rounded" style="font-size: 13px; padding: 0.4rem 1rem; cursor: pointer; margin-right: 8px; background-color: #2563eb !important; display: inline-flex; align-items: center; height: 34px;" title="Open DHTMLX Gantt">
                                <i class="fas fa-crown" style="margin-right: 6px;"></i> DHTMLX Gantt
                            </a> --}}
                            
                            <a class="bg-black text-white px-4 py-2 rounded" id="addMemberButton" style="font-size: 13px; padding:0.4rem 1rem; cursor: pointer; margin-right: 8px;">+  Add to Team</a>
                            {{-- <a href="/projects/create" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.4rem 1rem;">+  Add Project</a> --}}
                </div>
            </div>
            <div class="content mains">
                <div class="task-list" style="border-bottom-right-radius: 0px; padding-right: 0px; margin-top: 0px;">
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
                        {{-- <div class="calendar-container">
                            <!-- JavaScript will populate the months and dates here -->
                        </div> --}}
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
                        <div id="gantt_here" data-check-height="{{ ($data->tasks->count() * 32) + 52 }}" style='width:100% !important; height:{{ ($data->tasks->count() * 32) + 52 +15}}px;'></div>
                    </div>
                </div>
            </div>
            <div class="content us" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
                <div class="task-list" style="border-bottom-right-radius: 0px; border-right: 0px; padding-right: 0px; padding-top: 0px">
                    <div class="task-header" style="border-top-left-radius: 4px; margin-bottom: 0px; padding: 10px; padding-right: 0px;">
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
        





        $(function () {

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
            const scrollContainer = $('.scroll-container');
            const st = $('#st_date').val();
            const en = $('#en_date').val();
            
            // Find earliest task start date
            const taskDates = [
                @foreach ($data->tasks as $item)
                    @if ($item->start_date != null)
                        "{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}",
                    @endif
                @endforeach
            ].filter(date => date).map(date => new Date(date));
            
            const earliestTaskDate = taskDates.length > 0 ? new Date(Math.min(...taskDates)) : new Date(st);
            
            // Set start date to 1 year before earliest task date (make it global for home button)
            window.calendarStartDate = new Date(earliestTaskDate);
            window.calendarStartDate.setFullYear(window.calendarStartDate.getFullYear() - 1);
            let startDate = window.calendarStartDate;
            
            // Set end date to 10 years after project end date
            let endDate = new Date(en);
            endDate.setFullYear(endDate.getFullYear() + 10);
            
            console.log('Earliest task date:', earliestTaskDate.toDateString());
            
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

            // Initial render
            renderCalendar();
            
            // Scroll to 1 week before today - call directly after render
            scrollToOneWeekBefore();
            
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
            
            // Function to scroll to 1 week before today
            function scrollToOneWeekBefore() {
                const today = new Date();
                const oneWeekBefore = new Date(today);
                oneWeekBefore.setDate(today.getDate() - 7);
                
                // Calculate days from calendar start (which is 1 year before today) to target date
                const daysFromStart = Math.floor((oneWeekBefore - startDate) / (1000 * 60 * 60 * 24));
                const scrollPosition = daysFromStart * 24; // 24px per day
                
                console.log('[Auto-scroll] Calendar starts at:', startDate.toDateString());
                console.log('[Auto-scroll] Scrolling to 1 week before today:', oneWeekBefore.toDateString());
                console.log('[Auto-scroll] Days from calendar start:', daysFromStart);
                console.log('[Auto-scroll] Scroll position:', scrollPosition + 'px');
                
                $('.scroll-container').scrollLeft(scrollPosition);
                gantt.scrollTo(scrollPosition, null); // Sync gantt chart too
                console.log('[Auto-scroll] Both calendars scrolled to position:', scrollPosition);
            }
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


{{-- <script>
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
</script> --}}

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

    // Arrow key navigation for time entry inputs
    $(document).on('keydown', '.inputss, .inputsss', function(e) {
        const currentInput = $(this);
        const allInputs = $('.inputss, .inputsss').not(':disabled');
        const currentIndex = allInputs.index(currentInput);
        
        // Get the date of current input
        const currentDate = currentInput.data('date');
        
        // Find inputs in the same row (same user/task)
        const currentRow = currentInput.parent();
        const rowInputs = currentRow.find('.inputss, .inputsss').not(':disabled');
        const rowIndex = rowInputs.index(currentInput);
        
        let targetInput = null;
        
        switch(e.keyCode) {
            case 37: // Left arrow
                e.preventDefault();
                if (rowIndex > 0) {
                    targetInput = rowInputs.eq(rowIndex - 1);
                }
                break;
                
            case 39: // Right arrow
                e.preventDefault();
                if (rowIndex < rowInputs.length - 1) {
                    targetInput = rowInputs.eq(rowIndex + 1);
                }
                break;
                
            case 38: // Up arrow
                e.preventDefault();
                // Find the input above with the same date
                const rowsAbove = currentRow.prevAll('.second-input, .time-input-row, .member-time-calendar-row');
                for (let i = 0; i < rowsAbove.length; i++) {
                    const inputAbove = $(rowsAbove[i]).find(`[data-date="${currentDate}"]`).not(':disabled');
                    if (inputAbove.length > 0) {
                        targetInput = inputAbove.first();
                        break;
                    }
                }
                break;
                
            case 40: // Down arrow
                e.preventDefault();
                // Find the input below with the same date
                const rowsBelow = currentRow.nextAll('.second-input, .time-input-row, .member-time-calendar-row');
                for (let i = 0; i < rowsBelow.length; i++) {
                    const inputBelow = $(rowsBelow[i]).find(`[data-date="${currentDate}"]`).not(':disabled');
                    if (inputBelow.length > 0) {
                        targetInput = inputBelow.first();
                        break;
                    }
                }
                break;
        }
        
        // Focus and select the target input
        if (targetInput && targetInput.length > 0) {
            targetInput.focus().select();
        }
    });

    // Function to update project totals without page reload
        async function updateProjectTotals(project_id) {
            try {
                const response = await fetch(`/projects/${project_id}/totals`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Update progress rings
                    if (data.budget_progress !== undefined) {
                        updateProgressRing('budget', data.budget_progress, data.budget_total, data.budget_spent);
                    }
                    if (data.hour_progress !== undefined) {
                        updateProgressRing('hour', data.hour_progress, data.hour_total, data.hour_spent);
                    }
                    if (data.task_progress !== undefined) {
                        updateProgressRing('task', data.task_progress, data.task_total, data.task_completed);
                    }
                }
            } catch (error) {
                console.error('Error updating project totals:', error);
            }
        }

        // Function to update individual progress ring
        function updateProgressRing(type, percentage, total, spent) {
            const ringId = type === 'budget' ? 'budget-ring' : type === 'hour' ? 'hour-ring' : 'task-ring';
            const ring = document.getElementById(ringId);
            if (!ring) return;

            const circumference = 2 * Math.PI * 40;
            const offset = circumference - (percentage / 100) * circumference;
            
            ring.style.strokeDashoffset = offset;
            
            // Update text
            const container = ring.closest('.circle-progess');
            if (container) {
                const progressText = container.querySelector('.progress-text');
                if (progressText) {
                    if (type === 'task') {
                        progressText.textContent = `${spent}/${total}`;
                    } else {
                        progressText.textContent = `${Math.round(percentage)}%`;
                    }
                }
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
                        const responseData = await response.json();

                        $('.user-hour-'+user_id).html(responseData.data.total);
                        $('.user-cost-'+user_id).html(responseData.data.cost);

                        // Reload estimate section without skeleton loader
                        $.ajax({
                            url: '/projects/reload-data/' + project_id,
                            method: 'GET',
                            success: function(html) {
                                $('#fetch').html(html);
                                setTimeout(() => {
                                    initProgressRings();
                                }, 10);
                            }
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
                    const responseData = await response.json();

                    // Update user-specific hours and cost
                    $('.user-hour-'+user_id).html(Math.round(responseData.data.total));
                    $('.user-cost-'+user_id).html(responseData.data.cost);

                    // Reload estimate section without skeleton loader
                    $.ajax({
                        url: '/projects/reload-data/' + project_id,
                        method: 'GET',
                        success: function(html) {
                            $('#fetch').html(html);
                            setTimeout(() => {
                                initProgressRings();
                            }, 10);
                        }
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

        document.getElementById('app-skeleton').remove();
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
        
        // Reorder task items
        $.each(items, function(i, item) {
            $('.mains .task-list').append(item);
        });
        
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
    // Home button - scroll both calendars to 1 week before today
    $('#home').on('click', function() {
        const today = new Date();
        const oneWeekBefore = new Date(today);
        oneWeekBefore.setDate(oneWeekBefore.getDate() - 7);
        
        // Use the actual calendar start date
        const calendarStart = window.calendarStartDate || new Date();
        
        // Calculate scroll position based on days from calendar start
        const daysFromStart = Math.floor((oneWeekBefore - calendarStart) / (1000 * 60 * 60 * 24));
        const scrollPosition = daysFromStart * 24; // 24px per day
        
        console.log('Scrolling to 1 week before today:', oneWeekBefore.toDateString());
        console.log('Days from calendar start:', daysFromStart, '| Scroll position:', scrollPosition);
        
        // Scroll gantt first (instant)
        gantt.scrollTo(Math.max(0, scrollPosition), null);
        
        // Then scroll bottom calendar with animation (will sync back via event)
        $('.scroll-container').animate({
            scrollLeft: scrollPosition
        }, 400);
    });
});
</script>

<script>
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

<script type="text/javascript">
        // DHTMLX Gantt Configuration
        const EXACT_DAY_WIDTH = 24;
        
        // Find earliest task start date for DHTMLX Gantt
        const ganttTaskDates = [
            @foreach ($data->tasks as $item)
                @if ($item->start_date != null)
                    "{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}",
                @endif
            @endforeach
        ].filter(date => date).map(date => new Date(date));
        
        const ganttEarliestTaskDate = ganttTaskDates.length > 0 ? new Date(Math.min(...ganttTaskDates)) : new Date('{{ $data->start_date }}');
        
        // Set calendar start date to 1 year before earliest task date
        const calendarStartDate = new Date(ganttEarliestTaskDate);
        calendarStartDate.setFullYear(calendarStartDate.getFullYear() - 1);
        
        // Set end date to 10 years after project end
        const projectEndDate = new Date('{{ $data->end_date }}');
        const calendarEndDate = new Date(projectEndDate);
        calendarEndDate.setFullYear(calendarEndDate.getFullYear() + 10);
        
        console.log('DHTMLX Gantt - Earliest task:', ganttEarliestTaskDate.toDateString(), '| Calendar start:', calendarStartDate.toDateString());
        
        // Configure date format
        gantt.config.date_format = "%Y-%m-%d";
        
        // Move scrollbar outside the chart
        gantt.config.layout = {
            css: "gantt_container",
            rows: [
                {
                    cols: [
                        {view: "timeline", scrollX: "scrollHor", scrollY: "scrollVer"},
                        {view: "scrollbar", id: "scrollVer", group:"vertical"}
                    ]
                },
                {view: "scrollbar", id: "scrollHor", group:"horizontal"}
            ]
        };
        
        // Timeline configuration - FORCE 24px per day
gantt.config.scales = [
    { unit: "month", step: 1, format: "%F", height: 32 }, // Top row: Month
    { 
        unit: "day", 
        step: 1, 
        format: "%d", 
        height: 20,
        css: function(date) {
            const dayOfWeek = date.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                return "gantt-weekend-cell";
            }
            return "gantt-weekday-cell";
        }
    }
];

gantt.config.scale_height = 52;  // Enough height for two rows
gantt.config.min_column_width = 24;
gantt.config.max_column_width = 24;
        
        // Hide the grid completely
        gantt.config.grid_width = 0;
        
        // Task bar sizing
        gantt.config.bar_height = 20;
        gantt.config.row_height = 29.5;
        
        // Enable interactions
        gantt.config.drag_move = true;
        gantt.config.drag_resize = true;
        gantt.config.drag_progress = false;
        gantt.config.readonly = false;
        
        // Disable task linking/connections
        gantt.config.drag_links = false;
        gantt.config.show_links = false;
        
        // Set start date to match calendar
        gantt.config.start_date = calendarStartDate;
        gantt.config.end_date = calendarEndDate;
        
        // Override task positioning template
        gantt.templates.task_position = function(start, end, task) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            
            const daysFromCalendarStart = Math.floor((startDate - calendarStartDate) / (1000 * 60 * 60 * 24)) + 1;
            const taskDurationDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            
            const left = daysFromCalendarStart * EXACT_DAY_WIDTH;
            const width = taskDurationDays * EXACT_DAY_WIDTH;
            
            return {
                left: left,
                width: width
            };
        };

        // Style timeline cells for weekends
        gantt.templates.timeline_cell_class = function(task, date) {
            const dayOfWeek = date.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                return "gantt-timeline-weekend";
            }
            return "";
        };
        
        // Initialize gantt
        gantt.init("gantt_here");
        
        // Load tasks from Laravel data
        const ganttTasks = {
            data: [
                @foreach ($data->tasks as $key => $item)
                @if ($item->start_date != null && $item->end_date != null)
                {
                    id: {{ $item->id }},
                    text: "T{{ $key + 1 }}",
                    start_date: "{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}",
                    end_date: "{{ \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') }}",
                    duration: {{ \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1 }},
                    progress: 0
                },
                @endif
                @endforeach
            ]
        };
        
        // Parse and load the tasks
        gantt.parse(ganttTasks);
        
        console.log('DHTMLX Gantt initialized with', ganttTasks.data.length, 'tasks');
        console.log('Tasks data:', ganttTasks.data);

        // Provide missing applyWeekendStyling referenced after renders
        function applyWeekendStyling(){
            // Weekend cells already styled via templates; keep as safe no-op.
            // If custom styling needed later, add here.
            // console.log('[applyWeekendStyling] executed');
        }

        // ---------------- Overlap Detection ----------------
        // Returns true if two ranges overlap (inclusive)
        function rangesOverlap(aStart, aEnd, bStart, bEnd){
            return aStart < bEnd && bStart < aEnd; // half-open logic
        }

        function detectAndMarkOverlaps(){
            const tasks = [];
            gantt.eachTask(function(t){
                tasks.push({ id: t.id, start: t.start_date, end: t.end_date });
            });
            tasks.sort((a,b)=> a.start - b.start);
            const overlapping = new Set();
            for(let i=0;i<tasks.length;i++){
                for(let j=i+1;j<tasks.length;j++){
                    if(tasks[j].start >= tasks[i].end){
                        break; // no more overlaps possible with tasks[i]
                    }
                    if(rangesOverlap(tasks[i].start, tasks[i].end, tasks[j].start, tasks[j].end)){
                        overlapping.add(tasks[i].id);
                        overlapping.add(tasks[j].id);
                    }
                }
            }
            window.ganttOverlappingIds = overlapping;
            console.log('[Overlap] overlapping task ids:', Array.from(overlapping));
            gantt.render();
        }

        // Template to apply overlap class
        gantt.templates.task_class = function(start, end, task){
            if(window.ganttOverlappingIds && window.ganttOverlappingIds.has(task.id)){
                return 'task-overlap';
            }
            return '';
        };
        // Initial detection after load
        detectAndMarkOverlaps();
        // ---------------- End Overlap Detection ----------------

        // ---------------- Undo / Redo Implementation ----------------
        // Stacks to keep historical snapshots of all task dates
        let undoStack = [];
        let redoStack = [];

        // Date helpers
        const dateToStr = gantt.date.date_to_str("%Y-%m-%d");
        const strToDate = gantt.date.str_to_date("%Y-%m-%d");

        // Capture current state of ALL task start/end dates
        function captureAllTasks() {
            const snapshot = [];
            gantt.eachTask(function(task){
                snapshot.push({
                    id: task.id,
                    start_date: dateToStr(task.start_date),
                    end_date: dateToStr(task.end_date)
                });
            });
            return snapshot;
        }

        // Apply a previously captured snapshot to the gantt (restore dates)
        function applySnapshot(snapshot) {
            snapshot.forEach(function(item){
                if (gantt.isTaskExists(item.id)) {
                    const task = gantt.getTask(item.id);
                    task.start_date = strToDate(item.start_date);
                    task.end_date = strToDate(item.end_date);
                    task.duration = Math.ceil((task.end_date - task.start_date) / (1000 * 60 * 60 * 24));
                    gantt.updateTask(task.id);
                }
            });
            gantt.render();
            detectAndMarkOverlaps();
            // Optionally persist restored dates to server (batch)
            snapshot.forEach(function(item){
                $.ajax({
                    url: '/projects/save-dates',
                    type: 'POST',
                    data: {
                        stoppedStartDate: item.start_date,
                        stoppedEndDate: item.end_date,
                        task_id: item.id,
                        _token: '{{ csrf_token() }}'
                    }
                });
                $(`.start-${item.id}`).html(formatDateForDisplay(item.start_date));
            });
        }

        function updateUndoRedoButtons(){
            const undoDisabled = undoStack.length === 0;
            const redoDisabled = redoStack.length === 0;
            $('#undoBtn').prop('disabled', undoDisabled);
            $('#redoBtn').prop('disabled', redoDisabled);
            // Debug logs
            console.log('[Undo/Redo] Stack sizes => undo:', undoStack.length, 'redo:', redoStack.length);
            console.log('[Undo/Redo] Buttons => undoDisabled:', undoDisabled, 'redoDisabled:', redoDisabled);
        }

        // Initialize button states
        updateUndoRedoButtons();

        // Before any drag (move/resize/progress) store snapshot for undo
        gantt.attachEvent("onBeforeTaskDrag", function(id, mode, e){
            // Push current state BEFORE modification
            undoStack.push(captureAllTasks());
            // Any new change invalidates redo history
            redoStack = [];
            updateUndoRedoButtons();
            return true;
        });

        // Undo button click
        $('#undoBtn').on('click', function(){
            if (undoStack.length === 0) return;
            // Current becomes a redo candidate
            redoStack.push(captureAllTasks());
            const previous = undoStack.pop();
            applySnapshot(previous);
            updateUndoRedoButtons();
            console.log('[Undo] Applied previous snapshot');
        });

        // Redo button click
        $('#redoBtn').on('click', function(){
            if (redoStack.length === 0) return;
            // Current goes back to undo stack
            undoStack.push(captureAllTasks());
            const nextState = redoStack.pop();
            applySnapshot(nextState);
            updateUndoRedoButtons();
            console.log('[Redo] Re-applied next snapshot');
        });
        // ---------------- End Undo / Redo Implementation ----------------
        
        // Scroll gantt to 1 week before today on load
        setTimeout(function() {
            const today = new Date();
            const oneWeekBefore = new Date(today);
            oneWeekBefore.setDate(oneWeekBefore.getDate() - 7);
            const daysFromStart = Math.floor((oneWeekBefore - calendarStartDate) / (1000 * 60 * 60 * 24));
            const scrollPosition = daysFromStart * EXACT_DAY_WIDTH;
            
            gantt.scrollTo(Math.max(0, scrollPosition), null);
            console.log('Gantt scrolled to 1 week before today:', oneWeekBefore.toDateString());
            
            // Add red vertical line for today's date using CSS
            const todayDaysFromStart = Math.floor((today - calendarStartDate) / (1000 * 60 * 60 * 24));
            const todayPosition = todayDaysFromStart * EXACT_DAY_WIDTH;
            
            const ganttTask = document.querySelector('.gantt_task');
            if (ganttTask) {
                const existingLine = ganttTask.querySelector('.today-marker-line');
                if (existingLine) existingLine.remove();
                
                const todayLine = document.createElement('div');
                todayLine.className = 'today-marker-line';
                todayLine.style.position = 'absolute';
                todayLine.style.left = todayPosition + 'px';
                todayLine.style.top = '52px';
                todayLine.style.width = '2px';
                todayLine.style.height = '100%';
                todayLine.style.backgroundColor = '#ff0000';
                todayLine.style.zIndex = '10';
                todayLine.style.pointerEvents = 'none';
                ganttTask.appendChild(todayLine);
            }
        }, 200);
        
        // Re-apply after gantt renders (scrolling, etc.)
        let initialRenderHandled = false;
        gantt.attachEvent("onGanttRender", function() {
            setTimeout(applyWeekendStyling, 10);
            if(!initialRenderHandled && window.AppLoader){
                // Hide skeleton once the first render occurs
                setTimeout(function(){ window.AppLoader.hide && window.AppLoader.hide(); }, 80);
                initialRenderHandled = true;
            }
            
            // Re-add today line after render
            setTimeout(function() {
                const today = new Date();
                const todayDaysFromStart = Math.floor((today - calendarStartDate) / (1000 * 60 * 60 * 24));
                const todayPosition = todayDaysFromStart * EXACT_DAY_WIDTH;
                
                const ganttTask = document.querySelector('.gantt_task');
                if (ganttTask) {
                    const existingLine = ganttTask.querySelector('.today-marker-line');
                    if (existingLine) existingLine.remove();
                    
                    const todayLine = document.createElement('div');
                    todayLine.className = 'today-marker-line';
                    todayLine.style.position = 'absolute';
                    todayLine.style.left = todayPosition + 'px';
                    todayLine.style.top = '52px';
                    todayLine.style.width = '2px';
                    todayLine.style.height = '100%';
                    todayLine.style.backgroundColor = '#ff0000';
                    todayLine.style.zIndex = '10';
                    todayLine.style.pointerEvents = 'none';
                    ganttTask.appendChild(todayLine);
                }
            }, 50);
            
            return true;
        });
        
        // Simple smooth scroll synchronization - both containers sync instantly
        let isGanttScrolling = false;
        let isBottomScrolling = false;
        let bottomScrollStopTimeout;
        let ganttScrollStopTimeout;
        
        // Recalibration function - ensures perfect alignment after scrolling stops
        function recalibrateScrollPosition() {
            const bottomScrollPos = $('.scroll-container').scrollLeft();
            const ganttScrollPos = gantt.getScrollState().x;
            if (Math.abs(bottomScrollPos - ganttScrollPos) > 1) {
                isGanttScrolling = true;
                $('.scroll-container').scrollLeft(ganttScrollPos);
                setTimeout(() => { isGanttScrolling = false; }, 10);
            }
        }
        
        // When gantt scrolls, instantly sync bottom calendar
        gantt.attachEvent("onGanttScroll", function(left, top) {
            if (isBottomScrolling) return true; // Prevent feedback loop
            isGanttScrolling = true;
            $('.scroll-container').scrollLeft(left);
            setTimeout(() => { isGanttScrolling = false; }, 10);
            
            // Recalibrate after scrolling stops
            clearTimeout(ganttScrollStopTimeout);
            ganttScrollStopTimeout = setTimeout(recalibrateScrollPosition, 300);
            
            return true;
        });
        
        // When bottom calendar scrolls, instantly sync gantt
        $('.scroll-container').on('scroll', function() {
            if (isGanttScrolling) return; // Prevent feedback loop
            isBottomScrolling = true;
            const scrollLeft = $(this).scrollLeft();
            gantt.scrollTo(scrollLeft, null);
            setTimeout(() => { isBottomScrolling = false; }, 10);
            
            // Recalibrate after scrolling stops
            clearTimeout(bottomScrollStopTimeout);
            bottomScrollStopTimeout = setTimeout(recalibrateScrollPosition, 300);
        });
        
        // Mouse wheel scroll handlers - DAILY VIEW (0.8x speed)
        $('.scroll-container').on('wheel', function(e) {
            const deltaY = e.originalEvent.deltaY;
            const deltaX = e.originalEvent.deltaX;
            
            // If vertical scrolling is dominant, convert to horizontal
            if (Math.abs(deltaY) > Math.abs(deltaX)) {
                e.preventDefault();
                const scrollAmount = deltaY * 0.8;
                const currentScroll = $(this).scrollLeft();
                const newScroll = currentScroll + scrollAmount;
                $(this).scrollLeft(newScroll);
            }
        });
        
        // Gantt container wheel handler
        const ganttContainer = document.getElementById('gantt_here');
        if (ganttContainer) {
            ganttContainer.addEventListener('wheel', function(e) {
                const deltaY = e.deltaY;
                const deltaX = e.deltaX;
                
                // If vertical scrolling is dominant, convert to horizontal
                if (Math.abs(deltaY) > Math.abs(deltaX)) {
                    e.preventDefault();
                    const scrollAmount = deltaY * 0.8;
                    const currentScroll = gantt.getScrollState().x;
                    const newScroll = currentScroll + scrollAmount;
                    gantt.scrollTo(newScroll, null);
                }
            }, { passive: false });
            
            // MutationObserver to detect when gantt re-renders the scale
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.target.classList.contains('gantt_scale_line')) {
                        // Weekend styling is now handled by gantt.templates.scale_cell_class
                        // No manual styling needed
                    }
                });
            });
            
            observer.observe(ganttContainer, {
                childList: true,
                subtree: true,
                attributes: false
            });
        }
        
        // Drag-to-scroll functionality for calendar headers (not time inputs)
        let isDragging = false;
        let dragStartX = 0;
        let dragScrollLeft = 0;
        let dragScrollContainer = null;
        
        // Only allow dragging on calendar headers (not on time input rows)
        $(document).on('mousedown', '.calendar-container, .calendar-day, .month-header, .month-container', function(e) {
            // Don't start drag if clicking on an input
            if ($(e.target).is('input, button, a')) return;
            
            dragScrollContainer = $(this).closest('.scroll-container');
            if (dragScrollContainer.length === 0) return;
            
            isDragging = true;
            dragStartX = e.pageX;
            dragScrollLeft = dragScrollContainer.scrollLeft();
            dragScrollContainer.css('cursor', 'grabbing');
            e.preventDefault();
        });
        
        $(document).on('mousemove', function(e) {
            if (!isDragging || !dragScrollContainer) return;
            e.preventDefault();
            const walk = (dragStartX - e.pageX) * 1.5; // 1.5x for more responsive drag
            dragScrollContainer.scrollLeft(dragScrollLeft + walk);
        });
        
        $(document).on('mouseup', function() {
            if (isDragging && dragScrollContainer) {
                dragScrollContainer.css('cursor', 'grab');
            }
            isDragging = false;
            dragScrollContainer = null;
        });
        
        // Event handlers for drag/resize
        gantt.attachEvent("onAfterTaskDrag", function(id, mode, e) {
            const task = gantt.getTask(id);
            console.log('Task dragged:', task.text, 'New dates:', task.start_date, '->', task.end_date);
            
            // Save to server
            $.ajax({
                url: '/projects/save-dates',
                type: 'POST',
                data: {
                    stoppedStartDate: gantt.date.date_to_str("%Y-%m-%d")(task.start_date),
                    stoppedEndDate: gantt.date.date_to_str("%Y-%m-%d")(task.end_date),
                    task_id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: async function(response) {
                    console.log('✓ Task dates saved to server');
                    $(`.start-${id}`).html(formatDateForDisplay(gantt.date.date_to_str("%Y-%m-%d")(task.start_date)));
                    
                    // Update project totals dynamically without page reload
                    const project_id = {{ $project_id ?? 'null' }};
                    if (project_id) {
                        await updateProjectTotals(project_id);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving task dates:', error);
                }
            });
            // Recompute overlaps after drag or resize
            detectAndMarkOverlaps();
            
            return true;
        });
    </script>




</body>
</html>
