// Time tracking functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mock data for demonstration
    const users = [
        { id: '1', name: 'John Doe' },
        { id: '2', name: 'Jane Smith' },
        { id: '3', name: 'Bob Wilson' }
    ];

    const projects = [
        { id: '1', name: 'Website Redesign', tasks: [
            { id: '1', name: 'Frontend Development' },
            { id: '2', name: 'Backend Integration' }
        ]},
        { id: '2', name: 'Mobile App', tasks: [
            { id: '3', name: 'UI Design' },
            { id: '4', name: 'API Development' }
        ]}
    ];

    let currentWeek = getWeekDates();
    let timeEntries = [];
    let draggedEntry = null;
    let draggedCell = null;

    // Initialize user select
    const userSelect = document.getElementById('user-select');
    users.forEach(user => {
        const option = document.createElement('option');
        option.value = user.id;
        option.textContent = user.name;
        userSelect.appendChild(option);
    });

    // Week navigation
    document.getElementById('prev-week').addEventListener('click', () => changeWeek('prev'));
    document.getElementById('next-week').addEventListener('click', () => changeWeek('next'));
    document.getElementById('current-week').addEventListener('click', goToCurrentWeek);
    document.getElementById('add-entry').addEventListener('click', addNewEntry);
    document.getElementById('copy-previous').addEventListener('click', copyFromPreviousWeek);

    function getWeekDates(date = new Date()) {
        const start = new Date(date);
        start.setDate(date.getDate() - date.getDay() + 1);
        return Array.from({ length: 7 }, (_, i) => {
            const day = new Date(start);
            day.setDate(start.getDate() + i);
            return day;
        });
    }

    function formatDateRange() {
        const start = currentWeek[0].toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });
        const end = currentWeek[6].toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });
        document.getElementById('date-range').textContent = `${start} → ${end}`;
    }

    function changeWeek(direction) {
        const firstDay = currentWeek[0];
        const newDate = new Date(firstDay);
        newDate.setDate(firstDay.getDate() + (direction === 'next' ? 7 : -7));
        currentWeek = getWeekDates(newDate);
        formatDateRange();
        renderTimeEntries();
    }

    function goToCurrentWeek() {
        currentWeek = getWeekDates();
        formatDateRange();
        renderTimeEntries();
    }

    function addNewEntry() {
        const entry = {
            id: Date.now().toString(),
            project: '',
            task: '',
            hours: {
                Mon: '', Tue: '', Wed: '', Thu: '', Fri: '', Sat: '', Sun: ''
            }
        };
        timeEntries.push(entry);
        renderTimeEntries();
    }

    function copyFromPreviousWeek() {
        // Implementation for copying from previous week
    }

    // Update renderTimeEntries function
    function renderTimeEntries() {
        const grid = document.getElementById('time-entries-grid');
        const header = grid.innerHTML.split('</div>')[0] + '</div>';
        grid.innerHTML = header;

        timeEntries.forEach((entry, index) => {
            const row = document.createElement('div');
            row.className = 'contents group transition-all duration-300 ease-out';
            row.setAttribute('data-entry-id', entry.id);

            // Enhanced grip handle with larger size and better visibility
            const gripCell = document.createElement('div');
            gripCell.className = 'bg-white p-2 flex items-center justify-center cursor-grab active:cursor-grabbing hover:bg-accent/50 transition-all duration-200';
            gripCell.innerHTML = '<i data-lucide=\'grip-vertical\' class=\'grip-handle\'></i>';

            gripCell.addEventListener('mousedown', () => {
                draggedEntry = entry;
                row.classList.add('row-dragging');
                document.body.style.cursor = 'grabbing';
            });

            row.appendChild(gripCell);

            // Project select
            const projectCell = document.createElement('div');
            projectCell.className = 'bg-white p-1';
            projectCell.innerHTML = `
                <select class="w-full h-10 rounded-md border-gray-300 focus:border-black focus:ring-black">
                    <option value="">Select project...</option>
                    ${projects.map(p => `
                        <option value="${p.id}" ${p.id === entry.project ? 'selected' : ''}>
                            ${p.name}
                        </option>
                    `).join('')}
                </select>
            `;

            // Task select
            const taskCell = document.createElement('div');
            taskCell.className = 'bg-white p-1';
            taskCell.innerHTML = `
                <select class="w-full h-10 rounded-md border-gray-300 focus:border-black focus:ring-black" ${!entry.project ? 'disabled' : ''}>
                    <option value="">Select task...</option>
                    ${entry.project ? projects.find(p => p.id === entry.project)?.tasks.map(t => `
                        <option value="${t.id}" ${t.id === entry.task ? 'selected' : ''}>
                            ${t.name}
                        </option>
                    `).join('') : ''}
                </select>
            `;

            row.appendChild(projectCell);
            row.appendChild(taskCell);

            // Enhanced time cell styling and drag animation
            ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].forEach(day => {
                const cell = document.createElement('div');
                const today = new Date().toLocaleString('en-US', { weekday: 'short' }) === day;
                cell.className = `bg-white p-1 ${today ? 'today-column' : ''}`;
                cell.innerHTML = `
                    <div class='time-cell'
                         draggable='true'>
                        ${entry.hours[day]}
                    </div>
                `;

                const timeCell = cell.firstElementChild;
                timeCell.addEventListener('dragstart', (e) => {
                    draggedCell = { value: entry.hours[day], fromDay: day, entryId: entry.id };
                    timeCell.classList.add('cell-dragging');
                });

                timeCell.addEventListener('dragend', () => {
                    timeCell.classList.remove('cell-dragging');
                });

                cell.addEventListener('dragover', (e) => e.preventDefault());
                cell.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (draggedCell && draggedCell.entryId === entry.id) {
                        entry.hours[day] = draggedCell.value;
                        renderTimeEntries();
                    }
                });

                row.appendChild(cell);
            });

            // Total
            const totalCell = document.createElement('div');
            totalCell.className = 'bg-white p-2 text-center font-medium';
            const total = Object.values(entry.hours)
                .reduce((sum, val) => sum + (parseFloat(val) || 0), 0)
                .toFixed(2);
            totalCell.textContent = total > 0 ? total : '';
            row.appendChild(totalCell);

            // Enhanced delete button with confirmation
            const deleteCell = document.createElement('div');
            deleteCell.className = 'bg-white p-2 flex items-center justify-center';
            const hasValues = Object.values(entry.hours).some(h => h !== '');
            
            if (hasValues) {
                deleteCell.innerHTML = `
                    <button class='text-red-500 hover:text-red-700 transition-colors duration-200' 
                            onclick='confirmDelete(${index})'>
                        <i data-lucide='trash-2' class='w-4 h-4'></i>
                    </button>
                `;
            } else {
                deleteCell.innerHTML = `
                    <button class='text-red-500 hover:text-red-700 transition-colors duration-200'
                            onclick='deleteEntry(${index})'>
                        <i data-lucide='trash-2' class='w-4 h-4'></i>
                    </button>
                `;
            }

            row.appendChild(deleteCell);

            grid.appendChild(row);
        });

        // Initialize Lucide icons
        lucide.createIcons();
    }

    // Add confirmation dialog function
    function confirmDelete(index) {
        if (confirm('This entry contains time values. Are you sure you want to delete it?')) {
            deleteEntry(index);
        }
    }

    // Initialize
    formatDateRange();
    renderTimeEntries();

    // Enhanced drag and drop animation
    document.addEventListener('mouseup', () => {
        if (draggedEntry) {
            const rows = Array.from(document.querySelectorAll('[data-entry-id]'));
            rows.forEach(row => {
                row.classList.remove('row-dragging');
                row.style.transform = '';
            });
            
            const newIndex = rows.findIndex(row => {
                const rect = row.getBoundingClientRect();
                return rect.top + rect.height / 2 > event.clientY;
            });

            if (newIndex !== -1 && newIndex !== timeEntries.indexOf(draggedEntry)) {
                const currentIndex = timeEntries.indexOf(draggedEntry);
                timeEntries.splice(currentIndex, 1);
                timeEntries.splice(newIndex, 0, draggedEntry);
                
                // Smooth animation for reordering
                requestAnimationFrame(() => {
                    renderTimeEntries();
                    const movedRow = document.querySelector(`[data-entry-id='${draggedEntry.id}']`);
                    if (movedRow) {
                        movedRow.classList.add('row-dragging');
                        setTimeout(() => movedRow.classList.remove('row-dragging'), 300);
                    }
                });
            }
            
            draggedEntry = null;
            document.body.style.cursor = '';
        }
    });
});