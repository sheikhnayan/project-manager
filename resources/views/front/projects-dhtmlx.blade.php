
        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Project Gantt — DHTMLX</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" />
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <style>
        .gantt-wrapper { display: flex; height: calc(100vh - 64px); }
        .task-list-sidebar { width: 600px; border-right: 1px solid #e5e7eb; overflow: auto; background: #fff; }
        .gantt-container { flex: 1; overflow: auto; }
        .task-header { display: flex; background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
        .task-header span { font-size: 12px; padding: 14px 10px; border-right: 1px solid #eee; }
        .task-row { display: flex; align-items: center; height: 45px; border-bottom: 1px solid #eee; }
        .task-row > span { font-size: 12px; padding: 0 10px; border-right: 1px solid #eee; height: 100%; display: flex; align-items: center; }
        .task-row > span:last-child, .task-header span:last-child { border-right: none; }
        /* Ensure dhtmlx canvas stretches */
        #dhtmlx-gantt { width: 100%; height: 100%; min-width: 900px; }

        /* DHTMLX scale/header styling to match projects.blade.php */
        .gantt_grid_scale, .gantt_task .gantt_task_scale { background: #f9fafb; border-color: #e5e7eb; }
        .gantt_scale_cell { font-size: 12px; color: #6b7280; }
        /* Weekend shading (both header and timeline cells) */
        .gantt_scale_cell.weekend, .gantt_task_cell.weekend { background: #f3f4f6; }
        /* Today vertical marker */
        .gantt_marker.today .gantt_marker_content { background: rgba(59,130,246,.45); width: 2px; }
        .custom-today-line { background: rgba(59,130,246,.45); width:2px; position:absolute; top:0; bottom:0; z-index:10; }

        /* Time entry section */
        .time-entry-wrapper { display:flex; margin-top:8px; }
        .time-entry-sidebar { width:600px; border-right:1px solid #e5e7eb; background:#fff; }
        .time-entry-header { display:flex; background:#f9fafb; border-bottom:1px solid #e5e7eb; }
        .time-entry-header span { font-size:12px; padding:10px 10px; border-right:1px solid #eee; }
        .time-entry-header span:last-child { border-right:none; }
        .time-entry-calendar-header { display: flex; flex-direction: column; position: sticky; top: 0; z-index: 2; background: #fff; }
        .time-entry-calendar-header-row { display: flex; }
        .time-entry-calendar-header-cell { min-width: 24px; max-width: 24px; flex: 0 0 24px; text-align: center; font-size: 11px; border-right: 1px solid #f1f5f9; color: #6b7280; background: #f9fafb; }
        .time-entry-calendar-header-cell.month { font-weight: 600; font-size: 12px; background: #f3f4f6; border-bottom: 1px solid #e5e7eb; }
        .time-entry-calendar-header-cell.weekend { background: #f3f4f6; }
        .time-entry-calendar-header-cell.today-col { outline: 2px solid rgba(59,130,246,.6); outline-offset: -2px; }
        .time-entry-member-row { display:flex; align-items:center; height:34px; border-bottom:1px solid #eee; font-size:12px; }
        .time-entry-member-row span { padding:0 10px; display:flex; align-items:center; border-right:1px solid #eee; height:100%; }
        .time-entry-member-row span:last-child { border-right:none; }
        .time-entry-calendar-container { flex:1; overflow:auto; background:#fff; }
        .time-entry-scroll { position:relative; }
        .time-entry-row { display:flex; height:34px; }
        .time-entry-day { width:24px; min-width:24px; flex:0 0 24px; border-right:1px solid #f1f5f9; box-sizing:border-box; font-size:10px; text-align:center; line-height:34px; color:#333; background:#fff; padding:0; }
        .time-entry-day.weekend { background:#f3f4f6; }
        .time-entry-day.has-hours { background:#dbeafe; font-weight:600; color:#1e3a8a; }
        .time-entry-day.today-col { outline:2px solid rgba(59,130,246,.6); outline-offset:-2px; }
        .member-time-input { width:100%; height:100%; border:none; background:transparent; text-align:center; font-size:10px; color:#1e3a8a; font-weight:600; padding:0; }
        .member-time-input:disabled { background:transparent; color:#333; font-weight:400; }
        .time-entry-label { font-size:11px; color:#6b7280; margin:4px 10px; }
                /* --- Time Entry Input Styles from projects.blade.php --- */
        .calendar-day {
            width: 24px !important;
            height: 20px !important;
            text-align: center;
            border: 1px solid #ccc;
            box-sizing: border-box;
            display: inline-block;
            vertical-align: top;
            padding-top: 1px;
            font-size: 10px;
            margin: 0;
            border-top: unset;
        }
        .second-input .calendar-day {
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
        .second-input {
            display: flex;
        }
        .member-time-calendar-row {
            background-color: #f9fafb;
        }
        .inputss::-webkit-outer-spin-button,
        .inputss::-webkit-inner-spin-button,
        .inputsss::-webkit-outer-spin-button,
        .inputsss::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .inputss[type=number],
        .inputsss[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
        /* Hide number input arrows/spinners for all browsers */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
        /* Always show horizontal scrollbar for time entry scroll */
        .time-entry-scroll {
            overflow-x: auto !important;
            overflow-y: hidden;
            scrollbar-color: #cbd5e1 #f1f5f9;
            scrollbar-width: thin;
        }
        /* Grab cursor for drag scrolling */
        .gantt_task_bg,
        .gantt_task,
        .time-entry-scroll {
            cursor: grab;
        }
        .gantt_task_bg.grabbing,
        .gantt_task.grabbing,
        .time-entry-scroll.grabbing {
            cursor: grabbing;
            user-select: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="px-4 py-3 flex items-center justify-between bg-white border-b">
        <div class="flex items-center gap-2">
            <a href="/projects/{{ $data->id }}" class="text-sm px-3 py-2 rounded bg-gray-800 text-white">← Back</a>
            <span class="text-sm text-gray-600">DHTMLX Gantt</span>
        </div>
        <div></div>
    </div>

    <div class="gantt-wrapper">
        <div class="task-list-sidebar">
            <div class="task-header">
                <span style="width:50%">Title</span>
                <span style="width:25%" class="text-center">Date</span>
                <span style="width:25%" class="text-center">Budget</span>
            </div>
            @foreach ($data->tasks as $key => $item)
                @if ($item->start_date)
                <div class="task-row" data-task-id="{{ $item->id }}">
                    <span style="width:50%">{{ $item->title ?? $item->name ?? 'Task '.$item->id }}</span>
                    <span style="width:25%" class="justify-center">{{ $item->start_date }} → {{ $item->end_date }}</span>
                    <span style="width:25%" class="justify-center">{{ number_format($item->budget_total ?? 0) }}</span>
                </div>
                @endif
            @endforeach

            {{-- Time Entry Members Sidebar --}}
            <div class="time-entry-sidebar" style="margin-top:8px;">
                <div class="time-entry-header">
                    <span style="width:50%">Member</span>
                    <span style="width:25%" class="text-center">Total (hrs)</span>
                    <span style="width:25%" class="text-center">Last Entry</span>
                </div>
                @php
                    // Collect time entries per member (actual entries only)
                    $allTimeEntries = collect();
                    foreach ($data->members ?? [] as $m) {
                        $entries = DB::table('time_entries')
                            ->where('user_id', $m->user_id)
                            ->where('project_id', $data->id)
                            ->select('user_id','entry_date','hours')
                            ->orderBy('entry_date','asc')
                            ->get();
                        $allTimeEntries = $allTimeEntries->concat($entries);
                    }
                    $groupedTimeEntries = $allTimeEntries->groupBy('user_id')->map(function($userEntries){
                        return [
                            'dates' => $userEntries->groupBy('entry_date')->map(fn($d)=>$d->sum('hours')),
                            'total' => $userEntries->sum('hours'),
                            'last'  => optional($userEntries->sortByDesc('entry_date')->first())->entry_date
                        ];
                    });
                @endphp
                @foreach(($data->members ?? []) as $m)
                    @php $timeInfo = $groupedTimeEntries[$m->user_id] ?? null; @endphp
                    <div class="time-entry-member-row" data-user-id="{{ $m->user_id }}">
                        <span style="width:50%">{{ $m->user->name ?? ('User '.$m->user_id) }}</span>
                        <span style="width:25%" class="justify-center">{{ $timeInfo['total'] ?? 0 }}</span>
                        <span style="width:25%" class="justify-center">{{ $timeInfo['last'] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="gantt-container">
            <div id="dhtmlx-gantt"></div>
            {{-- Time Entry Calendar --}}
            {{-- <div class="time-entry-label">Time Entries</div> --}}
            <div class="time-entry-calendar-container">
                <div class="time-entry-calendar-header" id="timeEntryCalendarHeader"></div>
                <div class="time-entry-scroll" id="timeEntryScroll"></div>
            </div>
        </div>
    </div>

    <script>
        // DHTMLX setup
        // Match calendar look: Month + Day scales, 24px day cells
        gantt.config.date_format = "%Y-%m-%d";
        gantt.config.scales = [
            { unit: "month", step: 1, format: d => gantt.date.date_to_str("%M")(d) },
            { unit: "day", step: 1, format: d => gantt.date.date_to_str("%d")(d) }
        ];
        gantt.config.min_column_width = 24;
        gantt.config.scale_height = 52;

        // Weekend shading classes for header and timeline
        gantt.templates.scale_cell_class = function(date){
            var day = date.getDay();
            return (day === 0 || day === 6) ? "weekend" : "";
        };
        gantt.templates.task_cell_class = function(task, date){
            var day = date.getDay();
            return (day === 0 || day === 6) ? "weekend" : "";
        };
        
        // Use the newer template name to avoid deprecation warning
        gantt.templates.timeline_cell_class = function(task, date){
            var day = date.getDay();
            return (day === 0 || day === 6) ? "weekend" : "";
        };

        gantt.config.grid_width = 0; // hide built-in grid
        gantt.config.row_height = 45; // match sidebar rows
        gantt.config.autosize = true;
        gantt.config.fit_tasks = true;
        gantt.config.show_grid = false;
        gantt.init("dhtmlx-gantt");

        const tasks = [
            @foreach ($data->tasks as $item)
                @if ($item->start_date)
                {
                    id: {{ $item->id }},
                    text: @json($item->title ?? $item->name ?? ('Task ' . $item->id)),
                    start_date: '{{ $item->start_date }}',
                    end_date: '{{ $item->end_date ?? $item->start_date }}',
                },
                @endif
            @endforeach
        ];

        // DHTMLX expects a tasks structure
        const dataset = {
            data: tasks,
            links: []
        };

        gantt.parse(dataset);

        // Today marker with compatibility fallback
        (function addTodayMarker(){
            try {
                if (gantt.plugins) { gantt.plugins({ marker:true }); }
                if (typeof gantt.addMarker === 'function') {
                    gantt.addMarker({ start_date: new Date(), css: 'today', title: 'Today' });
                } else {
                    // Fallback: manual vertical line
                    const chartArea = document.querySelector('.gantt_layout_cell_chart .gantt_data_area');
                    if (!chartArea) return;
                    const today = new Date();
                    if (typeof gantt.posFromDate === 'function') {
                        const x = gantt.posFromDate(today);
                        const line = document.createElement('div');
                        line.className = 'custom-today-line';
                        line.style.left = x + 'px';
                        chartArea.appendChild(line);
                    }
                }
            } catch(e){ console.warn('Today marker fallback error:', e); }
        })();

        // Setup scroll sync after Gantt is fully rendered
        gantt.attachEvent("onGanttRender", function(){
            setupScrollSync();
        });

        function setupScrollSync() {
            // Always use .gantt_data_area for Gantt scroll
            const ganttScrollEl = document.querySelector('.gantt_data_area');
            const timeEntryScrollEl = document.getElementById('timeEntryScroll');
            const timeEntryHeaderEl = document.getElementById('timeEntryCalendarHeader');

            if (!ganttScrollEl) {
                console.warn('Could not find .gantt_data_area for Gantt scroll sync!');
                return;
            }
            if (!timeEntryScrollEl) {
                console.warn('Could not find #timeEntryScroll for scroll sync!');
                return;
            }

            let isSyncing = false;
            function syncScroll(sourceEl, targetEls) {
                if (isSyncing) return;
                isSyncing = true;
                targetEls.forEach(el => {
                    if (el && el.scrollLeft !== sourceEl.scrollLeft) {
                        el.scrollLeft = sourceEl.scrollLeft;
                    }
                });
                setTimeout(() => { isSyncing = false; }, 0);
            }

            ganttScrollEl.addEventListener('scroll', () => {
                syncScroll(ganttScrollEl, [timeEntryScrollEl, timeEntryHeaderEl]);
            });
            timeEntryScrollEl.addEventListener('scroll', () => {
                syncScroll(timeEntryScrollEl, [ganttScrollEl, timeEntryHeaderEl]);
            });
            if (timeEntryHeaderEl) {
                timeEntryHeaderEl.addEventListener('scroll', () => {
                    syncScroll(timeEntryHeaderEl, [ganttScrollEl, timeEntryScrollEl]);
                });
            }
        }

        // ---- Time Entry Calendar Build ----
        window.memberTimeEntries = @json($groupedTimeEntries ?? []);

        // Determine date range from tasks
        let minDate = null, maxDate = null;
        tasks.forEach(t => {
            const s = new Date(t.start_date);
            const e = new Date(t.end_date);
            if(!minDate || s < minDate) minDate = s;
            if(!maxDate || e > maxDate) maxDate = e;
        });
        if(!minDate) { minDate = new Date(); }
        if(!maxDate) { maxDate = new Date(minDate.getTime()); maxDate.setMonth(maxDate.getMonth()+1); }

        // Extend range slightly like original (1 year back, 10 years forward optional)
        const rangeStart = new Date(minDate.getTime()); rangeStart.setFullYear(rangeStart.getFullYear()-1);
        const rangeEnd = new Date(maxDate.getTime()); rangeEnd.setFullYear(rangeEnd.getFullYear()+1);

        const oneDayMs = 24*60*60*1000;
        const days = Math.ceil((rangeEnd - rangeStart)/oneDayMs);

        // --- Calendar Header (Month/Day) ---
        const headerDiv = document.getElementById('timeEntryCalendarHeader');
        // Month row
        let monthRow = document.createElement('div');
        monthRow.className = 'time-entry-calendar-header-row';
        let dayRow = document.createElement('div');
        dayRow.className = 'time-entry-calendar-header-row';
        let curMonth = null, monthSpan = 0;
        for(let i=0;i<days;i++) {
            const d = new Date(rangeStart.getTime() + i*oneDayMs);
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth()+1).padStart(2,'0');
            const dd = String(d.getDate()).padStart(2,'0');
            const key = `${yyyy}-${mm}-${dd}`;
            const weekend = (d.getDay()===0 || d.getDay()===6);
            const todayCheck = new Date();
            // Day cell
            let dayCell = document.createElement('div');
            dayCell.className = 'time-entry-calendar-header-cell';
            if(weekend) dayCell.classList.add('weekend');
            if(d.toDateString() === todayCheck.toDateString()) dayCell.classList.add('today-col');
            dayCell.textContent = dd;
            dayRow.appendChild(dayCell);
            // Month cell (span logic)
            if(!curMonth || d.getMonth() !== curMonth.getMonth() || d.getFullYear() !== curMonth.getFullYear()) {
                if(curMonth) {
                    let mcell = document.createElement('div');
                    mcell.className = 'time-entry-calendar-header-cell month';
                    mcell.style.width = (monthSpan*24)+"px";
                    mcell.style.minWidth = (monthSpan*24)+"px";
                    mcell.style.maxWidth = (monthSpan*24)+"px";
                    mcell.textContent = curMonth.toLocaleString('default', { month: 'short', year: 'numeric' });
                    monthRow.appendChild(mcell);
                }
                curMonth = new Date(d.getTime());
                monthSpan = 1;
            } else {
                monthSpan++;
            }
        }
        // Last month cell
        if(curMonth && monthSpan) {
            let mcell = document.createElement('div');
            mcell.className = 'time-entry-calendar-header-cell month';
            mcell.style.width = (monthSpan*24)+"px";
            mcell.style.minWidth = (monthSpan*24)+"px";
            mcell.style.maxWidth = (monthSpan*24)+"px";
            mcell.textContent = curMonth.toLocaleString('default', { month: 'short', year: 'numeric' });
            monthRow.appendChild(mcell);
        }
        headerDiv.appendChild(monthRow);
        headerDiv.appendChild(dayRow);

        // --- Time Entry Rows (with input fields) ---
        const timeEntryScroll = document.getElementById('timeEntryScroll');
        const memberRows = document.querySelectorAll('.time-entry-member-row');
        memberRows.forEach(row => {
            const userId = row.getAttribute('data-user-id');
            const dataMap = (window.memberTimeEntries[userId] && window.memberTimeEntries[userId].dates) ? window.memberTimeEntries[userId].dates : {};
            const timeRow = document.createElement('div');
            timeRow.className = 'time-entry-row';
            for(let i=0;i<days;i++) {
                const d = new Date(rangeStart.getTime() + i*oneDayMs);
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth()+1).padStart(2,'0');
                const dd = String(d.getDate()).padStart(2,'0');
                const key = `${yyyy}-${mm}-${dd}`;
                const weekend = (d.getDay()===0 || d.getDay()===6);
                const todayCheck = new Date();
                let dayEl = document.createElement('div');
                dayEl.className = 'time-entry-day';
                if(weekend) dayEl.classList.add('weekend');
                if(d.toDateString() === todayCheck.toDateString()) dayEl.classList.add('today-col');
                // Input field for time entry
                const inp = document.createElement('input');
                inp.type = 'number';
                inp.min = 1;
                inp.max = 8;
                inp.step = 1;
                inp.className = 'member-time-input';
                inp.setAttribute('data-user-id', userId);
                inp.setAttribute('data-date', key);
                inp.value = dataMap[key] || '';
                // Enable/disable logic (customize as needed)
                inp.disabled = false;
                // Event listeners for change/input with save logic
                inp.addEventListener('change', async function(e) {
                    await convertTimeInput(inp, userId, key);
                    inp.classList.toggle('has-hours', !!inp.value);
                });
                inp.addEventListener('input', function(e) {
                    // Only allow integer values between 1 and 8
                    let v = inp.value.replace(/[^0-9]/g, '');
                    if(v !== '' && (parseInt(v) < 1 || parseInt(v) > 8)) v = '';
                    inp.value = v;
                });
                if(inp.value) dayEl.classList.add('has-hours');
                dayEl.appendChild(inp);
                timeRow.appendChild(dayEl);
            }
            timeEntryScroll.appendChild(timeRow);
        });

        // ====== SAVING LOGIC ======
        
        // Time entry save function (matches convertTimeInput from projects.blade.php)
        async function convertTimeInput(inputElement, user_id, date) {
            const integerTime = parseInt(inputElement.value);
            const data = inputElement.value;
            const project_id = {{ $data->id }};

            if (isNaN(integerTime) || integerTime == 0) {
                inputElement.value = '';
                const data = 0;
                try {
                    const response = await fetch('/estimated-time-tracking/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ user_id, date, data, project_id }),
                    });
                    if (response.ok) {
                        const responseData = await response.json();
                        console.log('Time entry cleared successfully');
                    }
                } catch (error) {
                    console.error('Error saving data:', error);
                }
                return;
            }

            if (integerTime < 1 || integerTime > 8) {
                alert('Value is Invalid. Please enter a number between 1 and 8.');
                inputElement.value = '';
                return;
            }

            inputElement.value = integerTime;

            try {
                const response = await fetch('/estimated-time-tracking/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ user_id, date, data, project_id }),
                });
                if (response.ok) {
                    const responseData = await response.json();
                    console.log('Time entry saved successfully');
                }
            } catch (error) {
                console.error('Error saving data:', error);
            }
        }

        // DHTMLX Gantt drag and resize save handlers
        gantt.attachEvent("onAfterTaskDrag", function(id, mode, e){
            const task = gantt.getTask(id);
            saveTaskDates(id, task.start_date, task.end_date);
            return true;
        });

        gantt.attachEvent("onAfterTaskUpdate", function(id, task){
            saveTaskDates(id, task.start_date, task.end_date);
            return true;
        });

        // Save task dates to server
        function saveTaskDates(taskId, startDate, endDate) {
            const formatDate = (d) => {
                const date = typeof d === 'string' ? new Date(d) : d;
                return date.toISOString().split('T')[0];
            };
            
            const formattedStart = formatDate(startDate);
            const formattedEnd = formatDate(endDate);

            $.ajax({
                url: '/projects/save-dates',
                type: 'POST',
                data: {
                    stoppedStartDate: formattedStart,
                    stoppedEndDate: formattedEnd,
                    task_id: taskId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log('Task dates saved:', taskId, formattedStart, formattedEnd);
                    // Update sidebar date display
                    const taskRow = $(`.task-row[data-task-id="${taskId}"]`);
                    if(taskRow.length) {
                        taskRow.find('span:eq(1)').text(`${formattedStart} → ${formattedEnd}`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving task dates:', error);
                }
            });
        }

        // Format date for display (if needed)
        function formatDateForDisplay(dateStr) {
            const date = new Date(dateStr);
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const year = date.getFullYear();
            return `${month}/${day}/${year}`;
        }
    </script>
</body>
</html>
