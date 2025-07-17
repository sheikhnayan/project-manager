<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Project Management</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinite Scrollable Gantt Chart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .draggable {
            cursor: move;
        }

        .calendar-day{
            width: 50px !important;
        }

        .second-input{
            display: flex;
        }

        .draggable:first-of-type{
            top: 0 !important;
            margin-top: 7px !important;
        }
        .calendar-container, .gantt-bar-container {
            display: flex;
            white-space: nowrap;
            position: relative;
        }
        .month-container {
            display: inline-block;
            border-right: 1px solid #ccc;
        }
        .month-header {
            background-color: #4a5568;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .calendar-day {
            display: inline-block;
            width: 3.225%; /* 100% / 31 */
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }
        .gantt-bar-container {
            position: relative;
        }
        .scroll-container {
            overflow-x: scroll;
            width: 100%;
            cursor: grab;
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
            width: 510px;
            background-color: #f7fafc;
            border-right: 1px solid #ccc;
            padding: 10px;
        }
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: bold;
            background-color: #4a5568;
            color: white;
            padding: 10px;
        }
        .task-item {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #e2e8f0;
            border-radius: 4px;
            margin-left: 10px;
            position: relative;
            display: flex;
            align-items: center;
        }
        .task-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 20px;
            height: 2px;
            background-color: #ccc;
        }

        .holiday{
            background: #eee;
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
    <div class="container mx-auto p-4 content">
        <div class="task-list" style="margin-top: 52px;">
            <div class="task-header">
                <span style="width: 40%;">Title</span>
                <span>Start Date</span>
                <span>Assigned</span>
                <span><i class="fas fa-plus"></i></span>
            </div>
            <div class="task-item" data-task="task1">
                <span style="width: 40%;">Title</span>
                <span>Start Date</span>
                <span>Assigned</span>
            </div>
            <div class="task-item" data-task="task2">Project 2</div>
            <div class="task-item" data-task="task3">Project 3</div>
        </div>
        <div class="gantt">
            <!-- JavaScript will populate the months and dates here -->
        </div>
        {{-- <div class="scroll-container">
            <div class="relative mt-4">
            </div>
        </div> --}}
    </div>
    <div class="container mx-auto p-4 content">
        <div class="task-list" style="margin-top: 52px;">
            <div class="task-header">
                <span style="width: 40%;">Name</span>
                <span>Cost</span>
                <span>Hours</span>
            </div>
            <div class="task-item" data-task="task1">
                <span style="width: 40%;">Title</span>
                <span>Start Date</span>
                <span>Assigned</span>
            </div>
            <div class="task-item" data-task="task2">Project 2</div>
            <div class="task-item" data-task="task3">Project 3</div>
        </div>
        <div class="scroll-container">
            <div class="relative mt-4">
                <div class="calendar-container second-calender">
                    <!-- JavaScript will populate the months and dates here -->
                </div>

                <div class="second-input">

                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        $(function() {
            const calendarContainer = $('.calendar-container');
            const ganttBarContainer = $('.gantt-bar-container');
            const scrollContainer = $('.scroll-container');
            const drag = $('.draggable');
            const startDate = new Date(2023, 2, 1); // March 1, 2023
            const endDate = new Date(2023, 11, 31); // December 31, 2023

            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            let currentMonth = startDate.getMonth();

            let monthContainer = $('<div class="month-container"></div>');
            monthContainer.append(`<div class="month-header">${monthNames[currentMonth]}</div>`);

            inp = ``;

            for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
                const day = d.getDate();
                const month = d.getMonth();
                const dayOfWeek = d.getDay();
                const year = d.getFullYear();
                const dateString = `${day}`;
                const dayClass = (dayOfWeek === 0 || dayOfWeek === 1) ? 'calendar-day holiday' : 'calendar-day';

                if (month !== currentMonth) {
                    calendarContainer.append(monthContainer);
                    currentMonth = month;
                    monthContainer = $('<div class="month-container"></div>');
                    monthContainer.append(`<div class="month-header">${monthNames[currentMonth]}</div>`);
                }

                monthContainer.append(`<div class="calendar-day ${dayClass}">${dateString}</div>`);

                inn = `<input type="text" class="calendar-day">`

                inp += inn;
            }

            calendarContainer.append(monthContainer);

            $('.second-input').append(inp);

            console.log($(".calendar-day").outerWidth());

            $(".draggable").draggable({
                axis: "x",
                grid: [$(".calendar-day").outerWidth(), 0], // Step of 1 day
                containment: "document"
            }).resizable({
                grid: [$(".calendar-day").outerWidth(), 0], // Step of 1 day
                handles: "e, w",
                containment: "parent",
                stop: function(event, ui) {
                    $(this).css('height', 'auto'); // Prevent height change
                }
            });

            let isDown = false;
            let startX;
            let scrollLeft;

            drag.on('drag', (e) => {
                isDown = false;
                scrollContainer.removeClass('active');
            })

            scrollContainer.on('mousedown', (e) => {
                isDown = true;
                scrollContainer.addClass('active');
                startX = e.pageX - scrollContainer.offset().left;
                scrollLeft = scrollContainer.scrollLeft();
            });

            scrollContainer.on('mouseleave', () => {
                isDown = false;
                scrollContainer.removeClass('active');
            });

            scrollContainer.on('mouseup', () => {
                isDown = false;
                scrollContainer.removeClass('active');
            });

            scrollContainer.on('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offset().left;
                const walk = (x - startX) * 3; //scroll-fast
                scrollContainer.scrollLeft(scrollLeft - walk);
            });

            scrollContainer.on('scroll', function() {
                const scrollLeft = $(this).scrollLeft();
                calendarContainer.scrollLeft(scrollLeft);
                ganttBarContainer.scrollLeft(scrollLeft);
            });

            // Align Gantt bars with task items
            function alignGanttBars() {
                $('.task-item').each(function(index) {
                    const taskId = $(this).data('task');
                    const ganttBar = $(`.draggable[data-task="${taskId}"]`);
                    const taskTop = $(this).outerHeight() * index;
                    const taskHeight = $(this).outerHeight();
                    ganttBar.css({
                        'top': taskTop - 132,
                        'height': taskHeight
                    });
                });
            }

            alignGanttBars();
            $(window).resize(alignGanttBars);
        });
    </script> --}}

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
        let tasks = [
            {
                id: '1',
                name: 'Redesign website',
                start: '2016-12-28',
                end: '2016-12-31',
                progress: 20
            },
            ]
            let gantt = new Gantt(".gantt", tasks);
    </script>
</body>
</html>
