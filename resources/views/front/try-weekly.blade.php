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
            width: 32px !important;
            min-width: 32px !important;
            max-width: 32px !important;
            flex: 0 0 32px !important; /* Prevent flexbox shrink/stretch */
            height: 20px !important;
            text-align: center;
            border: 1px solid #ccc;
            box-sizing: border-box;
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
        .calendar-day {
            display: inline-block;
            vertical-align: top;
            border: 1px solid #ccc;
            padding-top: 1px;
            font-size: 10px;
            box-sizing: border-box;
            margin: 0;
            border-top: unset;
            width: 32px !important;
            min-width: 32px !important;
            max-width: 32px !important;
            height: 20px !important;
            text-align: center;
            flex-shrink: 0;
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
        
        /* Hide ALL scrollbars in gantt chart area - global rule */
        #gantt_here *::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        
        /* Hide gantt scrollbars */
        #gantt_here,
        .gantt_container,
        .gantt_task,
        .gantt_task_scroll,
        .gantt_layout_cell,
        .gantt_data_area {
            scrollbar-width: none !important; /* Firefox */
            -ms-overflow-style: none !important; /* IE and Edge */
        }
        
        #gantt_here::-webkit-scrollbar,
        .gantt_container::-webkit-scrollbar,
        .gantt_task::-webkit-scrollbar,
        .gantt_task_scroll::-webkit-scrollbar,
        .gantt_layout_cell::-webkit-scrollbar,
        .gantt_data_area::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
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
        
        /* Today marker line in gantt */
        .today-marker {
            background-color: #ff0000 !important;
            opacity: 0.8;
            width: 2px !important;
        }
        
        /* Weekend styling for gantt scale cells */
        .gantt-weekend-cell span {
            color: #d32f2f !important;
            font-weight: bold !important;
        }
        
        .gantt-weekday-cell span {
            color: #000 !important;
            font-weight: normal !important;
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
            width: 32px !important;
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
.gantt_layout_cell{
            border-width: 0px !important;
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
        <div class="p-4 rounded-lg" style="background: #fff !important; border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
            <div class="content p-2" style="padding-left: 0px !important; display: block; margin-bottom: 40px;">
                <div style="float: left; margin-top: 6px;">
                    <h5 style="font-size: 20px; font-weight: 600;">{{ $data->name }}</h5>
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
                               <i class="fas fa-home" style="border: 1px solid #000; padding:0.6rem 0.8rem; border-radius:4px; border-color:#eee; font-size: 0.8rem; color: #000;"></i>
                                {{-- <img src="{{ asset('house.png') }}" style="border: 1px solid #000;padding: 10px 12px;border-radius: 4px;border-color: #eee; "> --}}
                            </button>
                            <div style="border: 1px solid #eee; border-radius: 4px;  margin-right: 8px; height: 34px;width: 170px; display:flex;justify-content: center;">
                                <a href="/projects/{{ $data->id }}" class="toggle-btn" style="border-top-left-radius: 4px;border-bottom-left-radius: 4px;">Daily</a>
                                <a href="/projects/weekly/{{ $data->id }}" class="toggle-btn active" style="border-top-right-radius: 4px;border-bottom-right-radius: 4px;">Weekly</a>
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
                        <div id="gantt_here" data-check-height="{{ ($data->tasks->count() * 32) + 52 }}" style='width:100% !important; height:{{ ($data->tasks->count() * 32) + 52 }}px;'></div>
                    </div>
                </div>
            </div>
            <div class="content us" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
                <div class="task-list" style="border-right: 0px; padding-right: 0px; padding-top: 0px">
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
            
            // Set start date to 1 year before earliest task date
            let startDate = new Date(earliestTaskDate);
            startDate.setFullYear(startDate.getFullYear() - 1);
            
            // Set end date to 10 years after project end date
            let endDate = new Date(en);
            endDate.setFullYear(endDate.getFullYear() + 10);
            
            console.log('Earliest task date:', earliestTaskDate.toDateString());
            
            const projectDurationDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 32));
            
            console.log('Calendar range:', startDate, 'to', endDate, '(' + Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 32)) + ' days)');
            
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let isWeeklyView = true; // Weekly view

            function renderCalendar() {
                calendarContainer.empty(); // Clear the calendar container
                
                // Align start date to Monday to match DHTMLX
                const alignedStartDate = new Date(startDate);
                const startDayOfWeek = alignedStartDate.getDay();
                const daysToMonday = (startDayOfWeek === 0 ? -6 : 1 - startDayOfWeek);
                alignedStartDate.setDate(alignedStartDate.getDate() + daysToMonday);

                inp = ``;

                // Calculate continuous week number from epoch (matches DHTMLX %W format)
                const currentDate = new Date(alignedStartDate);
                
                // Helper function to calculate ISO week number
                function getISOWeekNumber(date) {
                    const tempDate = new Date(date.getTime());
                    tempDate.setHours(0, 0, 0, 0);
                    // Thursday in current week decides the year
                    tempDate.setDate(tempDate.getDate() + 3 - (tempDate.getDay() + 6) % 7);
                    // January 4 is always in week 1
                    const week1 = new Date(tempDate.getFullYear(), 0, 4);
                    // Adjust to Thursday in week 1 and count number of weeks from date to week1
                    return 1 + Math.round(((tempDate.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
                }
                
                // Track month segments as they appear in timeline (month headers can split across weeks)
                let monthSegments = []; // Array of {month, year, width} segments in order
                let currentMonthKey = null;
                let currentMonthDays = 0;
                
                // Only render weeks where Monday falls before the end date (matches DHTMLX behavior)
                while (currentDate < endDate) {
                    // Get the Monday of this week for the data-date attribute
                    const weekStartDate = new Date(currentDate);
                    const weekStartDay = weekStartDate.getDate().toString().padStart(2, '0');
                    const weekStartMonth = (weekStartDate.getMonth() + 1).toString().padStart(2, '0');
                    const weekStartYear = weekStartDate.getFullYear();
                    
                    // Calculate ISO week number (continuous, never resets)
                    const weekNumber = getISOWeekNumber(currentDate);
                    
                    // For this week, calculate how days are distributed across months
                    let weekMonthDistribution = {}; // {monthKey: dayCount}
                    
                    for (let dayOffset = 0; dayOffset < 7; dayOffset++) {
                        const dayInWeek = new Date(currentDate);
                        dayInWeek.setDate(dayInWeek.getDate() + dayOffset);
                        
                        // Only count days that are before the end date
                        if (dayInWeek >= endDate) break;
                        
                        const dayMonth = dayInWeek.getMonth();
                        const dayYear = dayInWeek.getFullYear();
                        const monthKey = `${dayYear}-${String(dayMonth).padStart(2, '0')}`;
                        
                        weekMonthDistribution[monthKey] = (weekMonthDistribution[monthKey] || 0) + 1;
                    }
                    
                    // Create month segments based on how this week is distributed
                    Object.keys(weekMonthDistribution).forEach(monthKey => {
                        const [year, month] = monthKey.split('-').map(Number);
                        const dayCount = weekMonthDistribution[monthKey];
                        const width = Math.round((dayCount / 7) * 32);
                        
                        // If same month as previous segment, merge widths
                        if (monthSegments.length > 0 && 
                            monthSegments[monthSegments.length - 1].monthKey === monthKey) {
                            monthSegments[monthSegments.length - 1].width += width;
                        } else {
                            // New month segment
                            monthSegments.push({
                                monthKey: monthKey,
                                year: year,
                                month: month,
                                width: width
                            });
                        }
                    });

                    // Create input for this week
                    inn = `<input type="number" min="1" max="56" step="1" class="calendar-day inputss" style="min-width: 32px;" onchange="convertTimeInput(this)" oninput="restrictToInteger(this)" data-date="${weekStartYear}-${weekStartMonth}-${weekStartDay}" data-week="${weekNumber}">`;

                    inp += inn;
                    
                    currentDate.setDate(currentDate.getDate() + 7); // Move to next week
                }
                
                // Build TWO SEPARATE ROWS: month headers row + week cells row
                const monthHeaderRow = $('<div class="month-header-row"></div>');
                const weekCellRow = $('<div class="week-cell-row"></div>');
                
                // Count total weeks for width calculation
                const currentDate2 = new Date(alignedStartDate);
                let totalWeeks = 0;
                while (currentDate2 < endDate) {
                    totalWeeks++;
                    currentDate2.setDate(currentDate2.getDate() + 7);
                }
                const totalExpectedWidth = totalWeeks * 32;
                
                // First row: Month header segments in timeline order with partial widths
                let accumulatedWidth = 0;
                monthSegments.forEach(function(segment, index) {
                    let monthWidth = segment.width;
                    
                    // For the last segment, use remaining width to avoid rounding errors
                    if (index === monthSegments.length - 1) {
                        monthWidth = totalExpectedWidth - accumulatedWidth;
                    }
                    
                    console.log(`[Month Segment: ${monthNames[segment.month]} ${segment.year}] width = ${monthWidth}px`);
                    
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
                
                // Second row: ALL week cells continuously (no breaks) - only before end date
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
                
                // Append both rows to calendar container
                calendarContainer.append(monthHeaderRow);
                calendarContainer.append(weekCellRow);

                // Only append to regular second-input elements, not member-time-calendar-row
                $('.second-input:not(.member-time-calendar-row)').append(inp);
                
                // Set data-user-id for the newly added input fields
                $('.second-input:not(.member-time-calendar-row)').each(function() {
                    const userId = $(this).data('user-id');
                    const taskId = $(this).data('task-id');
                    console.log('[renderCalendar] Setting attributes for second-input:', { userId, taskId });
                    $(this).find('.inputss').attr('data-user-id', userId);
                    $(this).find('.inputss').attr('data-task-id', taskId);
                });

                // Calendar rows are now preloaded with actual data from the server
                
                // Populate member time calendar rows with time entry data
                populateMemberTimeCalendarRows();
            }

            // Function to populate member calendar rows with time entry data - WEEKLY VERSION
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
                    
                    // Align start date to Monday to match DHTMLX
                    const alignedStartDate = new Date(startDate);
                    const startDayOfWeek = alignedStartDate.getDay();
                    const daysToMonday = (startDayOfWeek === 0 ? -6 : 1 - startDayOfWeek);
                    alignedStartDate.setDate(alignedStartDate.getDate() + daysToMonday);
                    
                    // Iterate through weeks (same as main calendar)
                    const currentDate = new Date(alignedStartDate);
                    
                    // Helper function to calculate ISO week number
                    function getISOWeekNumber(date) {
                        const tempDate = new Date(date.getTime());
                        tempDate.setHours(0, 0, 0, 0);
                        tempDate.setDate(tempDate.getDate() + 3 - (tempDate.getDay() + 6) % 7);
                        const week1 = new Date(tempDate.getFullYear(), 0, 4);
                        return 1 + Math.round(((tempDate.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
                    }
                    
                    while (currentDate <= endDate) {
                        // Get the Monday of this week for the data-date attribute
                        const weekStartDay = currentDate.getDate().toString().padStart(2, '0');
                        const weekStartMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');
                        const weekStartYear = currentDate.getFullYear();
                        const dateString = `${weekStartYear}-${weekStartMonth}-${weekStartDay}`;
                        
                        // Calculate ISO week number
                        const weekNumber = getISOWeekNumber(currentDate);
                        
                        // Aggregate hours for this entire week (Mon-Sun)
                        let weekTotalHours = 0;
                        for (let d = 0; d < 7; d++) {
                            const checkDate = new Date(currentDate);
                            checkDate.setDate(currentDate.getDate() + d);
                            const checkDateStr = `${checkDate.getFullYear()}-${(checkDate.getMonth() + 1).toString().padStart(2, '0')}-${checkDate.getDate().toString().padStart(2, '0')}`;
                            weekTotalHours += parseFloat(userTimeEntries[checkDateStr]) || 0;
                        }
                        
                        // Create input field for member time entry (one per week)
                        memberInp += `<input type="number" 
                                             min="1" 
                                             max="56" 
                                             step="1" 
                                             class="inputsss member-time-input" 
                                             style="min-width: 32px;" 
                                             data-user-id="${userId}" 
                                             data-project-id="${projectId}" 
                                             data-date="${dateString}"
                                             data-week="${weekNumber}"
                                             value="${weekTotalHours > 0 ? weekTotalHours : ''}"
                                             disabled
                                             >`;
                        
                        currentDate.setDate(currentDate.getDate() + 7); // Move to next week
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
            
            // Function to scroll to 1 week before today - WEEKLY VERSION
            function scrollToOneWeekBefore() {
                const today = new Date();
                const oneWeekBefore = new Date(today);
                oneWeekBefore.setDate(today.getDate() - 7);
                
                // Align calendar start to Monday
                const alignedStart = new Date(startDate);
                const dayOfWeek = alignedStart.getDay();
                const daysToMonday = (dayOfWeek === 0 ? -6 : 1 - dayOfWeek);
                alignedStart.setDate(alignedStart.getDate() + daysToMonday);
                
                // Calculate weeks from calendar start to target date
                const msPerWeek = 1000 * 60 * 60 * 24 * 7; // milliseconds per week
                const weeksFromStart = Math.floor((oneWeekBefore - alignedStart) / msPerWeek);
                const scrollPosition = weeksFromStart * 32; // 32px per week
                
                console.log('Calendar starts at:', alignedStart.toDateString());
                console.log('Scrolling to 1 week before today:', oneWeekBefore.toDateString());
                console.log('Weeks from calendar start:', weeksFromStart);
                console.log('Scroll position:', scrollPosition + 'px');
                
                // Scroll both calendars to the same position
                $('.scroll-container').scrollLeft(scrollPosition);
                gantt.scrollTo(scrollPosition, null);
                console.log('Both calendars scrolled to position:', scrollPosition);
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
        // Weekly view: allow up to 56 (7 * 8) hours
        if (value > 56) {
            inputElement.value = '56';
        } else if (value < 1 && inputElement.value !== '') {
            inputElement.value = '1';
        }
    }

    // Arrow key navigation for weekly time entry inputs
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
                // Find the input above with the same date (same week)
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
                // Find the input below with the same date (same week)
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

                return;
            }

            if (integerTime < 1 || integerTime > 56) {
                alert('Value is Invalid. Please enter a number between 1 and 56.');
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

            // ---------------- WEEKLY AGGREGATION ----------------
            // Build a map keyed by user_id|task_id|weekStartDate (Monday) summing daily hours
            const weeklyMap = {};

            function getMonday(dateStr){
                const d = new Date(dateStr + 'T00:00:00');
                const day = d.getDay(); // 0 = Sun, 1 = Mon
                const diff = day === 0 ? -6 : 1 - day; // days to Monday
                d.setDate(d.getDate() + diff);
                const y = d.getFullYear();
                const m = String(d.getMonth()+1).padStart(2,'0');
                const da = String(d.getDate()).padStart(2,'0');
                return `${y}-${m}-${da}`;
            }

            data.forEach(item => {
                const { task_id, user_id, date, time } = item;
                console.log('[WeeklyAggregation] Processing:', { task_id, user_id, date, time });
                if(!date || !user_id) return;
                let hours;
                if (typeof time === 'string' && time.includes(':')) {
                    hours = parseInt(time.split(':')[0]);
                } else {
                    hours = parseInt(time);
                }
                if (isNaN(hours) || hours <= 0) return;
                const monday = getMonday(date);
                const key = `${user_id}|${task_id}|${monday}`;
                weeklyMap[key] = (weeklyMap[key] || 0) + hours;
                console.log('[WeeklyAggregation] Added to key:', key, 'Total now:', weeklyMap[key]);
            });

            console.log('[WeeklyAggregation] Map:', weeklyMap);

            // Apply aggregated weekly hours to inputs (capped at 56)
            const inputs = document.querySelectorAll('.inputss');
            console.log('[WeeklyPopulation] Found', inputs.length, 'input fields');
            inputs.forEach(input => {
                const mondayDate = input.getAttribute('data-date'); // already Monday in weekly view
                const userId = input.getAttribute('data-user-id');
                const taskId = input.getAttribute('data-task-id');
                
                if (!userId || !mondayDate) {
                    console.log('[WeeklyPopulation] Skipping input - missing userId or date');
                    return;
                }
                
                const key = `${userId}|${taskId}|${mondayDate}`;
                console.log('[WeeklyPopulation] Looking for key:', key, 'Value:', weeklyMap[key]);
                
                if (weeklyMap[key]) {
                    const total = Math.min(weeklyMap[key], 56); // defensive cap
                    input.value = total;
                    console.log('[WeeklyPopulation] Set input to:', total, 'for', key);
                }
            });
            // ---------------- END WEEKLY AGGREGATION ----------------

            console.log('Weekly inputs populated from aggregated daily entries.');
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
        
        // Reorder task items
        $.each(items, function(i, item) {
            $('.mains .task-list').append(item);
        });
        
        asc = !asc;
        $('#sortProjectIcon').toggleClass('fa-sort-alpha-down fa-sort-alpha-up');
    });

    document.getElementById('app-skeleton').remove();
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
    // Home button - scroll both calendars to 1 week before today - WEEKLY VERSION
    $('#home').on('click', function() {
        const today = new Date();
        const oneWeekBefore = new Date(today);
        oneWeekBefore.setDate(oneWeekBefore.getDate() - 7);
        
        // Calculate scroll position based on weeks from calendar start (using same start as DHTMLX)
        const msPerWeek = 1000 * 60 * 60 * 24 * 7; // milliseconds per week
        const weeksFromStart = Math.floor((oneWeekBefore - calendarStartDate) / msPerWeek);
        const scrollPosition = weeksFromStart * 32; // 32px per week
        
        // Scroll bottom calendar
        $('.scroll-container').animate({
            scrollLeft: scrollPosition
        }, 400);
        
        // Use gantt.showDate() for reliable scrolling to a specific date
        gantt.showDate(oneWeekBefore);
        
        console.log('Both calendars scrolled to 1 week before today:', oneWeekBefore.toDateString());
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
        // DHTMLX Gantt Configuration - WEEKLY VIEW (Months + Week numbers at 32px each)
        const EXACT_WEEK_WIDTH = 32; // 32px per week cell
        
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
        
        // Timeline configuration - WEEKLY VIEW (Month + Week numbers at 24px each)
gantt.config.scales = [
    { unit: "month", step: 1, format: "%F %Y", height: 32 }, // Top row: Month Year
    { 
        unit: "week", 
        step: 1, 
        format: function(date) {
            return "W" + gantt.date.date_to_str("%W")(date);
        }, 
        height: 20
    }
];

gantt.config.scale_height = 52;  // Enough height for two rows
gantt.config.min_column_width = 32; // 32px per week cell
gantt.config.max_column_width = 32;
        
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
        
        // Override task positioning template for WEEKLY VIEW (24px per week cell)
        gantt.templates.task_position = function(start, end, task) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            
            // Calculate weeks from calendar start
            const msFromStart = startDate - calendarStartDate;
            const weeksFromStart = Math.floor(msFromStart / (1000 * 60 * 60 * 32 * 7));
            
            // Calculate task duration in weeks (rounded up)
            const taskDurationMs = endDate - startDate;
            const taskDurationWeeks = Math.ceil(taskDurationMs / (1000 * 60 * 60 * 32 * 7));
            
            const left = weeksFromStart * 32; // 32px per week
            const width = Math.max(taskDurationWeeks * 32, 32); // Min width = 1 week (32px)
            
            return {
                left: left,
                width: width
            };
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
                    task.duration = Math.ceil((task.end_date - task.start_date) / (1000 * 60 * 60 * 32));
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
        
        // Scroll gantt to 1 week before today on load - WEEKLY VIEW (32px per week)
        setTimeout(function() {
            const today = new Date();
            const oneWeekBefore = new Date(today);
            oneWeekBefore.setDate(oneWeekBefore.getDate() - 7);
            const msPerWeek = 1000 * 60 * 60 * 24 * 7; // milliseconds per week
            const weeksFromStart = Math.floor((oneWeekBefore - calendarStartDate) / msPerWeek);
            const scrollPosition = weeksFromStart * 32; // 32px per week
            
            gantt.scrollTo(Math.max(0, scrollPosition), null);
            console.log('Gantt (weekly view) scrolled to 1 week before today:', oneWeekBefore.toDateString());
            
            // Add red vertical line for today's date using CSS - WEEKLY VIEW
            const todayWeeksFromStart = Math.floor((today - calendarStartDate) / msPerWeek);
            const todayPosition = todayWeeksFromStart * 32; // 32px per week
            
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
            
            // Re-add today line after render - WEEKLY VIEW (32px per week)
            setTimeout(function() {
                const today = new Date();
                const msPerWeek = 1000 * 60 * 60 * 24 * 7; // milliseconds per week
                const todayWeeksFromStart = Math.floor((today - calendarStartDate) / msPerWeek);
                const todayPosition = todayWeeksFromStart * 32; // 32px per week
                
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
        
        // Synchronize gantt and bottom calendar scrolling - FIXED VERSION
        let syncInProgress = false;
        let syncTimeout;
        
        // When gantt scrolls, scroll bottom calendar to match
        gantt.attachEvent("onGanttScroll", function(left, top) {
            if (syncInProgress) {
                return true;
            }
            
            clearTimeout(syncTimeout);
            syncInProgress = true;
            
            // Use setTimeout to ensure it runs after current execution
            setTimeout(() => {
                $('.scroll-container').scrollLeft(left);
                
                syncTimeout = setTimeout(() => {
                    syncInProgress = false;
                }, 50);
            }, 0);
            
            // Trigger recalibration after gantt scrollbar drag stops (300ms)
            clearTimeout(ganttScrollStopTimeout);
            ganttScrollStopTimeout = setTimeout(function() {
                console.log('[Gantt Scrollbar] Scrollbar drag stopped, recalibrating...');
                recalibrateScrollPosition();
            }, 300);
            
            return true;
        });
        
        // When bottom calendar scrolls, scroll gantt to match
        $('.scroll-container').on('scroll', function() {
            if (syncInProgress) {
                return;
            }
            
            clearTimeout(syncTimeout);
            syncInProgress = true;
            const scrollLeft = $(this).scrollLeft();
            
            // Use setTimeout to ensure it runs after current execution
            setTimeout(() => {
                gantt.scrollTo(scrollLeft, null);
                
                syncTimeout = setTimeout(() => {
                    syncInProgress = false;
                }, 50);
            }, 0);
            
            // Trigger recalibration after scrollbar drag stops (300ms)
            clearTimeout(bottomScrollStopTimeout);
            bottomScrollStopTimeout = setTimeout(function() {
                console.log('[Bottom Scrollbar] Scrollbar drag stopped, recalibrating...');
                recalibrateScrollPosition();
            }, 300);
        });
        
        // Recalibration timers for when scroll stops
        let bottomScrollStopTimeout;
        let ganttScrollStopTimeout;
        
        // Recalibrate both calendars when scroll stops
        function recalibrateScrollPosition() {
            const bottomScrollPos = $('.scroll-container').scrollLeft();
            const ganttScrollPos = gantt.getScrollState().x;
            
            // If positions differ by more than 1px, sync them
            if (Math.abs(bottomScrollPos - ganttScrollPos) > 1) {
                console.log('Recalibrating scroll positions - Bottom:', bottomScrollPos, 'Gantt:', ganttScrollPos);
                // Use the gantt position as the source of truth
                syncInProgress = true;
                $('.scroll-container').scrollLeft(ganttScrollPos);
                setTimeout(() => {
                    syncInProgress = false;
                }, 100);
            }
        }
        
        // Mouse wheel horizontal scroll for custom calendar (bottom time inputs area)
        $('.scroll-container').on('wheel', function(e) {
            const deltaY = e.originalEvent.deltaY;
            const deltaX = e.originalEvent.deltaX;
            
            // Only convert vertical scroll to horizontal if it's primarily vertical movement
            if (Math.abs(deltaY) > Math.abs(deltaX)) {
                e.preventDefault();
                const scrollAmount = deltaY * 0.4; // Slowed down scroll speed
                const currentScroll = $(this).scrollLeft();
                $(this).scrollLeft(currentScroll + scrollAmount);
                
                // Recalibrate when scroll stops (after 300ms of no scrolling)
                clearTimeout(bottomScrollStopTimeout);
                bottomScrollStopTimeout = setTimeout(recalibrateScrollPosition, 300);
            }
        });
        
        // Mouse wheel horizontal scroll for gantt chart
        const ganttContainer = document.getElementById('gantt_here');
        if (ganttContainer) {
            ganttContainer.addEventListener('wheel', function(e) {
                const deltaY = e.deltaY;
                const deltaX = e.deltaX;
                
                // Only convert vertical scroll to horizontal if it's primarily vertical movement
                if (Math.abs(deltaY) > Math.abs(deltaX)) {
                    e.preventDefault();
                    const scrollAmount = deltaY * 0.4; // Slowed down scroll speed (match custom calendar)
                    const currentScroll = gantt.getScrollState().x;
                    gantt.scrollTo(currentScroll + scrollAmount, null);
                    
                    // Recalibrate when scroll stops (after 300ms of no scrolling)
                    clearTimeout(ganttScrollStopTimeout);
                    ganttScrollStopTimeout = setTimeout(recalibrateScrollPosition, 300);
                }
            }, { passive: false });
            
            // Use MutationObserver to detect when gantt re-renders the scale
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
                success: function(response) {
                    console.log('✓ Task dates saved to server');
                    $(`.start-${id}`).html(formatDateForDisplay(gantt.date.date_to_str("%Y-%m-%d")(task.start_date)));
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
