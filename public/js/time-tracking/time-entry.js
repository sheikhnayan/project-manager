// Time entry management
class TimeEntryManager {
    constructor() {
        this.entries = [];
        this.currentWeek = utils.getWeekDates();
        this.editingCell = null;

        this.initializeEventListeners();
        this.render();
    }

    initializeEventListeners() {
        document.getElementById('add-entry').addEventListener('click', () => this.addEntry());
        document.getElementById('prev-week').addEventListener('click', () => this.changeWeek('prev'));
        document.getElementById('next-week').addEventListener('click', () => this.changeWeek('next'));
        document.getElementById('current-week').addEventListener('click', () => this.goToCurrentWeek());
        document.getElementById('copy-previous').addEventListener('click', () => this.copyFromPreviousWeek());

        // Delete confirmation modal
        document.getElementById('cancel-delete').addEventListener('click', () => this.hideDeleteModal());
        document.getElementById('confirm-delete').addEventListener('click', () => this.confirmDelete());
    }

    render() {
        this.updateDateRange();
        this.renderEntries();
    }

    updateDateRange() {
        const dateRange = document.getElementById('date-range');
        dateRange.textContent = utils.formatDateRange(this.currentWeek);
    }

    renderEntries() {
        const grid = document.getElementById('time-entries-grid');
        const headerContent = grid.innerHTML;  // Save the header content
        grid.innerHTML = headerContent;  // Restore header content

        this.entries.forEach((entry, index) => {
            const row = this.createEntryRow(entry, index);
            grid.appendChild(row);
        });

        // Initialize Lucide icons
        lucide.createIcons();
    }

    createEntryRow(entry, index) {
        const row = document.createElement('div');
        row.className = 'contents group transition-all duration-300 ease-out';
        row.setAttribute('data-entry-id', entry.id);

        // Add grip handle
        const gripCell = this.createGripCell();
        row.appendChild(gripCell);

        // Add project select
        const projectCell = this.createProjectCell(entry);
        row.appendChild(projectCell);

        // Add task select
        const taskCell = this.createTaskCell(entry);
        row.appendChild(taskCell);

        // Add time cells
        ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].forEach(day => {
            const timeCell = this.createTimeCell(entry, day);
            row.appendChild(timeCell);
        });

        // Add total cell
        const totalCell = this.createTotalCell(entry);
        row.appendChild(totalCell);

        // Add delete button
        const deleteCell = this.createDeleteCell(entry, index);
        row.appendChild(deleteCell);

