// Drag and drop functionality
class DragDropManager {
    constructor(timeEntryManager) {
        this.timeEntryManager = timeEntryManager;
        this.draggedEntry = null;
        this.draggedCell = null;

        this.initializeEventListeners();
    }

    initializeEventListeners() {
        document.addEventListener('mouseup', this.handleMouseUp.bind(this));

        document.addEventListener('mousemove', (e) => {
            if (this.draggedEntry) {
                const rows = Array.from(document.querySelectorAll('[data-entry-id]'));
                rows.forEach(row => {
                    const rect = row.getBoundingClientRect();
                    if (e.clientY > rect.top && e.clientY < rect.bottom) {
                        row.classList.add('row-dragging');
                    } else {
                        row.classList.remove('row-dragging');
                    }
                });
            }
        });

        document.querySelectorAll('.grip-handle').forEach(handle => {
            handle.addEventListener('mousedown', (e) => {
                const row = e.target.closest('[data-entry-id]');
                if (row) {
                    this.draggedEntry = this.timeEntryManager.entries.find(
                        entry => entry.id === row.dataset.entryId
                    );
                    document.body.style.cursor = 'grabbing';
                }
            });
        });
    }

    handleMouseUp(event) {
        if (this.draggedEntry) {
            const rows = Array.from(document.querySelectorAll('[data-entry-id]'));
            rows.forEach(row => {
                row.classList.remove('row-dragging');
                row.style.transform = '';
            });

            const newIndex = rows.findIndex(row => {
                const rect = row.getBoundingClientRect();
                return rect.top + rect.height / 2 > event.clientY;
            });

            if (newIndex !== -1 && newIndex !== this.timeEntryManager.entries.indexOf(this.draggedEntry)) {
                const currentIndex = this.timeEntryManager.entries.indexOf(this.draggedEntry);
                this.timeEntryManager.entries.splice(currentIndex, 1);
                this.timeEntryManager.entries.splice(newIndex, 0, this.draggedEntry);

                requestAnimationFrame(() => {
                    this.timeEntryManager.render();
                    const movedRow = document.querySelector(`[data-entry-id='${this.draggedEntry.id}']`);
                    if (movedRow) {
                        movedRow.classList.add('row-dragging');
                        setTimeout(() => movedRow.classList.remove('row-dragging'), 300);
                    }
                });
            }

            this.draggedEntry = null;
            document.body.style.cursor = '';
        }
    }

    handleCellDragStart(cell, value, day, entryId) {
        this.draggedCell = { value, day, entryId };
        cell.classList.add('cell-dragging');
    }

    handleCellDragEnd(cell) {
        cell.classList.remove('cell-dragging');
    }

    handleCellDrop(entry, day) {
        if (this.draggedCell && this.draggedCell.entryId === entry.id) {
            entry.hours[day] = this.draggedCell.value;
            this.timeEntryManager.render();
        }
        this.draggedCell = null;
    }
}