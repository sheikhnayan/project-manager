// Utility functions for time tracking
const utils = {
    formatTime(value) {
        if (!value) return "";
        const numericValue = parseFloat(value);
        if (isNaN(numericValue)) return "";
        return numericValue.toFixed(2);
    },

    parseTimeInput(value) {
        if (!value) return "";

        // Handle HH:MM format
        if (value.includes(":")) {
            const [hours, minutes] = value.split(":");
            const totalHours = parseInt(hours) + (parseInt(minutes) / 60);
            return this.formatTime(totalHours);
        }

        // Handle decimal format
        return this.formatTime(value);
    },

    calculateTotal(hours) {
        return Object.values(hours)
            .reduce((sum, val) => sum + (parseFloat(val) || 0), 0)
            .toFixed(2);
    },

    getWeekDates(date = new Date()) {
        const start = new Date(date);
        start.setDate(date.getDate() - date.getDay() + 1);
        return Array.from({ length: 7 }, (_, i) => {
            const day = new Date(start);
            day.setDate(start.getDate() + i);
            return day;
        });
    },

    formatDateRange(dates) {
        const start = dates[0].toLocaleDateString('en-US', {
            weekday: 'short',
            day: 'numeric',
            month: 'short'
        });
        const end = dates[6].toLocaleDateString('en-US', {
            weekday: 'short',
            day: 'numeric',
            month: 'short'
        });
        return `${start} → ${end}`;
    },

    getTodayName() {
        return new Date().toLocaleString('en-US', { weekday: 'short' });
    },

    createEmptyTimeEntry() {
        return {
            id: Date.now().toString(),
            project: '',
            task: '',
            hours: {
                Mon: '', Tue: '', Wed: '', Thu: '', Fri: '', Sat: '', Sun: ''
            }
        };
    }
};