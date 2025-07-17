document.addEventListener('DOMContentLoaded', function() {
    // Mock data for statistics
    const stats = {
        monthlyHours: 145,
        lastMonthHours: 130,
        activeProjects: 8,
        completedProjects: 3,
        totalProjects: 12,
        activeTeamMembers: 15,
        newTeamMembers: 2
    };

    // Update statistics
    document.getElementById('total-hours').textContent = `${stats.monthlyHours}h`;
    const hoursChange = Math.round(((stats.monthlyHours - stats.lastMonthHours) / stats.lastMonthHours) * 100);
    document.getElementById('hours-change').textContent = `${hoursChange > 0 ? '+' : ''}${hoursChange}% from last month`;

    document.getElementById('active-projects').textContent = stats.activeProjects;
    document.getElementById('completed-projects').textContent = `${stats.completedProjects} completed this month`;

    document.getElementById('team-members').textContent = stats.activeTeamMembers;
    document.getElementById('new-members').textContent = 
        `${stats.newTeamMembers > 0 ? `+${stats.newTeamMembers}` : 'No'} new this month`;

    const completionRate = Math.round((stats.completedProjects / stats.totalProjects) * 100);
    document.getElementById('completion-rate').textContent = `${completionRate}%`;
    document.getElementById('completion-stats').textContent = 
        `${stats.completedProjects} of ${stats.totalProjects} projects completed`;

    // Mock data for recent items
    const recentProjects = [
        { name: 'Website Redesign', client: 'Acme Corp' },
        { name: 'Mobile App Development', client: 'TechStart' },
        { name: 'E-commerce Platform', client: 'RetailPlus' }
    ];

    const timeEntries = [
        { project: 'Website Redesign', task: 'Homepage Design', hours: 3.5 },
        { project: 'Mobile App Development', task: 'API Integration', hours: 2.0 },
        { project: 'E-commerce Platform', task: 'Cart Implementation', hours: 4.0 }
    ];

    // Render recent projects
    const projectsContainer = document.getElementById('recent-projects');
    recentProjects.forEach(project => {
        const div = document.createElement('div');
        div.className = 'border-b pb-4 last:border-0 last:pb-0';
        div.innerHTML = `
            <div class='flex justify-between items-start'>
                <div>
                    <h3 class='font-medium'>${project.name}</h3>
                    <p class='text-sm text-gray-600'>${project.client}</p>
                </div>
                <a href='/projects.html' class='text-sm text-black hover:text-gray-700'>
                    View Project →
                </a>
            </div>
        `;
        projectsContainer.appendChild(div);
    });

    // Render time entries
    const entriesContainer = document.getElementById('time-entries');
    timeEntries.forEach(entry => {
        const div = document.createElement('div');
        div.className = 'border-b pb-4 last:border-0 last:pb-0';
        div.innerHTML = `
            <div class='flex justify-between items-start'>
                <div>
                    <h3 class='font-medium'>${entry.project}</h3>
                    <p class='text-sm text-gray-600'>${entry.task}</p>
                </div>
                <span class='text-sm font-medium'>
                    ${entry.hours.toFixed(2)} hours
                </span>
            </div>
        `;
        entriesContainer.appendChild(div);
    });

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
});