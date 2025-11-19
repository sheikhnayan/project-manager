/**
 * Gantt Chart V2 - Clean, Modular Implementation
 * All functionalities from original with better organization
 */

class GanttChart {
    constructor() {
        // Configuration
        this.config = {
            dayWidth: 24,
            taskHeight: 30,
            maxHistorySize: 20,
            scrollSpeed: 30,
            edgeThreshold: 60,
            calendarStartOffset: -1, // 1 year before today
            calendarEndOffset: 10    // 10 years after project end
        };

        // State
        this.state = {
            undoStack: [],
            redoStack: [],
            isRestoringState: false,
            startDate: null,
            endDate: null,
            monthNames: ["January", "February", "March", "April", "May", "June", 
                        "July", "August", "September", "October", "November", "December"]
        };

        // DOM Elements (will be initialized in init)
        this.elements = {};
    }

    /**
     * Initialize the Gantt Chart
     */
    init() {
        console.log('Initializing Gantt Chart V2...');
        
        // Cache DOM elements
        this.cacheElements();
        
        // Calculate date ranges
        this.calculateDateRanges();
        
        // Render calendar
        this.renderCalendar();
        
        // Align Gantt bars
        this.alignGanttBars();
        
        // Setup interactions
        this.setupDragAndResize();
        this.setupUndoRedo();
        this.setupSorting();
        this.setupScrolling();
        this.setupTimeTracking();
        this.setupExpandCollapse();
        
        // Add visual indicators
        this.highlightToday();
        this.highlightHolidays();
        
        // Save initial state
        setTimeout(() => this.saveState(), 1000);
        
        // Scroll to today
        this.scrollToToday();
        
        console.log('Gantt Chart V2 initialized successfully');
    }

    /**
     * Cache DOM elements for performance
     */
    cacheElements() {
        this.elements = {
            calendarContainer: $('.calendar-container'),
            ganttBarContainer: $('.gantt-bar-container'),
            scrollContainer: $('.scroll-container'),
            undoBtn: $('#undoBtn'),
            redoBtn: $('#redoBtn'),
            homeBtn: $('#home'),
            sortProject: $('#sortProject'),
            sortProjectUser: $('#sortProjectuser'),
            teamMembersList: $('#team-members-list'),
            teamTimeInputs: $('#team-time-inputs')
        };
    }

    /**
     * Calculate date ranges for calendar
     */
    calculateDateRanges() {
        const projectEnd = new Date($('#en_date').val());
        
        // Start: 1 year before today
        this.state.startDate = new Date();
        this.state.startDate.setFullYear(this.state.startDate.getFullYear() + this.config.calendarStartOffset);
        
        // End: 10 years after project end
        this.state.endDate = new Date(projectEnd);
        this.state.endDate.setFullYear(this.state.endDate.getFullYear() + this.config.calendarEndOffset);
        
        console.log('Calendar range:', this.state.startDate, 'to', this.state.endDate);
    }

