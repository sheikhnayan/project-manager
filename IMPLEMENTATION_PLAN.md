# Calendar Two-Row Structure Implementation Plan

## Overview
Restructure the calendar to have two separate rows:
1. **Month Header Row**: Month names with proportional widths based on days in month
2. **Week Cell Row**: Continuous week cells (W01, W02, etc.) with no breaks

## CSS Changes Required

### Update `.calendar-container` (around line 97)
```css
.calendar-container {
    display: block; /* Changed from flex */
    white-space: nowrap;
    position: relative;
}
```

### Add new `.month-header-row` style
```css
.month-header-row {
    display: flex;
    white-space: nowrap;
    width: fit-content;
}
```

### Add new `.week-cell-row` style
```css
.week-cell-row {
    display: flex;
    white-space: nowrap;
    width: fit-content;
}
```

### Update `.month-header` (around line 105)
```css
.month-header {
    display: inline-block; /* Changed from block */
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
```

### Update `.calendar-day` (around line 117)
Add flex-shrink property:
```css
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
    flex-shrink: 0; /* ADD THIS LINE */
}
```

## JavaScript Changes Required

### Replace the entire section from line 1029-1060
Replace the "Build month containers" section with the two-row structure:

```javascript
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
```

## Result
- Month headers will span proportional widths (e.g., Jan = ~142px for 31 days, Feb = ~128px for 28 days)
- Week cells will be continuous with no visual breaks between months
- Both rows align properly with DHTMLX Gantt above them
