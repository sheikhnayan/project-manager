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
            width: 32px !important;
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
            background-color: #D9534F;
            border-radius: 10px;
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
                <h5 style="font-size: 20px; font-weight: 600; margin-left: 7px;">Projects</h5>
            </div>
            <div class="flex items-center " style="float: right;">
                        <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px;">
                            <img src="{{ asset('house.png') }}" style="border: 1px solid #000;padding: 10px 12px;border-radius: 4px;border-color: #eee; ">
                        </button>
                        <div style="border: 1px solid #eee; border-radius: 4px;  margin-right: 8px; height: 34px;width: 170px; display:flex;justify-content: center;">
                            <a href="/projects" class="toggle-btn">Daily</a>
                            <a href="/projects/weekly" class="toggle-btn active">Weekly</a>
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
                    <span style="width: 40%; font-size: 12px; cursor:pointer; display: inherit; padding-left: 10px; border-right: 1px solid #eee; padding-top: 17px; padding-bottom: 17px;" id="sortProject">
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
                        {{ round($spent) }}
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
                        {{ round($spent) }}
                        </span>
                        <span style="width: 15%; font-size: 12px; border-right: 1px solid #eee; padding-top: 6px; padding-bottom: 6px; text-align: center;">
                            @php
                            // $time = DB::table('time_entries')->where('project_id',$data->id)->get();
                            $budget = $item->budget_total < 1 ? 1 : $item->budget_total;
                            $spent = 0;

                            foreach ($item->timeEntries as  $value) {
                                # code...
                                $rate = $value->user->hourly_rate;


                                $spent += $rate*$value->hours;
                            }

                            $spent = $spent < 1 ? 1 : $spent;

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
                <div class="relative">
                    <div class="calendar-container">
                        <!-- JavaScript will populate the months and dates here -->
                    </div>
                    @php

                        $height = 0;

                        foreach ($data as $key => $value) {
                                $height +=1;
                        }

                    @endphp
                    <div class="gantt-bar-container" style="height: {{ (30*$height)}}px; margin-top: -5px;">
                        @foreach ($data as $key => $item)
                            @foreach ($item->tasks as $k => $it)
                                <div class="draggable bg-blue-600 text-white text-center" data-project-id="{{ $item->id }}" data-task-id="{{ $it->id }}" data-task="task{{$key + 1}}" style="left: calc(3.225% * 5); position: absolute; top: {{ 32*$key }}px; padding: 0px; padding-top: 1px;" data-start-date="{{ \Carbon\Carbon::parse($it->start_date)->format('Y-m-d') }}" data-end-date="{{ \Carbon\Carbon::parse($it->end_date)->format('Y-m-d') }}">
                                    <span>T0{{ $k+1 }}</span>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="st_date" value=2025-05-01>
    <input type="hidden" id="en_date" value={{ \Carbon\Carbon::parse('2025-05-01')->addDays(1000)->format('Y-m-d') }}>
    <input type="hidden" id="task_count" value={{ count($data) }}>

    <script>
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
                const projectStartDate = new Date($('#st_date').val());
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


                for (let i = 0; i < totalMonthsToShow; i++) {
                    const monthStartDate = new Date(currentYear, currentMonth, 1);

                    // Adjust to the first Monday of the month
                    const dayOfWeek = monthStartDate.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                    const daysToMonday = (dayOfWeek === 0 ? 1 : 8 - dayOfWeek); // Calculate days to the first Monday
                    monthStartDate.setDate(monthStartDate.getDate() + daysToMonday);
                    const monthEndDate = new Date(currentYear, currentMonth + 1, 0);

                    monthContainer.append(`<div class="month-header">${monthNames[currentMonth]} ${currentYear}</div>`);

                    for (let d = new Date(monthStartDate); d <= monthEndDate; d.setDate(d.getDate() + 7)) {
                        const day = d.getDate().toString().padStart(2, '0'); // Pad day with leading zero
                        const month = (d.getMonth() + 1).toString().padStart(2, '0'); // Pad month with leading zero
                        const year = d.getFullYear();
                        const dateString = `${year}-${month}-${day}`;
                        // Create a week block for each week starting from the first Monday
                        const weekBlock = $(`<div class="calendar-day week-block" data-week-start="${dateString}" data-week="${weekNumber}" data-month="${currentMonth}">W${weekNumber}</div>`);
                        monthContainer.append(weekBlock);
                        weekNumber++;


                        inp += `<input type="text" class="calendar-day inputss" onchange="convertTimeInput(this)" data-date="${dateString}">`;
                    }

                    // Only append if there are week blocks (more than just the header)
                    if (monthContainer.children('.week-block').length > 0) {
                        calendarContainer.append(monthContainer);
                    }

                    // Move to the next month
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }

                    // Reset the month container for the next month
                    monthContainer = $('<div class="month-container"></div>');
                }

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
    const weekWidth = $(".week-block").outerWidth();
    const ganttStartDate = new Date($('#st_date').val());

    // Set the startDate to the 1st day of the month
    ganttStartDate.setDate(1);

    // Adjust to the first Monday of the month
    const dayOfWeek = ganttStartDate.getDay();
    const daysToMonday = (dayOfWeek === 0 ? 1 : 8 - dayOfWeek);
    ganttStartDate.setDate(ganttStartDate.getDate() + daysToMonday);

    // Group bars by project (data-task)
    let barIndex = 0;
    $('.task-item').each(function() {
        const taskData = $(this).attr('data-task');
        // All bars for this project
        $('.gantt-bar-container .draggable[data-task="' + taskData + '"]').each(function() {
            const $task = $(this);
            const taskStartDate = new Date($task.attr('data-start-date'));
            const taskEndDate = new Date($task.attr('data-end-date'));
            const taskTop = 30 * barIndex; // 30 is your row height
            const taskHeight = 28; // or your preferred bar height

            // Calculate the number of weeks from the Gantt start date to the task start and end dates
            const weeksFromStart = Math.floor((taskStartDate - ganttStartDate) / (1000 * 60 * 60 * 24 * 7));
            const taskDurationWeeks = Math.ceil((taskEndDate - taskStartDate) / (1000 * 60 * 60 * 24 * 7));

            // Calculate the left position and width of the task bar
            const leftPosition = weeksFromStart * weekWidth;
            const barWidth = taskDurationWeeks * weekWidth;

            if (isNaN(taskStartDate) || isNaN(taskEndDate)) return;
            if (taskDurationWeeks <= 0) return;

            $task.css({
                left: `${leftPosition}px`,
                width: `${barWidth}px`,
                top: `${taskTop}px`,
                height: `${taskHeight}px`
            });
        });
        barIndex++;
    });

    // makeDraggableAndResizable();
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
                            $task = $('[data-task-id="'+taskId+'"]')

                            $task.attr('data-start-date', startDate);
                            $task.attr('data-end-date', endDate);
                            alignGanttBars();
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
                                    $('.start-'+taskId).html(stoppedStartDateFormatted);
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

<script>
$(document).ready(function() {
    $('#home').on('click', function() {
        const today = new Date();
        let found = false;
        $('.week-block').each(function() {
            // Get the week start date from the data attribute or calculate from DOM if needed
            // We'll use the data-date attribute if you have it, otherwise fallback to index
            // For this example, let's assume each .week-block has a data-date attribute for the week's Monday
            const weekStartStr = $(this).attr('data-week-start');
            if (weekStartStr) {
                const weekStart = new Date(weekStartStr);
                const weekEnd = new Date(weekStart);
                weekEnd.setDate(weekEnd.getDate() + 6);
                if (today >= weekStart && today <= weekEnd) {
                    const scrollContainer = $('.scroll-container');
                    const weekLeft = $(this).position().left;
                    scrollContainer.animate({
                        scrollLeft: weekLeft - scrollContainer.width()/2 + $(this).outerWidth()/2
                    }, 400);
                    found = true;
                    return false; // break loop
                }
            }
        });
        // If you don't have data-week-start, you can add it when rendering the calendar weeks in JS/PHP
    });
});
</script>


</body>
</html>