    /**
     * Render the calendar
     */
    renderCalendar() {
        this.elements.calendarContainer.empty();
        
        let currentMonth = this.state.startDate.getMonth();
        let monthContainer = $('<div class="month-container"></div>');
        monthContainer.append(`<div class="month-header">${this.state.monthNames[currentMonth]}</div>`);
        
        let inp = '';
        const baseColor = [74, 85, 104];
        const fadeStep = 2;
        let fadeIndex = 0;
        
        const currentDate = new Date(this.state.startDate);
        while (currentDate <= this.state.endDate) {
            const day = currentDate.getDate().toString().padStart(2, '0');
            const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
            const year = currentDate.getFullYear();
            const dayOfWeek = currentDate.getDay();
            const dateString = `${day}`;
            const dayClass = (dayOfWeek === 0 || dayOfWeek === 6) ? 'calendar-day holiday' : 'calendar-day';
            
            if (currentDate.getMonth() !== currentMonth) {
                const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                        ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                        ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
                monthContainer.find('.month-header').css('background-color', fadeColor);
                
                this.elements.calendarContainer.append(monthContainer);
                currentMonth = currentDate.getMonth();
                monthContainer = $('<div class="month-container"></div>');
                monthContainer.append(`<div class="month-header">${this.state.monthNames[currentMonth]}</div>`);
                
                fadeIndex++;
            }
            
            monthContainer.append(`<div class="calendar-day ${dayClass}" data-date="${year}-${month}-${day}">${dateString}</div>`);
            
            const inputField = `<input type="number" min="1" max="8" step="1" 
                class="${dayClass} calendar-day inputss" 
                style="min-width: 24px;" 
                onchange="ganttChart.convertTimeInput(this)" 
                oninput="ganttChart.restrictToInteger(this)" 
                data-date="${year}-${month}-${day}">`;
            inp += inputField;
            
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        const fadeColor = `rgb(${Math.min(baseColor[0] + fadeIndex * fadeStep, 255)},
                                ${Math.min(baseColor[1] + fadeIndex * fadeStep, 255)},
                                ${Math.min(baseColor[2] + fadeIndex * fadeStep, 255)})`;
        monthContainer.find('.month-header').css('background-color', fadeColor);
        this.elements.calendarContainer.append(monthContainer);
        
        // Append inputs to time input rows
        $('.second-input:not(.member-time-calendar-row)').append(inp);
        $('.second-input:not(.member-time-calendar-row)').each(function() {
            const userId = $(this).data('user-id');
            $(this).find('.inputss').attr('data-user-id', userId);
        });
        
        // Populate member time calendar rows
        this.populateMemberTimeCalendarRows();
    }

    /**
     * Populate member time calendar rows
     */
    populateMemberTimeCalendarRows() {
        $('.member-time-calendar-row').each((index, element) => {
            const $row = $(element);
            const userId = $row.data('user-id');
            const projectId = $row.data('project-id');
            
            $row.empty();
            
            const userTimeEntries = window.memberTimeEntries && window.memberTimeEntries[userId] 
                ? window.memberTimeEntries[userId] : {};
            
            let memberInp = '';
            const currentDate = new Date(this.state.startDate);
            
            while (currentDate <= this.state.endDate) {
                const day = currentDate.getDate().toString().padStart(2, '0');
                const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
                const year = currentDate.getFullYear();
                const dayOfWeek = currentDate.getDay();
                const dateString = `${year}-${month}-${day}`;
                const dayClass = (dayOfWeek === 0 || dayOfWeek === 6) ? 'calendar-day holiday' : 'calendar-day';
                
                const existingHours = userTimeEntries[dateString] || '';
                
                memberInp += `<input type="number" min="1" max="8" step="1" 
                    class="${dayClass} inputsss member-time-input" 
                    style="min-width: 24px;" 
                    data-user-id="${userId}" 
                    data-project-id="${projectId}" 
                    data-date="${dateString}"
                    value="${existingHours}"
                    disabled>`;
                
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            $row.append(memberInp);
        });
    }

    /**
     * Align Gantt bars with calendar
     */
    alignGanttBars() {
        const dayWidth = $(".calendar-day").outerWidth();
        const ganttStartDate = new Date(this.state.startDate);
        
        $('.draggable').each((index, element) => {
            const $task = $(element);
            const taskStartDate = new Date($task.attr('data-start-date'));
            const taskEndDate = new Date($task.attr('data-end-date'));
            
            if (isNaN(taskStartDate) || isNaN(taskEndDate)) {
                console.warn("Invalid dates for task:", $task.attr('data-task-id'));
                return;
            }
            
            const daysFromStart = Math.floor((taskStartDate - ganttStartDate) / (1000 * 60 * 60 * 24));
            const taskDuration = Math.floor((taskEndDate - taskStartDate) / (1000 * 60 * 60 * 24)) + 1;
            
            if (taskDuration <= 0) {
                console.warn("Task duration invalid:", $task.attr('data-task-id'));
                return;
            }
            
            const leftPosition = daysFromStart * dayWidth;
            const barWidth = taskDuration * dayWidth;
            const taskTop = 30 * index;
            
            $task.css({
                left: `${leftPosition}px`,
                width: `${barWidth}px`,
                top: `${taskTop}px`,
                height: '24px'
            });
        });
    }

    /**
     * Setup drag and resize functionality
     */
    setupDragAndResize() {
        const self = this;
        
        $(".draggable").draggable({
            axis: "x",
            grid: [this.config.dayWidth, 0],
            containment: "document",
            cancel: ".ui-resizable-handle",
            start: function(event, ui) {
                if ($(event.originalEvent.target).hasClass('ui-resizable-handle')) {
                    return false;
                }
                const $task = $(this);
                $task.data("initialStartDate", $task.attr("data-start-date"));
                $task.data("initialEndDate", $task.attr("data-end-date"));
                self.saveStateBeforeAction();
            },
            stop: function(event, ui) {
                const $task = $(this);
                self.updateTaskDatesAfterDrag($task, ui);
                self.checkOverlaps();
            }
        }).resizable({
            handles: {
                'e': '.ui-resizable-e',
                'w': '.ui-resizable-w'
            },
            grid: [this.config.dayWidth, 0],
            containment: "document",
            start: function(event, ui) {
                const $task = $(this);
                $task.data("initialStartDate", $task.attr("data-start-date"));
                $task.data("initialEndDate", $task.attr("data-end-date"));
                self.saveStateBeforeAction();
            },
            stop: function(event, ui) {
                const $task = $(this);
                self.updateTaskDatesAfterResize($task, ui);
                self.checkOverlaps();
            }
        });
    }

    /**
     * Update task dates after drag
     */
    updateTaskDatesAfterDrag($task, ui) {
        const dayWidth = $(".calendar-day").outerWidth();
        const ganttStartDate = new Date(this.state.startDate);
        
        const startOffset = ui.position.left;
        const endOffset = startOffset + $task.outerWidth();
        
        const currentStartDate = new Date(ganttStartDate);
        currentStartDate.setDate(ganttStartDate.getDate() + Math.round(startOffset / dayWidth));
        
        const currentEndDate = new Date(ganttStartDate);
        currentEndDate.setDate(ganttStartDate.getDate() + Math.round(endOffset / dayWidth) - 1);
        
        $task.attr('data-start-date', currentStartDate.toISOString().split('T')[0]);
        $task.attr('data-end-date', currentEndDate.toISOString().split('T')[0]);
        
        this.saveTaskToServer($task);
    }

    /**
     * Update task dates after resize
     */
    updateTaskDatesAfterResize($task, ui) {
        const dayWidth = $(".calendar-day").outerWidth();
        const ganttStartDate = new Date(this.state.startDate);
        
        const startOffset = Math.round(ui.position.left / dayWidth) * dayWidth;
        const endOffset = Math.round((ui.position.left + ui.size.width) / dayWidth) * dayWidth;
        
        const finalStartDate = new Date(ganttStartDate);
        finalStartDate.setDate(ganttStartDate.getDate() + Math.floor(startOffset / dayWidth));
        
        const finalEndDate = new Date(ganttStartDate);
        finalEndDate.setDate(ganttStartDate.getDate() + Math.floor(endOffset / dayWidth) - 1);
        
        $task.attr("data-start-date", finalStartDate.toISOString().split('T')[0]);
        $task.attr("data-end-date", finalEndDate.toISOString().split('T')[0]);
        
        this.saveTaskToServer($task);
    }

    /**
     * Save task to server
     */
    saveTaskToServer($task) {
        const taskId = $task.attr('data-task-id');
        const startDate = $task.attr('data-start-date');
        const endDate = $task.attr('data-end-date');
        
        $.ajax({
            url: '/projects/save-dates',
            type: 'POST',
            data: {
                stoppedStartDate: startDate,
                stoppedEndDate: endDate,
                task_id: taskId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                $(`.start-${taskId}`).html(this.formatDateForDisplay(startDate));
                console.log('Task dates saved:', taskId);
            },
            error: (xhr, status, error) => {
                console.error('Error saving task dates:', error);
            }
        });
    }

    /**
     * Check for overlapping bars
     */
    checkOverlaps() {
        const bars = $('.gantt-bar-container .draggable');
        bars.removeClass('alert-danger');
        
        bars.each((i, barA) => {
            const $barA = $(barA);
            const startA = new Date($barA.attr('data-start-date'));
            const endA = new Date($barA.attr('data-end-date'));
            
            bars.each((j, barB) => {
                if (i === j) return;
                
                const $barB = $(barB);
                const startB = new Date($barB.attr('data-start-date'));
                const endB = new Date($barB.attr('data-end-date'));
                
                if (this.isOverlap(startA, endA, startB, endB)) {
                    $barA.addClass('alert-danger');
                    $barB.addClass('alert-danger');
                }
            });
        });
    }

    /**
     * Check if two date ranges overlap
     */
    isOverlap(startA, endA, startB, endB) {
        return (startA <= endB && endA >= startB);
    }

    /**
     * Setup undo/redo functionality
     */
    setupUndoRedo() {
        // Undo button
        this.elements.undoBtn.on('click', () => {
            if (!this.elements.undoBtn.prop('disabled')) {
                this.performUndo();
            }
        });
        
        // Redo button
        this.elements.redoBtn.on('click', () => {
            if (!this.elements.redoBtn.prop('disabled')) {
                this.performRedo();
            }
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', (e) => {
            if (e.ctrlKey && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                this.performUndo();
            } else if ((e.ctrlKey && e.shiftKey && e.key === 'Z') || (e.ctrlKey && e.key === 'y')) {
                e.preventDefault();
                this.performRedo();
            }
        });
    }

    /**
     * Save current state
     */
    saveState() {
        if (this.state.isRestoringState) return;
        
        const currentState = { tasks: [] };
        
        $('.draggable').each((index, element) => {
            const $task = $(element);
            currentState.tasks.push({
                id: $task.attr('data-task-id'),
                startDate: $task.attr('data-start-date'),
                endDate: $task.attr('data-end-date'),
                left: $task.css('left'),
                width: $task.css('width'),
                top: $task.css('top')
            });
        });
        
        if (this.state.undoStack.length >= this.config.maxHistorySize) {
            this.state.undoStack.shift();
        }
        
        this.state.undoStack.push(JSON.parse(JSON.stringify(currentState)));
        this.state.redoStack = [];
        this.updateUndoRedoButtons();
    }

    /**
     * Save state before action (for undo)
     */
    saveStateBeforeAction() {
        if (this.state.isRestoringState) return;
        this.saveState();
    }

    /**
     * Perform undo
     */
    performUndo() {
        if (this.state.undoStack.length === 0) return;
        
        const currentState = { tasks: [] };
        $('.draggable').each((index, element) => {
            const $task = $(element);
            currentState.tasks.push({
                id: $task.attr('data-task-id'),
                startDate: $task.attr('data-start-date'),
                endDate: $task.attr('data-end-date'),
                left: $task.css('left'),
                width: $task.css('width'),
                top: $task.css('top')
            });
        });
        
        this.state.redoStack.push(currentState);
        const previousState = this.state.undoStack.pop();
        this.restoreState(previousState);
    }

    /**
     * Perform redo
     */
    performRedo() {
        if (this.state.redoStack.length === 0) return;
        
        const currentState = { tasks: [] };
        $('.draggable').each((index, element) => {
            const $task = $(element);
            currentState.tasks.push({
                id: $task.attr('data-task-id'),
                startDate: $task.attr('data-start-date'),
                endDate: $task.attr('data-end-date'),
                left: $task.css('left'),
                width: $task.css('width'),
                top: $task.css('top')
            });
        });
        
        this.state.undoStack.push(currentState);
        const nextState = this.state.redoStack.pop();
        this.restoreState(nextState);
    }

    /**
     * Restore state
     */
    restoreState(state) {
        if (!state) return;
        
        this.state.isRestoringState = true;
        
        state.tasks.forEach(taskState => {
            const $task = $(`.draggable[data-task-id="${taskState.id}"]`);
            if ($task.length) {
                $task.css({
                    left: taskState.left,
                    width: taskState.width,
                    top: taskState.top
                });
                
                $task.attr('data-start-date', taskState.startDate);
                $task.attr('data-end-date', taskState.endDate);
                
                $(`.start-${taskState.id}`).html(this.formatDateForDisplay(taskState.startDate));
                this.saveTaskToServer($task);
            }
        });
        
        this.state.isRestoringState = false;
        this.updateUndoRedoButtons();
        setTimeout(() => this.checkOverlaps(), 100);
    }

    /**
     * Update undo/redo button states
     */
    updateUndoRedoButtons() {
        this.elements.undoBtn.prop('disabled', this.state.undoStack.length === 0);
        this.elements.redoBtn.prop('disabled', this.state.redoStack.length === 0);
    }

    /**
     * Setup sorting functionality
     */
    setupSorting() {
        let taskAsc = true;
        let userAsc = true;
        
        // Sort tasks
        this.elements.sortProject.on('click', () => {
            this.sortTasks(taskAsc);
            taskAsc = !taskAsc;
        });
        
        // Sort team members
        this.elements.sortProjectUser.on('click', () => {
            this.sortTeamMembers(userAsc);
            userAsc = !userAsc;
        });
        
        // Make team members sortable by drag
        this.elements.teamMembersList.sortable({
            handle: '.drag-handle',
            axis: 'y',
            cursor: 'move',
            update: (event, ui) => {
                this.reorderTimeInputs();
            }
        });
    }

    /**
     * Sort tasks
     */
    sortTasks(asc) {
        const items = $('.mains .task-list .task-item').get();
        items.sort((a, b) => {
            const keyA = $(a).find('span').first().text().trim().toLowerCase();
            const keyB = $(b).find('span').first().text().trim().toLowerCase();
            return asc ? keyA.localeCompare(keyB) : keyB.localeCompare(keyA);
        });
        
        const ganttBars = $('.gantt-bar-container .draggable').get();
        const taskOrder = items.map(item => $(item).attr('data-task'));
        
        ganttBars.sort((a, b) => {
            const taskA = $(a).data('task');
            const taskB = $(b).data('task');
            const indexA = taskOrder.indexOf(taskA);
            const indexB = taskOrder.indexOf(taskB);
            return (indexA === -1 ? 999 : indexA) - (indexB === -1 ? 999 : indexB);
        });
        
        $.each(items, (i, item) => $('.mains .task-list').append(item));
        
        $('.gantt-bar-container').empty();
        $.each(ganttBars, (index, bar) => {
            $(bar).css('top', `${30 * index}px`);
            $('.gantt-bar-container').append(bar);
        });
        
        this.setupDragAndResize();
        $('.today-line, .holiday-highlight').remove();
        this.highlightToday();
        this.highlightHolidays();
        setTimeout(() => this.checkOverlaps(), 100);
    }

    /**
     * Sort team members
     */
    sortTeamMembers(asc) {
        const items = $('.us .names .task-item').get();
        items.sort((a, b) => {
            const keyA = $(a).find('span').first().text().trim().toLowerCase();
            const keyB = $(b).find('span').first().text().trim().toLowerCase();
            return asc ? keyA.localeCompare(keyB) : keyB.localeCompare(keyA);
        });
        
        $.each(items, (i, item) => $('.us .names').append(item));
        this.reorderTimeInputs();
    }

    /**
     * Reorder time inputs to match team member order
     */
    reorderTimeInputs() {
        const memberOrder = [];
        $('#team-members-list .team-member-row').each((index, element) => {
            memberOrder.push($(element).data('member-id'));
        });
        
        const $timeInputContainer = $('#team-time-inputs');
        const $timeInputs = $timeInputContainer.find('.time-input-row').detach();
        
        memberOrder.forEach(memberId => {
            const $matchingInput = $timeInputs.filter(`[data-member-id="${memberId}"]`);
            if ($matchingInput.length > 0) {
                $timeInputContainer.append($matchingInput);
            }
        });
        
        // Refresh attributes
        $('.time-input-row .inputss').each(function() {
            const $this = $(this);
            const $parent = $this.parent();
            $this.attr('data-user-id', $parent.data('user-id'));
        });
    }

    /**
     * Setup scrolling functionality
     */
    setupScrolling() {
        // Drag to scroll
        this.elements.scrollContainer.each((index, element) => {
            const $container = $(element);
            let isDragging = false;
            let startX, scrollLeft;
            
            $container.on('mousedown', (e) => {
                isDragging = true;
                startX = e.pageX - $container.offset().left;
                scrollLeft = $container.scrollLeft();
                $container.css('cursor', 'grabbing');
            });
            
            $container.on('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - $container.offset().left;
                const walk = (x - startX) * 1;
                $container.scrollLeft(scrollLeft - walk);
            });
            
            $container.on('mouseup mouseleave', () => {
                isDragging = false;
                $container.css('cursor', 'grab');
            });
        });
        
        // Home button - scroll to today
        this.elements.homeBtn.on('click', () => this.scrollToToday());
    }

    /**
     * Scroll to today's date
     */
    scrollToToday() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const todayStr = `${yyyy}-${mm}-${dd}`;
        
        const $todayCell = $(`.calendar-day[data-date="${todayStr}"]`);
        if ($todayCell.length) {
            const scrollContainer = $('.scroll-container').first();
            const cellLeft = $todayCell.position().left;
            scrollContainer.animate({
                scrollLeft: cellLeft - scrollContainer.width() / 14 + $todayCell.outerWidth() / 2
            }, 400);
        }
    }

