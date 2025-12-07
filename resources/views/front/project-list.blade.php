<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projects - Project Management</title>
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
        /* DHTMLX Gantt Custom Styling to match calendar */
        .gantt_container {
            font-family: Arial, sans-serif;
        }
        
        .gantt_scale_cell {
            border-right: 1px solid #ccc;
        }
        
        .gantt_task_scale .gantt_scale_cell {
            border-bottom: 1px solid #ccc;
        }
        
        /* Month header styling */
        .gantt_scale_line:first-child .gantt_scale_cell {
            background-color: #000 !important;
            color: white !important;
            font-size: 14px;
            font-weight: normal;
            border-right: 1px solid #fff;
        }
        
        /* Day number styling */
        .gantt_scale_line:last-child .gantt_scale_cell {
            background-color: #ffffff;
            font-size: 10px;
            border-left: unset;
        }
        
        /* Weekend styling */
        .gantt-weekend-cell {
            background-color: #EBEBEB !important;
            color: #D9534F !important;
        }
        
        /* Task row styling */
        .gantt_task_row {
            border-bottom: 1px solid #ebebeb;
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
        
        /* Grid column lines */
        .gantt_task_cell {
            border-right: 1px solid #ebebeb;
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
            cursor: unset;
            background-color: #4A5568 !important;
            border-radius: 5px;
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
        }

        .second-input .calendar-day{
            height: 30px !important;
            font-size: 12px;
            padding: 0px;
        }

        .second-input{
            display: flex;
        }

        .draggable:first-of-type{
            top: 0 !important;
        }
        .calendar-container, .gantt-bar-container {
            display: flex;
            white-space: nowrap;
            position: relative;
        }
        .month-container {
            display: inline-block;
            vertical-align: top;
        }

        .month-container .calendar-day{
            border-left: unset !important;
        }

        .month-header {
            display: block;
            width: 100%;
            margin: 0;
            padding: 5px 0;
            font-size: 14px;
            background-color: #000;
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
            display: flex;
        }
        .task-list {
            width: 600px;
            background-color: #f7fafc;
            border-right: 1px solid #ccc;
            padding: 10px;
            padding-bottom: 0px;
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
            /* border-radius: 4px; */
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 0px;
            border-bottom: 1px solid #eee;
            margin-left: 0px;
            background: #fff;
            height: 30px;
        }

        .task-list .task-item:last-child{
            border-bottom: 0px !important;
        }

        .task-item img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .holiday{
            background: #EBEBEB;
            color: #D9534F;
        }

        .today-line {
            position: absolute;
            top: -20px;
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
            background-color: #EBEBEB; /* Light red background for holidays */
            z-index: -1; /* Ensure it doesn't overlap the Gantt bars */
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
<body class="">
    @include('front.nav')

    <div class="mx-auto p-4 overflow-hidden rounded-lg shadow border" style="border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
        {{-- <a class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800" href="/projects/weekly/{{ $data->id }}" style="float: right">Weekly</a> --}}
        <div class="content" style="display: block; margin-bottom: 40px;">
            <div style="float: left; margin-top: 6px;">
                <h5 style="font-size: 20px; font-weight: 600;">Projects</h5>
            </div>
            <div class="flex items-center " style="float: right;">
                        <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;">
                            <img src="{{ asset('house.png') }}" style="border: 1px solid #000;padding: 10px 12px;border-radius: 4px;border-color: #eee; ">
                        </button>
                        <div style="border: 1px solid #eee; border-radius: 4px;  margin-right: 8px; height: 34px;width: 170px; display:flex;justify-content: center;">
                            <a href="/projects" class="toggle-btn active">Daily</a>
                            <a href="/projects/weekly" class="toggle-btn">Weekly</a>
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

                        <a href="/projects/create" class="bg-black text-white px-4 py-2 rounded" style="font-size: 13px; padding:0.4rem 1rem;">+  Add Project</a>
            </div>
        </div>
        <div class="content" style="border: 1px solid #D1D5DB; border-radius: 4px;">
            <div class="task-list" style="padding: 0px; margin-top: 0px;">
                <div class="task-header" style="margin-bottom: 0px; border-top-left-radius: 4px;">
                    <span style="width: 40%; font-size: 12px; cursor:pointer; display: inherit; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProject">
                        Project
                        <img style="margin-left: 5px;" src="{{ asset('sort.svg') }}" id="sortProjectIcon" alt="">
                    </span>
                    <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Total</span>
                    <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">Burn</span>
                    <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px; text-align: center;">% Used</span>
                    <span style="width: 15%; font-size: 12px; padding-top: 17px; padding-bottom: 17px; text-align: center;">Status</span>
                    {{-- <span class="text-center font-size: 12px; add-task" style="width: 20%;" id="addTaskButton"><i class="fas fa-plus"></i></span> --}}
                </div>
                @foreach ($data as $key => $item)
                    <div class="task-item" data-task="task{{ $key + 1 }}" style="margin-bottom: 0px; border-bottom: 1px solid #eee; margin-left: 0px; background: #fff;">
                        {{-- <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User 1"> --}}
                        <span style="width: 40%; font-size: 12px; display: inline-flex; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px;"> <img src="{{ asset('dots.svg') }}" style="margin-right: 5px;"> <a href="/projects/{{ $item->id }}">{{ $item->name }}</a>  </span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                            @php
                            // $time = DB::table('time_entries')->where('project_id',$data->id)->get();

                            $spent = 0;

                            foreach ($item->estimatedtimeEntries as  $value) {
                                # code...
                                $rate = $value->user->hourly_rate;


                                $spent += $rate*$value->hours;
                            }

                        @endphp
                        {{ formatCurrency($spent) }}
                        </span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                            @php
                            // $time = DB::table('time_entries')->where('project_id',$data->id)->get();

                            $spent = 0;

                            foreach ($item->timeEntries as  $value) {
                                # code...
                                $rate = $value->user->hourly_rate;


                                $spent += $rate*$value->hours;
                            }

                        @endphp
                        {{ formatCurrency($spent) }}
                        </span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                            @php
                            // $time = DB::table('time_entries')->where('project_id',$data->id)->get();
                            $budget = 0;

                            foreach ($item->estimatedtimeEntries as  $value) {
                                # code...
                                $rate = $value->user->hourly_rate;


                                $budget += $rate*$value->hours;
                            }

                            $spent = 0;

                            foreach ($item->timeEntries as  $value) {
                                # code...
                                $rate = $value->user->hourly_rate;


                                $spent += $rate*$value->hours;
                            }

                            $spent = $spent < 1 ? 1 : $spent;

                            $budget = $budget < 1 ? 1 : $budget;

                            $pre = ($spent/$budget)*100;

                        @endphp
                        {{ round($pre) }}%
                        </span>
                        <span style="width: 15%; font-size: 12px; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                            @php
                                $estimated_hours = $item->estimatedtimeEntries->sum('hours') < 1 ? 1 : $item->estimatedtimeEntries->sum('hours');

                                $spent_hours = $item->timeEntries->sum('hours') < 1 ? 1 : $item->timeEntries->sum('hours');

                                $p = ( $spent_hours / $estimated_hours )*100;
                            @endphp
                                {{ round($p) }}%
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="scroll-container" style="border-top-right-radius: 4px;">
                <div id="gantt_here" data-check-height="{{ (count($data) * 32) + 52 }}" style="width: 100% !important; height: {{ (count($data) * 32) + 52 + 15 }}px;"></div>
            </div>
        </div>
    </div>

    <input type="hidden" id="st_date" value=2025-05-01>
    <input type="hidden" id="en_date" value={{ \Carbon\Carbon::parse('2025-05-01')->addDays(1000)->format('Y-m-d') }}>
    <input type="hidden" id="task_count" value={{ count($data) }}>

    <script>
        $(function () {
            const EXACT_DAY_WIDTH = 24;
            const calendarStartDate = new Date("{{ \Carbon\Carbon::parse('2025-05-01')->format('Y-m-d') }}");
            const calendarEndDate = new Date("{{ \Carbon\Carbon::parse('2025-05-01')->addDays(1000)->format('Y-m-d') }}");
            
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
            
            // Timeline configuration - FORCE 24px per day to match custom calendar
            gantt.config.scales = [
                { unit: "month", step: 1, format: "%F", height: 32 },
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
                        return "";
                    }
                }
            ];

            gantt.config.scale_height = 52;
            gantt.config.min_column_width = 24;
            gantt.config.max_column_width = 24;
            
            // Hide the grid completely
            gantt.config.grid_width = 0;
            
            // Task bar sizing to match custom calendar
            gantt.config.bar_height = 20;
            gantt.config.row_height = 29.5;
            
            // Read-only mode
            gantt.config.readonly = true;
            gantt.config.drag_move = false;
            gantt.config.drag_resize = false;
            gantt.config.drag_progress = false;
            gantt.config.drag_links = false;
            gantt.config.show_links = false;
            gantt.config.details_on_dblclick = false;
            
            // Set start date to match calendar
            gantt.config.start_date = calendarStartDate;
            gantt.config.end_date = calendarEndDate;
            
            // Prepare data for DHTMLX Gantt
            var tasks = {
                data: [
                    @foreach ($data as $key => $item)
                    {
                        id: {{ $item->id }},
                        text: "{{ $item->name }}",
                        start_date: "{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}",
                        end_date: "{{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') : \Carbon\Carbon::now()->addDays(30)->format('Y-m-d') }}",
                        progress: 0,
                        open: true
                    }{{ $loop->last ? '' : ',' }}
                    @endforeach
                ],
                links: []
            };
            
            // Initialize gantt
            gantt.init("gantt_here");
            gantt.parse(tasks);
            
            // Scroll to today's position
            setTimeout(function() {
                var today = new Date();
                gantt.showDate(today);
            }, 100);
            
            // Daily/Weekly view toggle
            let isWeeklyView = false;
            $('#toggleView').on('click', function () {
                isWeeklyView = !isWeeklyView;
                
                if (isWeeklyView) {
                    // Weekly view
                    gantt.config.scale_unit = "week";
                    gantt.config.date_scale = "Week #%W";
                    gantt.config.subscales = [
                        {unit: "month", step: 1, date: "%F %Y"}
                    ];
                } else {
                    // Daily view
                    gantt.config.scale_unit = "day";
                    gantt.config.date_scale = "%d";
                    gantt.config.subscales = [
                        {unit: "month", step: 1, date: "%F %Y"}
                    ];
                }
                
                gantt.render();
            });
            
            // Home button - scroll to today
            $('#home').on('click', function() {
                var today = new Date();
                gantt.showDate(today);
            });
            
            // Mouse wheel scroll handler - convert vertical scroll to horizontal
            const ganttContainer = document.getElementById('gantt_here');
            if (ganttContainer) {
                ganttContainer.addEventListener('wheel', function(e) {
                    const deltaY = e.deltaY;
                    const deltaX = e.deltaX;
                    
                    // If vertical scrolling is dominant, convert to horizontal
                    if (Math.abs(deltaY) > Math.abs(deltaX)) {
                        e.preventDefault();
                        const scrollAmount = deltaY * 0.8; // 0.8x scroll speed
                        const currentScroll = gantt.getScrollState().x;
                        const newScroll = currentScroll + scrollAmount;
                        gantt.scrollTo(newScroll, null);
                    }
                }, { passive: false });
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
$(document).ready(function() {
    let asc = '{{ request("sort", "asc") }}' === 'asc';
    $('#sortProject').on('click', function() {
        // Toggle sort order
        let newSort = asc ? 'desc' : 'asc';
        let url = new URL(window.location.href);
        url.searchParams.set('sort', newSort);
        window.location.href = url.toString();
    });
});
</script>


</body>
</html>
