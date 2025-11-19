<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $data->name }} - Projects</title>

    <!-- External Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Base Styles */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; }

        /* Calendar Styles */
        .calendar-container, .gantt-bar-container { display: flex; white-space: nowrap; position: relative; }
        .month-container { display: inline-block; }
        .month-header {
            display: block; width: 100%; padding: 5px 0; font-size: 14px;
            background-color: #000 !important; color: white; text-align: center;
            border-right: 1px solid #fff; border-bottom: 1px solid #e5e7eb;
        }
        .calendar-day {
            display: inline-block; width: 24px !important; height: 20px !important;
            text-align: center; border: 1px solid #ccc; font-size: 10px;
            padding-top: 1px; box-sizing: border-box; border-top: unset;
        }
        .calendar-day.holiday { color: #d32f2f; background-color: #f7f7f7 !important; }

        /* Gantt Bar Styles */
        .draggable {
            cursor: move; background-color: #4A5568 !important;
            border-radius: 5px !important; border: 1px solid #fff !important;
            position: absolute; padding: 1px 0;
        }
        .draggable span { font-size: 12px; display: block; color: white; }
        .draggable.alert-danger { background-color: #ef4444 !important; }

        /* Resizable Handles */
        .ui-resizable-handle {
            background: #fff; border: 1px solid #fff; z-index: 90;
            display: flex; align-items: center; justify-content: center;
            position: absolute; opacity: 1 !important;
        }
        .ui-resizable-e { right: 3px; width: 2px; height: 67%; top: 4px; cursor: e-resize; }
        .ui-resizable-w { left: 3px; width: 2px; height: 67%; top: 4px; cursor: w-resize; }

        /* Scroll Container */
        .scroll-container {
            overflow-x: scroll; width: 100%; cursor: grab;
            border-top-right-radius: 4px; border-bottom-right-radius: 4px;
            scrollbar-width: none; -ms-overflow-style: none;
        }
        .scroll-container::-webkit-scrollbar { display: none; }
        .scroll-container:active { cursor: grabbing; }

        /* Task List Styles */
        .task-list {
            width: 600px; background-color: #f7fafc;
            border-right: 1px solid #ccc; border-radius: 4px;
        }
        .task-header {
            display: flex; justify-content: space-between; align-items: center;
            background-color: #000; color: white; padding: 10px; height: 52px;
            font-weight: bold; margin-bottom: 0;
        }
        .task-item {
            padding: 10px; display: flex; align-items: center;
            border-bottom: 1px solid #eee; background: #fff; height: 30px;
        }
        .task-item:last-child { border-bottom-left-radius: 4px; }

        /* Input Styles */
        .second-input { display: flex; }
        .second-input .calendar-day {
            height: 30px !important; font-size: 10px; padding: 0 !important;
            width: 24px !important; border-right: 1px solid #eee;
            border-top: 1px solid #eee; border-bottom: unset; border-left: unset;
        }

        /* Hide number input spinners */
        .inputss::-webkit-outer-spin-button,
        .inputss::-webkit-inner-spin-button,
        .inputsss::-webkit-outer-spin-button,
        .inputsss::-webkit-inner-spin-button {
            -webkit-appearance: none; margin: 0;
        }
        .inputss[type=number], .inputsss[type=number] {
            -moz-appearance: textfield; appearance: textfield;
        }

        /* Today Line */
        .today-line {
            position: absolute; top: -25px; bottom: 0; width: 2px;
            background-color: #D9534F; z-index: 100;
        }

        /* Holiday Highlight */
        .holiday-highlight {
            position: absolute; top: 0; bottom: 0;
            background-color: #f7f7f7; z-index: -1;
        }

        /* Modal Styles */
        .modal {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000; justify-content: center; align-items: center;
        }
        .modal-content {
            background-color: white; padding: 20px; border-radius: 8px;
            width: 400px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: auto; margin-top: 25rem;
        }

        /* Drag Handle */
        .drag-handle {
            color: #9ca3af; margin-right: 8px; cursor: move; font-size: 12px;
        }
        .drag-handle:hover { color: #4b5563; }

        /* Sortable Placeholder */
        .ui-sortable-helper {
            background-color: #f3f4f6; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .ui-sortable-placeholder {
            background-color: #e5e7eb; visibility: visible !important; height: 40px;
        }

        /* Expandable Time Entries */
        .expand-arrow {
            cursor: pointer; font-size: 12px; margin-left: 8px;
            user-select: none; padding: 4px; display: inline-block;
            min-width: 16px; text-align: center;
        }
        .expand-arrow:hover { background-color: rgba(0, 0, 0, 0.1); border-radius: 3px; }
        .member-time-entries { background-color: #f9fafb; display: none; }
        .time-entry-row { background-color: #f8fafc; }

        /* Progress Ring */
        .progress-container {
            position: relative; width: 100%; max-width: 100px;
            aspect-ratio: 1/1; margin: 20px 0 0;
        }
        .progress-ring { transform: rotate(-90deg); width: 100%; height: 100%; }
        .progress-text { font-size: 2.2vw; font-weight: bold; color: #333; line-height: 1; }
        .custom-label { font-size: 0.4vw; color: #555; }

        /* Undo/Redo Buttons */
        #undoBtn, #redoBtn { transition: all 0.2s ease; }
        #undoBtn:disabled, #redoBtn:disabled { opacity: 0.5; cursor: not-allowed; }
        #undoBtn:disabled i, #redoBtn:disabled i { border-color: #f3f4f6 !important; color: #9ca3af !important; }
        #undoBtn:not(:disabled):hover i, #redoBtn:not(:disabled):hover i {
            border-color: #000 !important; background-color: #f9fafb;
        }

        /* Toggle Buttons */
        .toggle-btn {
            background: #000; color: #fff; border: 1px solid #000;
            padding: 7px 19px; font-size: 13px; font-weight: 500;
            transition: background 0.2s, color 0.2s; height: 33px; width: 170px;
            text-align: center; display: inline-flex; align-items: center;
            justify-content: center;
        }
        .toggle-btn:not(.active) { background: transparent; color: #000; }
        .toggle-btn.active { background: #000; color: #fff; }

        /* Utility Classes */
        .task-circle {
            width: 15px; height: 15px; border-radius: 50%;
            background-color: #000; margin-right: 10px; display: inline-block;
        }
        .archied { display: none; }
        .content { margin-top: 0 !important; }
        .mains { border: 1px solid #D1D5DB; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-50">
    @include('front.nav')
    
    <div class="mx-auto shadow border rounded-lg overflow-hidden">
        <div class="p-4 rounded-lg" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
            
            <!-- Header -->
            <div class="content" style="display: block; margin-bottom: 40px;">
                <div style="float: left; margin-top: 6px;">
                    <h5 style="font-size: 20px; font-weight: 600; margin-left: 7px;">{{ $data->name }}</h5>
                </div>
                <div class="flex items-center" style="float: right;">
                    <!-- Undo/Redo -->
                    <button class="text-gray-600 hover:text-black" id="undoBtn" style="margin-right: 8px;" title="Undo (Ctrl+Z)" disabled>
                        <i class="fas fa-undo" style="border: 1px solid #eee; padding: 10px 12px; border-radius: 4px; font-size: 14px;"></i>
                    </button>
                    <button class="text-gray-600 hover:text-black" id="redoBtn" style="margin-right: 8px;" title="Redo (Ctrl+Y)" disabled>
                        <i class="fas fa-redo" style="border: 1px solid #eee; padding: 10px 12px; border-radius: 4px; font-size: 14px;"></i>
                    </button>
                    
                    <!-- Home Button -->
                    <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;" title="Go to Today">
                        <img src="{{ asset('house.png') }}" style="border: 1px solid #eee; padding: 10px 12px; border-radius: 4px;">
                    </button>
                    
                    <!-- View Toggle -->
                    <div style="border: 1px solid #eee; border-radius: 4px; margin-right: 8px; height: 34px; width: 170px; display: flex; justify-content: center;">
                        <a href="/projects/{{ $data->id }}" class="toggle-btn active" style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">Daily</a>
                        <a href="/projects/weekly/{{ $data->id }}" class="toggle-btn" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">Weekly</a>
                    </div>
                    
                    <!-- Add Member Button -->
                    <a class="bg-black text-white px-4 py-2 rounded" id="addMemberButton" style="font-size: 13px; padding: 0.4rem 1rem; cursor: pointer; margin-right: 8px;">+ Add Member</a>
                </div>
            </div>

            <!-- Tasks Section -->
            <div class="content mains">
                <div class="task-list" style="padding-right: 0; margin-top: 0;">
                    <div class="task-header" style="margin-bottom: 0; border-top-left-radius: 4px; padding-right: 0;">
                        <span style="width: 50%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProject">
                            Title
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" alt="Sort">
                        </span>
                        <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding: 17px 0; text-align: center;">Date</span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding: 17px 0; text-align: center;">Budget</span>
                        <span class="text-center" style="font-size: 12px; width: 10%; padding: 17px 0; text-align: center; cursor: pointer;" id="addTaskButton">
                            <i class="fas fa-plus"></i>
                        </span>
                    </div>
                    @foreach ($data->tasks as $key => $item)
                        @if ($item->start_date != null)
                            <div class="task-item" data-task="task{{ $key + 1 }}" data-task-id="{{ $item->id }}">
                                <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding: 6px 0;">
                                    <div class="task-circle"></div>
                                    {{ $item->name }}
                                </span>
                                <span class="start-{{ $item->id }}" style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding: 6px 0; text-align: center;">
                                    {{ formatDate($item->start_date) }}
                                </span>
                                <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding: 6px 0; text-align: center;">
                                    <input type="text" name="budget_total" data-task-id="{{ $item->id }}" value="{{ formatCurrency(round($item->budget_total)) }}" class="budget_total" style="width: 100%; font-size: 12px; text-align: center; border: none; background: transparent;">
                                </span>
                                <span style="display: block; width: 10%; padding: 6px 0; text-align: center;">
                                    <button class="open-edit-task-modal bg-blue-500 text-white px-2 py-1 rounded" style="font-size: 13px; background: #fff !important; color: #4b5563 !important;"
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
                        <div class="calendar-container"></div>
                        @php
                            $height = 0;
                            foreach ($data->tasks as $key => $value) {
                                if ($value->start_date != null) {
                                    $height += 1;
                                }
                            }
                        @endphp
                        <div class="gantt-bar-container" style="height: {{ (24*$height) + 5 }}px">
                            @foreach ($data->tasks as $key => $item)
                                @if ($item->start_date != null)
                                    <div class="draggable" 
                                         data-task-id="{{ $item->id }}" 
                                         data-task="task{{$key + 1}}" 
                                         data-start-date="{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}" 
                                         data-end-date="{{ \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') }}">
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

            <!-- Team Members Section -->
            <div class="content us" style="margin-top: 10px !important; border: 1px solid #D1D5DB; border-radius: 4px;">
                <div class="task-list" style="border-right: 0; padding-right: 0; padding-top: 0;">
                    <div class="task-header" style="margin-bottom: 0; padding: 10px; padding-right: 0;">
                        <span style="width: 50%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding: 17px 0;" id="sortProjectuser">
                            Name
                            <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" alt="Sort">
                        </span>
                        <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding: 17px 0; text-align: center;">Cost</span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding: 17px 0; text-align: center;">Hours</span>
                        <span style="font-size: 12px; width: 10%; padding: 17px 0; text-align: center; border-right: 1px solid #eee;">
                            <i class="fas fa-eye show-user" data-type="show" style="cursor: pointer;"></i>
                        </span>
                    </div>
                    <div class="not-archived names" id="team-members-list">
                        @foreach ($data->members as $item)
                            @if ($item->archieve == 0)
                                <div class="task-item team-member-row" data-member-id="{{ $item->id }}" data-user-id="{{ $item->user_id }}" style="position: unset;">
                                    <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding: 6px 0; align-items: center;">
                                        <img class="drag-handle" src="{{ asset('dots.svg') }}" style="margin-right: 5px; width: 20px; height: 20px;">
                                        <img src="{{ $item->user->profile_image_url ? asset('storage/'.$item->user->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                                        {{ $item->user->name }}
                                        <div class="expand-arrow" data-target="member-time-entries" data-id="{{ $item->user_id }}" data-project-id="{{ $data->id }}">▶</div>
                                    </span>
                                    @php
                                        $es = DB::table('estimated_time_entries')->where('project_id', $data->id)->where('user_id', $item->user_id)->sum('hours');
                                    @endphp
                                    <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding: 6px 0; text-align: center;" class="user-cost-{{ $item->user_id }}">
                                        {{ formatCurrency($item->user->hourly_rate * $es) }}
                                    </span>
                                    <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding: 6px 0; text-align: center;" class="user-hour-{{ $item->user_id }}">
                                        {{ number_format($es) }}
                                    </span>
                                    <span style="display: block; width: 10%; padding: 6px 0; text-align: center;">
                                        <i class="fas fa-eye hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px; cursor: pointer;"></i>
                                    </span>
                                </div>
                                
                                <!-- Expandable Time Entries -->
                                <div class="member-time-entries" data-user-id="{{ $item->user_id }}" data-project-id="{{ $data->id }}">
                                    <div class="task-item time-entry-row">
                                        <span style="padding-left: 20px; width: 50%; font-size: 11px; display: inline-flex; border-right: 1px solid #eee; padding: 4px 0; align-items: center;">
                                            <span style="width: 6px; height: 6px; background-color: #6b7280; border-radius: 50%; margin-right: 8px;"></span>
                                        </span>
                                        @php
                                            $timeEntries = DB::table('time_entries')->where('user_id', $item->user_id)->where('project_id', $data->id)->get();
                                            $totalHours = $timeEntries->sum('hours');
                                            $totalCost = $totalHours * $item->user->hourly_rate;
                                        @endphp
                                        <span style="width: 25%; font-size: 11px; border-right: 1px solid #eee; padding: 4px 0; text-align: center;" class="member-time-cost-{{ $item->user_id }}">
                                            {{ formatCurrency($totalCost) }}
                                        </span>
                                        <span style="width: 15%; font-size: 11px; border-right: 1px solid #eee; padding: 4px 0; text-align: center;" class="member-time-hours-{{ $item->user_id }}">
                                            {{ number_format($totalHours, 0) }}
                                        </span>
                                        <span style="width: 10%; font-size: 11px; padding: 4px 0; text-align: center;"></span>
                                    </div>
                                    <div class="member-time-calendar-row" data-user-id="{{ $item->user_id }}" data-project-id="{{ $data->id }}"></div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="archied">
                        @foreach ($data->members as $item)
                            @if ($item->archieve == 1)
                                <div class="task-item" data-user-id="{{ $item->user_id }}">
                                    <span style="width: 50%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding: 6px 0;">
                                        <img src="{{ $item->user->profile_image_url ? asset('storage/'.$item->user->profile_image_url) : 'https://randomuser.me/api/portraits/men/4.jpg' }}" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                                        {{ $item->user->name }}
                                    </span>
                                    @php
                                        $es = DB::table('estimated_time_entries')->where('project_id', $data->id)->where('user_id', $item->user_id)->sum('hours');
                                    @endphp
                                    <span style="width: 25%; font-size: 12px; border-right: 1px solid #eee; padding: 6px 0; text-align: center;" class="user-cost-{{ $item->user_id }}">
                                        {{ formatCurrency($item->user->hourly_rate * $es) }}
                                    </span>
                                    <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding: 6px 0; text-align: center;" class="user-hour-{{ $item->user_id }}">
                                        {{ number_format($es) }}
                                    </span>
                                    <span style="display: block; width: 10%; padding: 6px 0; text-align: center;">
                                        <i class="fas fa-eye-slash hide-user" data-id="{{ $item->id }}" style="color: #4B5563; font-size: 13px; cursor: pointer;"></i>
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="scroll-container sss">
                    <div class="relative mt-4">
                        <div class="calendar-container second-calender"></div>
                        <div class="not-archived" style="margin-top: -4px;" id="team-time-inputs">
                            @foreach ($data->members as $item)
                                @if ($item->archieve == 0)
                                    <div class="second-input time-input-row" data-user-id="{{ $item->user_id }}" data-member-id="{{ $item->id }}"></div>
                                    <div class="second-input member-time-calendar-row member-time-{{ $item->user_id }}" data-user-id="{{ $item->user_id }}" data-project-id="{{ $data->id }}" style="display: none;"></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="archied" style="margin-top: -4px;">
                            @foreach ($data->members as $item)
                                @if ($item->archieve == 1)
                                    <div class="second-input" data-user-id="{{ $item->user_id }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="fetch">
            @include('front.estimate', ['data' => $data])
        </div>
    </div>

    <!-- Hidden Inputs -->
    <input type="hidden" id="st_date" value="{{ $data->start_date }}">
    <input type="hidden" id="en_date" value="{{ $data->end_date }}">
    <input type="hidden" id="project_id" value="{{ $data->id }}">
    <input type="hidden" id="task_count" value="{{ count($data->tasks) }}">
    <input type="hidden" id="date_format" value="{{ globalSettings('date_format') }}">
    <input type="hidden" id="currency_symbol" value="{{ str_replace(number_format(0, 0), '', formatCurrency(0)) }}">

    <!-- Modular JavaScript -->
    <script src="{{ asset('js/gantt-v2.js') }}"></script>
    
    <script>
        // Initialize Gantt Chart
        $(document).ready(function() {
            // Server-side time entry data
            @php
                $allTimeEntries = collect();
                foreach ($data->members as $member) {
                    $memberTimeEntries = DB::table('time_entries')
                        ->where('user_id', $member->user_id)
                        ->where('project_id', $data->id)
                        ->select('user_id', 'entry_date', 'hours')
                        ->get();
                    $allTimeEntries = $allTimeEntries->concat($memberTimeEntries);
                }
                $groupedTimeEntries = $allTimeEntries->groupBy('user_id')->map(function($userEntries) {
                    return $userEntries->groupBy('entry_date')->map(function($dateEntries) {
                        return $dateEntries->sum('hours');
                    });
                });
            @endphp
            
            window.memberTimeEntries = @json($groupedTimeEntries);
            window.projectData = {
                id: {{ $data->id }},
                startDate: '{{ $data->start_date }}',
                endDate: '{{ $data->end_date }}'
            };
            
            // Initialize the Gantt system
            if (typeof GanttChart !== 'undefined') {
                window.ganttChart = new GanttChart();
                ganttChart.init();
            }
        });
    </script>
</body>
</html>
