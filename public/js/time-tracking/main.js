// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();

    // Initialize user dropdown
    const userSelect = document.getElementById('user-select');
    users.forEach(user => {
        const option = document.createElement('option');
        option.value = user.id;
        option.textContent = user.name;
        userSelect.appendChild(option);
    });

    // Initialize time entry manager
    const timeEntryManager = new TimeEntryManager();
    timeEntryManager.loadEntries();

    // Initialize drag and drop manager
    const dragDropManager = new DragDropManager(timeEntryManager);
    timeEntryManager.dragDropManager = dragDropManager;

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

    // Make toggleSettings available globally
    window.toggleSettings = toggleSettings;
});