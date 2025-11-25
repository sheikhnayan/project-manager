// REPLACEMENT CODE FOR renderCalendar() function
// This creates two separate rows: month headers and continuous week cells

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
    
    // Track months and their week counts for dynamic width
    let monthContainers = {};
    let monthOrder = []; // Track order of months as they appear
    
    while (currentDate <= endDate) {
        // Get the Monday of this week for the data-date attribute
        const weekStartDate = new Date(currentDate);
        const weekStartDay = weekStartDate.getDate().toString().padStart(2, '0');
        const weekStartMonth = (weekStartDate.getMonth() + 1).toString().padStart(2, '0');
        const weekStartYear = weekStartDate.getFullYear();
        
        // Calculate ISO week number (continuous, never resets)
        const weekNumber = getISOWeekNumber(currentDate);
        
        // Use THURSDAY of this week to determine which month the week belongs to (ISO standard)
        const thursday = new Date(currentDate);
        thursday.setDate(thursday.getDate() + 3); // Monday + 3 = Thursday
        
        const weekMonth = thursday.getMonth();
        const weekYear = thursday.getFullYear();
        
        // Create month key based on Thursday's date
        const monthKey = `${weekYear}-${String(weekMonth).padStart(2, '0')}`;
        
        // Create month container if it doesn't exist
        if (!monthContainers[monthKey]) {
            monthContainers[monthKey] = {
                year: weekYear,
                month: weekMonth,
                weeks: [],
                sortKey: weekYear * 100 + weekMonth // For proper sorting
            };
            monthOrder.push(monthKey);
        }
        
        // Add week to the month determined by Thursday
        monthContainers[monthKey].weeks.push({
            weekNumber: weekNumber,
            date: `${weekStartYear}-${weekStartMonth}-${weekStartDay}`
        });

        // Create input for this week
        inn = `<input type="number" min="1" max="56" step="1" class="calendar-day inputss" style="min-width: 32px;" onchange="convertTimeInput(this)" oninput="restrictToInteger(this)" data-date="${weekStartYear}-${weekStartMonth}-${weekStartDay}" data-week="${weekNumber}">`;

        inp += inn;
        
        currentDate.setDate(currentDate.getDate() + 7); // Move to next week
    }
    
    // Build TWO SEPARATE ROWS: month headers row + week cells row
    const monthHeaderRow = $('<div class="month-header-row"></div>');
    const weekCellRow = $('<div class="week-cell-row"></div>');
    
    // First row: Month headers with proportional widths
    monthOrder.forEach(function(monthKey) {
        const monthData = monthContainers[monthKey];
        
        // Calculate accurate month width based on actual days in the month
        const year = monthData.year;
        const month = monthData.month;
        const daysInMonth = new Date(year, month + 1, 0).getDate(); // Get actual days (28-31)
        const weeksInMonth = daysInMonth / 7; // Float value (e.g., 4.2857 for 30 days)
        const monthWidth = Math.round(weeksInMonth * 32); // 32px per week unit
        
        console.log(`[Month: ${monthNames[month]} ${year}] ${daysInMonth} days = ${weeksInMonth.toFixed(2)} weeks = ${monthWidth}px`);
        
        const monthHeader = $('<div class="month-header"></div>');
        monthHeader.text(monthNames[month] + ' ' + year);
        monthHeader.css({
            'width': monthWidth + 'px',
            'display': 'inline-block',
            'vertical-align': 'top'
        });
        monthHeaderRow.append(monthHeader);
    });
    
    // Second row: ALL week cells continuously (no breaks)
    const currentDate2 = new Date(alignedStartDate);
    while (currentDate2 <= endDate) {
        const weekStartDate = new Date(currentDate2);
        const weekStartDay = weekStartDate.getDate().toString().padStart(2, '0');
        const weekStartMonth = (weekStartDate.getMonth() + 1).toString().padStart(2, '0');
        const weekStartYear = weekStartDate.getFullYear();
        const weekNumber = getISOWeekNumber(currentDate2);
        
        const weekCell = $(`<div class="calendar-day" data-date="${weekStartYear}-${weekStartMonth}-${weekStartDay}" data-week="${weekNumber}">W${weekNumber}</div>`);
        weekCellRow.append(weekCell);
        
        currentDate2.setDate(currentDate2.getDate() + 7);
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
