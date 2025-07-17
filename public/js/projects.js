
document.addEventListener('DOMContentLoaded', function() {
    // Mock data for demonstration
    const projects = [
        {
            id: '1',
            name: 'Website Redesign',
            description: 'Complete overhaul of company website',
            tasks: [
                { id: '1', name: 'Design Phase', progress: 100, status: 'completed', group: 'Design' },
                { id: '2', name: 'Development', progress: 60, status: 'in-progress', group: 'Development' },
                { id: '3', name: 'Testing', progress: 20, status: 'in-progress', group: 'QA' }
            ],
            teamMembers: ['1', '2', '3']
        },
        {
            id: '2',
            name: 'Mobile App Development',
            description: 'Native mobile application for iOS and Android',
            tasks: [
                { id: '4', name: 'Requirements Gathering', progress: 100, status: 'completed', group: 'Planning' },
                { id: '5', name: 'UI Design', progress: 80, status: 'in-progress', group: 'Design' },
                { id: '6', name: 'API Development', progress: 40, status: 'in-progress', group: 'Development' }
            ],
            teamMembers: ['2', '3', '4']
        }
    ];

    const users = [
        { id: '1', name: 'John Doe' },
        { id: '2', name: 'Jane Smith' },
        { id: '3', name: 'Bob Wilson' },
        { id: '4', name: 'Alice Brown' }
    ];

    // Initialize project select
    const projectSelect = document.getElementById('project-select');
    projects.forEach(project => {
        const option = document.createElement('option');
        option.value = project.id;
        option.textContent = project.name;
        projectSelect.appendChild(option);
    });

    // Handle project selection
    projectSelect.addEventListener('change', function() {
        const projectId = this.value;
        const projectDetails = document.getElementById('project-details');
        
        if (!projectId) {
            projectDetails.classList.add('hidden');
            return;
        }

        const project = projects.find(p => p.id === projectId);
        if (!project) return;

        // Update project details
        document.getElementById('project-name').textContent = project.name;
        document.getElementById('project-description').textContent = project.description;
        
        // Show project details
        projectDetails.classList.remove('hidden');

        // Update timeline
        updateTimeline(project);

        // Update resource planning
        updateResourcePlanning(project);
    });

    function updateTimeline(project) {
        const container = document.getElementById('timeline-container');
        container.innerHTML = '';

        const taskGroups = project.tasks.reduce((acc, task) => {
            if (!acc[task.group]) acc[task.group] = [];
            acc[task.group].push(task);
            return acc;
        }, {});

        Object.entries(taskGroups).forEach(([group, tasks]) => {
            const groupDiv = document.createElement('div');
            groupDiv.className = 'mb-4';
            groupDiv.innerHTML = `
                <div class="font-medium mb-2">${group}</div>
                <div class="space-y-2">
                    ${tasks.map(task => `
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div>
                                <span>${task.name}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 rounded-full h-2" style="width: ${task.progress}%"></div>
                                </div>
                                <span>${task.progress}%</span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
            container.appendChild(groupDiv);
        });
    }

    function updateResourcePlanning(project) {
        const container = document.getElementById('resource-container');
        container.innerHTML = '';

        const teamMembers = users.filter(user => project.teamMembers.includes(user.id));
        
        teamMembers.forEach(user => {
            const userDiv = document.createElement('div');
            userDiv.className = 'flex items-center justify-between border-b pb-2';
            userDiv.innerHTML = `
                <div>
                    <div class="font-medium">${user.name}</div>
                    <div class="text-sm text-gray-600">Total Hours: 0</div>
                </div>
                <div class="grid grid-cols-7 gap-2">
                    ${Array(7).fill(0).map(() => `
                        <div class="h-8 w-8 rounded-full flex items-center justify-center bg-gray-100">
                            <span>-</span>
                        </div>
                    `).join('')}
                </div>
            `;
            container.appendChild(userDiv);
        });
    }
});