        return row;
    }

    createGripCell() {
        const cell = document.createElement('div');
        cell.className = 'bg-white p-2 flex items-center justify-center cursor-grab active:cursor-grabbing hover:bg-accent/50 transition-all duration-200';
        cell.innerHTML = '<i data-lucide="grip-vertical" class="grip-handle"></i>';
        return cell;
    }

    createProjectCell(entry) {
        const cell = document.createElement('div');
        cell.className = 'bg-white p-1';
        const select = document.createElement('select');
        select.className = 'w-full h-10 rounded-md border-gray-300 focus:border-black focus:ring-black';
        select.innerHTML = `
            <option value=''>Select project...</option>
            ${projects.map(p => `
                <option value='${p.id}' ${p.id === entry.project ? 'selected' : ''}>
                    ${p.name}
                </option>
            `).join('')}
        `;

        select.addEventListener('change', (e) => {
            this.handleProjectChange(entry, e.target.value);
        });

        cell.appendChild(select);
        return cell;
    }

    createTaskCell(entry) {
        const cell = document.createElement('div');
        cell.className = 'bg-white p-1';
        const select = document.createElement('select');
        select.className = 'w-full h-10 rounded-md border-gray-300 focus:border-black focus:ring-black';
        select.disabled = !entry.project;

        const project = projects.find(p => p.id === entry.project);
        select.innerHTML = `
            <option value=''>Select task...</option>
            ${project ? project.tasks.map(t => `
                <option value='${t.id}' ${t.id === entry.task ? 'selected' : ''}>
                    ${t.name}
                </option>
            `).join('') : ''}
        `;

        select.addEventListener('change', (e) => {
            this.handleTaskChange(entry, e.target.value);
        });

        cell.appendChild(select);
        return cell;
    }

    createTimeCell(entry, day) {
        const cell = document.createElement('div');
        const isToday = utils.getTodayName() === day;
        cell.className = `bg-white p-1 ${isToday ? 'today-column' : ''}`;

        const timeCell = document.createElement('div');
        timeCell.className = 'time-cell';
        timeCell.draggable = true;
        timeCell.textContent = entry.hours[day];

        timeCell.addEventListener('click', () => {
            if (!timeCell.classList.contains('editing')) {
                const input = document.createElement('input');
                input.type = 'text';
                input.value = entry.hours[day];
                input.className = 'w-full h-full text-center border-none focus:outline-none focus:ring-2 focus:ring-black rounded';

                input.addEventListener('blur', () => {
                    this.handleTimeInput(entry, day, input.value);
                    timeCell.classList.remove('editing');
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        input.blur();
                    }
                });

                timeCell.textContent = '';
                timeCell.classList.add('editing');
                timeCell.appendChild(input);
                input.focus();
                input.select();
            }
        });

        timeCell.addEventListener('dragstart', (e) => {
            this.dragDropManager?.handleCellDragStart(timeCell, entry.hours[day], day, entry.id);
        });

        timeCell.addEventListener('dragend', () => {
            this.dragDropManager?.handleCellDragEnd(timeCell);
        });

        timeCell.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        timeCell.addEventListener('drop', (e) => {
            e.preventDefault();
            this.dragDropManager?.handleCellDrop(entry, day);
        });

        cell.appendChild(timeCell);
        return cell;
    }

    createTotalCell(entry) {
        const cell = document.createElement('div');
        cell.className = 'bg-white p-2 text-center font-medium';
        const total = utils.calculateTotal(entry.hours);
        cell.textContent = total > 0 ? total : '';
        return cell;
    }

    createDeleteCell(entry, index) {
        const cell = document.createElement('div');
        cell.className = 'bg-white p-2 flex items-center justify-center';

        const hasValues = Object.values(entry.hours).some(h => h !== '');
        const buttonHTML = `
            <button class='text-red-500 hover:text-red-700 transition-colors duration-200'>
                <i data-lucide='trash-2' class='w-4 h-4'></i>
            </button>
        `;

        cell.innerHTML = buttonHTML;
        cell.querySelector('button').addEventListener('click', () => {
            if (hasValues) {
                this.showDeleteModal(index);
            } else {
                this.deleteEntry(index);
            }
        });

        return cell;
    }

    addEntry() {
        this.entries.push(utils.createEmptyTimeEntry());
        this.render();
    }

    changeWeek(direction) {
        const firstDay = this.currentWeek[0];
        const newDate = new Date(firstDay);
        newDate.setDate(firstDay.getDate() + (direction === 'next' ? 7 : -7));
        this.currentWeek = utils.getWeekDates(newDate);
        this.render();
    }

    goToCurrentWeek() {
        this.currentWeek = utils.getWeekDates();
        this.render();
    }

    copyFromPreviousWeek() {
        const firstDay = this.currentWeek[0];
        const prevWeekDate = new Date(firstDay);
        prevWeekDate.setDate(firstDay.getDate() - 7);

        const prevWeekData = JSON.parse(localStorage.getItem(`timeEntries_${prevWeekDate.toISOString().slice(0, 10)}`) || '[]');
        if (prevWeekData.length > 0) {
            this.entries = prevWeekData.map(entry => ({
                ...entry,
                id: Date.now().toString() + Math.random().toString(36).substr(2, 9)
            }));
            this.saveEntries();
            this.render();
        }
    }

    handleProjectChange(entry, projectId) {
        entry.project = projectId;
        entry.task = ''; // Reset task when project changes

        // Find the task select in the correct row
        const row = document.querySelector(`[data-entry-id='${entry.id}']`);
        if (row) {
            const taskSelect = row.children[2].querySelector('select'); // Get the task select directly
            if (taskSelect) {
                const project = projects.find(p => p.id === projectId);
                taskSelect.disabled = !project;
                taskSelect.innerHTML = `
                    <option value=''>Select task...</option>
                    ${project ? project.tasks.map(t => `
                        <option value='${t.id}'>${t.name}</option>
                    `).join('') : ''}
                `;
            }
        }

        this.saveEntries();
    }

    handleTaskChange(entry, taskId) {
        entry.task = taskId;
        this.saveEntries();
        this.render();
    }

    handleTimeInput(entry, day, value) {
        const formattedValue = utils.parseTimeInput(value);
        entry.hours[day] = formattedValue;
        this.saveEntries();

        // Update the cell display without full re-render
        const row = document.querySelector(`[data-entry-id='${entry.id}']`);
        if (row) {
            const cells = row.querySelectorAll('.time-cell');
            const dayIndex = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].indexOf(day);
            if (cells[dayIndex]) {
                cells[dayIndex].textContent = formattedValue;
            }

            // Update total
            const totalCell = row.querySelector('.bg-white.p-2.text-center.font-medium');
            if (totalCell) {
                const total = utils.calculateTotal(entry.hours);
                totalCell.textContent = total > 0 ? total : '';
            }
        }
    }

    saveEntries() {
        const weekKey = this.currentWeek[0].toISOString().slice(0, 10);
        localStorage.setItem(`timeEntries_${weekKey}`, JSON.stringify(this.entries));
    }

    loadEntries() {
        const weekKey = this.currentWeek[0].toISOString().slice(0, 10);
        const savedEntries = localStorage.getItem(`timeEntries_${weekKey}`);
        this.entries = savedEntries ? JSON.parse(savedEntries) : [];
        this.render();
    }

    showDeleteModal(index) {
        if (index >= 0 && index < this.entries.length) {
            this.deleteIndex = index;
            const modal = document.getElementById('delete-confirmation');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }
    }

    hideDeleteModal() {
        const modal = document.getElementById('delete-confirmation');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        this.deleteIndex = null;
    }

    confirmDelete() {
        if (this.deleteIndex !== null) {
            this.deleteEntry(this.deleteIndex);
            this.hideDeleteModal();
        }
    }

    deleteEntry(index) {
        if (index >= 0 && index < this.entries.length) {
            this.entries.splice(index, 1);
            this.saveEntries();
            this.render();
        }
    }
}