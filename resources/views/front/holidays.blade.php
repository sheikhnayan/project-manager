<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Holidays</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='2' y='6' fill='%23000'/><rect width='24' height='2' y='11' fill='%23000'/><rect width='24' height='2' y='16' fill='%23000'/></svg>">
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>
    <script src='https://cdn.tailwindcss.com'></script>
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>
    <script src='https://unpkg.com/lucide@latest'></script>
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .calendar-day {
            display: inline-block;
            width: 32px !important;
            min-width: 32px !important;
            max-width: 32px !important;
            height: 20px !important;
            text-align: center;
            border: 1px solid #ccc;
            box-sizing: border-box;
            vertical-align: top;
            padding-top: 1px;
            font-size: 10px;
            margin: 0;
            border-top: unset;
            flex-shrink: 0;
            border-right: 1px solid #eee;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            border-left: unset;
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
        
        .calendar-day-row {
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
            flex: 1;
            overflow-x: auto;
            overflow-y: hidden;
            cursor: grab;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .scroll-container:active {
            cursor: grabbing;
        }

        .content {
            margin-top: 0px !important;
            display: flex;
            border-bottom-left-radius: 0px !important;
            border-bottom-right-radius: 0px !important;
        }

        .task-list {
            width: 400px;
            background-color: #f7fafc;
            border-right: 1px solid #ccc;
            border-radius: 4px;
            border-bottom-left-radius: 0px !important;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* margin-bottom: 10px; */
            font-weight: bold;
            background-color: #000;
            color: white;
            padding: 10px;
            height: 52px;
        }

        .task-item {
            padding: 0px;
            padding-top: 0px;
            position: relative;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .holiday-checkbox-row {
            display: flex;
            white-space: nowrap;
            width: fit-content;
        }

        .holiday-checkbox {
            width: 32px !important;
            min-width: 32px !important;
            max-width: 32px !important;
            height: 30px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #eee;
            border-bottom: 1px solid #eee;
            box-sizing: border-box;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .holiday-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #000;
        }

        .holiday-checkbox:hover {
            background-color: #f3f4f6;
        }

        .holiday-checkbox.disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .holiday-checkbox.disabled input[type="checkbox"]:not(:checked) {
            cursor: not-allowed;
        }

        .weekend {
            background-color: #f9fafb !important;
            color: #d9534f !important;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 3rem;
            margin-top: 3rem;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #111827;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <div class="mx-auto p-4 shadow rounded-lg border" style="background: #fff !important; border: 1px solid #D1D5DB; margin: 16px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);">
        <div class="p-2" style="padding-left: 0px !important; display: block; margin-bottom: 20px;">
            <div style="float: left; margin-top: 6px;">
                <h5 style="font-size: 20px; font-weight: 600; padding-left: 0px;">My Holidays</h5>
            </div>
            <div class="flex items-center" style="float: right;">
                <button class="text-gray-600 hover:text-black" id="home" style="margin-right: 8px; border: 1px solid #eee; padding:0.6rem 0.8rem; border-radius:4px; background: white;">
                    <i data-lucide="home" style="width: 16px; height: 16px; color: #000;"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Allowed</div>
                <div class="stat-value" id="stat-allowed">{{ $user->holidays_allowed ?? 20 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Used</div>
                <div class="stat-value" id="stat-used">{{ count($holidays) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Remaining</div>
                <div class="stat-value" id="stat-remaining">{{ ($user->holidays_allowed ?? 20) - count($holidays) }}</div>
            </div>
        </div>

        <div class="content" style="display: block; background: #fff;">
            <div style="display: flex;">
                <!-- Task List Section -->
                <div class="task-list">
                    <div class="task-header" style="border-top-left-radius: 4px;">
                        <span style="width: 100%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px;">
                            Holiday Selection
                        </span>
                    </div>
                    <div class="task-item" style="margin-bottom: 0px; border-bottom: 1px solid #eee; margin-left: 0px; background: #fff;">
                        <span style="padding-left: 12px; width: 100%; font-size: 12px; display: inherit; padding-top: 4px; padding-bottom: 7px;">
                            Select Your Holidays
                        </span>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div class="scroll-container" id="scroll-container">
                    <div class="calendar-container" id="calendar-container"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const holidaysData = @json($holidays);
        const holidaysAllowed = {{ $user->holidays_allowed ?? 20 }};
        let selectedHolidays = new Set(holidaysData);

        $(document).ready(function() {
            renderCalendar();
            updateStats();

            // Drag to scroll functionality
            const scrollContainer = document.getElementById('scroll-container');
            let isDown = false;
            let startX;
            let scrollLeft;

            scrollContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                scrollContainer.style.cursor = 'grabbing';
                startX = e.pageX - scrollContainer.offsetLeft;
                scrollLeft = scrollContainer.scrollLeft;
            });

            scrollContainer.addEventListener('mouseleave', () => {
                isDown = false;
                scrollContainer.style.cursor = 'grab';
            });

            scrollContainer.addEventListener('mouseup', () => {
                isDown = false;
                scrollContainer.style.cursor = 'grab';
            });

            scrollContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offsetLeft;
                const walk = (x - startX) * 2;
                scrollContainer.scrollLeft = scrollLeft - walk;
            });

            // Mouse wheel horizontal scroll
            scrollContainer.addEventListener('wheel', (e) => {
                e.preventDefault();
                scrollContainer.scrollLeft += e.deltaY;
            });

            // Home button functionality
            $('#home').on('click', function() {
                const today = new Date();
                const scrollContainer = $('.scroll-container');
                const todayStr = `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
                const $todayElement = $(`.calendar-day[data-date="${todayStr}"]`);
                
                if ($todayElement.length > 0) {
                    const elementLeft = $todayElement.position().left;
                    const cellWidth = $todayElement.outerWidth();
                    const scrollPosition = elementLeft - (cellWidth * 3);
                    scrollContainer.animate({
                        scrollLeft: Math.max(0, scrollPosition)
                    }, 400);
                }
            });
        });

        function renderCalendar() {
            const calendarContainer = $('#calendar-container');
            const startDate = new Date('2026-01-01');
            const endDate = new Date('2026-12-31');
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            calendarContainer.empty();

            // Create month headers row
            const monthHeaderRow = $('<div class="month-header-row"></div>');
            
            // Create calendar days row
            const calendarDayRow = $('<div class="calendar-day-row"></div>');
            
            // Create holiday checkboxes row
            const holidayCheckboxRow = $('<div class="holiday-checkbox-row"></div>');

            let currentMonth = -1;
            let monthDayCount = 0;
            let currentMonthHeader = null;

            for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                const date = new Date(d);
                const month = date.getMonth();
                const day = date.getDate();
                const dayOfWeek = date.getDay();
                const dateStr = `${date.getFullYear()}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

                // Add month header
                if (month !== currentMonth) {
                    if (currentMonthHeader) {
                        currentMonthHeader.css('width', (monthDayCount * 32) + 'px');
                    }
                    currentMonth = month;
                    monthDayCount = 0;
                    currentMonthHeader = $(`<div class="month-header">${monthNames[month]}</div>`);
                    monthHeaderRow.append(currentMonthHeader);
                }
                monthDayCount++;

                // Add calendar day
                const dayClass = isWeekend ? 'calendar-day weekend' : 'calendar-day';
                const calendarDay = $(`<div class="${dayClass}" data-date="${dateStr}">${day}</div>`);
                calendarDayRow.append(calendarDay);

                // Add holiday checkbox
                const isSelected = selectedHolidays.has(dateStr);
                const isDisabled = !isSelected && selectedHolidays.size >= holidaysAllowed;
                const checkboxClass = isDisabled ? 'holiday-checkbox disabled' : 'holiday-checkbox';
                const checkboxHtml = `
                    <div class="${checkboxClass}" data-date="${dateStr}" style="${isWeekend ? 'background-color: #f9fafb;' : ''}">
                        <input type="checkbox" 
                               data-date="${dateStr}" 
                               ${isSelected ? 'checked' : ''} 
                               ${isDisabled ? 'disabled' : ''}>
                    </div>
                `;
                holidayCheckboxRow.append(checkboxHtml);
            }

            // Set last month header width
            if (currentMonthHeader) {
                currentMonthHeader.css('width', (monthDayCount * 32) + 'px');
            }

            calendarContainer.append(monthHeaderRow);
            calendarContainer.append(calendarDayRow);
            calendarContainer.append(holidayCheckboxRow);

            // Add event listeners to checkboxes
            $('.holiday-checkbox input[type="checkbox"]').on('change', function(e) {
                const date = $(this).data('date');
                const isChecked = $(this).prop('checked');
                
                toggleHoliday(date, isChecked);
            });
        }

        function toggleHoliday(date, isChecked) {
            $.ajax({
                url: '/holidays/toggle',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { date: date },
                success: function(response) {
                    if (response.success) {
                        if (response.action === 'added') {
                            selectedHolidays.add(date);
                        } else {
                            selectedHolidays.delete(date);
                        }
                        updateStats();
                        renderCalendar(); // Re-render to update disabled states
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    alert(response.message || 'An error occurred');
                    // Revert checkbox state
                    $(`input[data-date="${date}"]`).prop('checked', !isChecked);
                }
            });
        }

        function updateStats() {
            const used = selectedHolidays.size;
            const remaining = holidaysAllowed - used;
            
            $('#stat-used').text(used);
            $('#stat-remaining').text(remaining);
        }
    </script>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