    /**
     * Highlight today's date
     */
    highlightToday() {
        const today = new Date();
        const dayWidth = $(".calendar-day").outerWidth();
        const taskCount = $('#task_count').val();
        
        const daysFromStart = Math.floor((today - this.state.startDate) / (1000 * 60 * 60 * 24));
        const todayPosition = daysFromStart * dayWidth;
        
        const totalHeight = (taskCount * 30) + 20;
        
        const todayLine = $('<div class="today-line"></div>');
        todayLine.css({
            left: todayPosition + 'px',
            height: totalHeight + 'px'
        });
        
        $('.gantt-bar-container').append(todayLine);
    }

    /**
     * Highlight holidays (weekends)
     */
    highlightHolidays() {
        const dayWidth = $(".calendar-day").outerWidth();
        const holidays = [];
        
        const currentDate = new Date(this.state.startDate);
        while (currentDate <= this.state.endDate) {
            const dayOfWeek = currentDate.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                holidays.push(new Date(currentDate));
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        $('.gantt-bar-container .holiday-highlight').remove();
        
        holidays.forEach(holiday => {
            const daysFromStart = Math.floor((holiday - this.state.startDate) / (1000 * 60 * 60 * 24));
            const holidayPosition = daysFromStart * dayWidth;
            
            const holidayHighlight = $('<div class="holiday-highlight"></div>');
            holidayHighlight.css({
                left: holidayPosition + 'px',
                width: dayWidth + 'px'
            });
            
            $('.gantt-bar-container').append(holidayHighlight);
        });
    }

    /**
     * Setup time tracking functionality
     */
    setupTimeTracking() {
        // Populate existing data
        setTimeout(() => this.populateTimeTrackingData(), 200);
        
        // Show/hide archived members
        $('.show-user').on('click', function() {
            const type = $(this).data('type');
            if (type == 'show') {
                $('.not-archived').hide();
                $('.archied').show();
                $(this).removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $('.not-archived').show();
                $('.archied').hide();
                $(this).removeClass('fa-eye-slash').addClass('fa-eye');
            }
            $(this).data('type', type == 'show' ? 'hide' : 'show');
        });
    }

    /**
     * Populate time tracking data
     */
    async populateTimeTrackingData() {
        const project = $('#project_id').val();
        
        try {
            const response = await fetch(`/estimated-time-tracking/${project}/get`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (!response.ok) {
                console.error('Failed to fetch time tracking data');
                return;
            }
            
            const data = await response.json();
            
            data.forEach(item => {
                const { user_id, date, time } = item;
                const inputFields = document.querySelectorAll('.inputss');
                
                inputFields.forEach(inputField => {
                    const inputDate = inputField.getAttribute('data-date');
                    const userId = inputField.getAttribute('data-user-id');
                    
                    if (inputDate === date && userId == user_id) {
                        let integerTime;
                        if (typeof time === 'string' && time.includes(':')) {
                            integerTime = parseInt(time.split(':')[0]);
                        } else {
                            integerTime = parseInt(time);
                        }
                        
                        if (!isNaN(integerTime) && integerTime >= 1 && integerTime <= 8) {
                            inputField.value = integerTime;
                        }
                    }
                });
            });
        } catch (error) {
            console.error('Error fetching time tracking data:', error);
        }
    }

    /**
     * Restrict input to integers
     */
    restrictToInteger(inputElement) {
        inputElement.value = inputElement.value.replace(/[^0-9]/g, '');
        const value = parseInt(inputElement.value);
        if (value > 8) {
            inputElement.value = '8';
        } else if (value < 1 && inputElement.value !== '') {
            inputElement.value = '1';
        }
    }

    /**
     * Convert and save time input
     */
    async convertTimeInput(inputElement) {
        const integerTime = parseInt(inputElement.value);
        const date = inputElement.getAttribute('data-date');
        const user_id = inputElement.getAttribute('data-user-id');
        const project_id = $('#project_id').val();
        
        if (isNaN(integerTime) || integerTime == 0) {
            inputElement.value = '';
            await this.saveTimeEntry(user_id, date, 0, project_id);
            return;
        }
        
        if (integerTime < 1 || integerTime > 8) {
            alert('Value is Invalid. Please enter a number between 1 and 8.');
            inputElement.value = '';
            return;
        }
        
        inputElement.value = integerTime;
        await this.saveTimeEntry(user_id, date, integerTime, project_id);
    }

    /**
     * Save time entry to server
     */
    async saveTimeEntry(user_id, date, data, project_id) {
        try {
            const response = await fetch('/estimated-time-tracking/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ user_id, date, data, project_id })
            });
            
            if (response.ok) {
                const responseData = await response.json();
                $(`.user-hour-${user_id}`).html(responseData.data.total);
                $(`.user-cost-${user_id}`).html(responseData.data.cost);
                
                $('#fetch').load(`/projects/reload-data/${project_id}`, () => {
                    setTimeout(() => {
                        if (typeof initProgressRings === 'function') {
                            initProgressRings();
                        }
                    }, 10);
                });
            }
        } catch (error) {
            console.error('Error saving time entry:', error);
        }
    }

    /**
     * Setup expand/collapse for member time entries
     */
    setupExpandCollapse() {
        $(document).on('click', '.expand-arrow', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const $arrow = $(e.currentTarget);
            const targetId = $arrow.data('id');
            const projectId = $arrow.data('project-id');
            const isExpanded = $arrow.hasClass('expanded');
            
            if (isExpanded) {
                $arrow.removeClass('expanded').html('▶');
            } else {
                $arrow.addClass('expanded').html('▼');
            }
            
            const $timeEntriesDiv = $(`.member-time-entries[data-user-id="${targetId}"][data-project-id="${projectId}"]`);
            
            if (isExpanded) {
                $timeEntriesDiv.slideUp(300);
                $(`.member-time-${targetId}`).slideUp(300);
            } else {
                $timeEntriesDiv.slideDown(300);
                $(`.member-time-${targetId}`).slideDown(300);
            }
        });
    }

    /**
     * Format date for display
     */
    formatDateForDisplay(dateString) {
        const dateFormat = $('#date_format').val();
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
}

// Make GanttChart globally available
window.GanttChart = GanttChart;
