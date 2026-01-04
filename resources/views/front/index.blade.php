<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Date-fns -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/date-fns/2.30.0/date-fns.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('/css/styles.css')}}'>
</head>
<body class="bg-gray-50">

    @include('front.nav')

    <main class="py-6">
        <div class="mx-auto px-4">
            <h1 class="text-3xl font-bold">Dashboard</h1>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mt-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-medium">Total Hours</h3>
                        <i data-lucide="clock" class="h-4 w-4 text-gray-500"></i>
                    </div>
                    <div>
                        @php
                            $user = auth()->user();
                            $companyFilter = ($user->role_id != 8 && $user->company_id) ? ['company_id' => $user->company_id] : [];
                            
                            $total_hours = DB::table('time_entries');
                            if (!empty($companyFilter)) {
                                $total_hours->where($companyFilter);
                            }
                            $total_hours = $total_hours->sum('hours');
                            
                            $last_month_hours = DB::table('time_entries')
                                ->whereMonth('created_at', now()->subMonth()->month)
                                ->whereYear('created_at', now()->subMonth()->year);
                            if (!empty($companyFilter)) {
                                $last_month_hours->where($companyFilter);
                            }
                            $last_month_hours = $last_month_hours->sum('hours');
                            
                            $hours_change = 0;
                            if ($last_month_hours > 0) {
                                $hours_change = (($total_hours - $last_month_hours) / $last_month_hours) * 100;
                            } elseif ($total_hours > 0) {
                                $hours_change = 100;
                            }
                        @endphp
                        <div class="text-2xl font-bold" id="total-hours">{{ round($total_hours) }}h</div>
                        <p class="text-xs text-gray-500" id="hours-change">
                            {{ $hours_change >= 0 ? '+' : '' }}{{ round($hours_change) }}% from last month
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-medium">Active Projects</h3>
                        <i data-lucide="calendar" class="h-4 w-4 text-gray-500"></i>
                    </div>
                    <div>
                        @php
                            $project = DB::table('projects')->where('is_archived',0);
                            if (!empty($companyFilter)) {
                                $project->where($companyFilter);
                            }
                            $project = $project->count();
                            
                            $completed_projects_this_month = DB::table('projects')
                                ->where('is_archived', 1)
                                ->whereMonth('updated_at', now()->month)
                                ->whereYear('updated_at', now()->year);
                            if (!empty($companyFilter)) {
                                $completed_projects_this_month->where($companyFilter);
                            }
                            $completed_projects_this_month = $completed_projects_this_month->count();
                        @endphp
                        <div class="text-2xl font-bold" id="active-projects">{{ $project }}</div>
                        <p class="text-xs text-gray-500" id="completed-projects">{{ $completed_projects_this_month }} completed this month</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-medium">Team Members</h3>
                        <i data-lucide="users" class="h-4 w-4 text-gray-500"></i>
                    </div>
                    <div>
                        @php
                            $users = DB::table('users')->where('role','!=','admin');
                            if (!empty($companyFilter)) {
                                $users->where($companyFilter);
                            }
                            $users = $users->count();
                            
                            $new_members_this_month = DB::table('users')
                                ->where('role', '!=', 'admin')
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year);
                            if (!empty($companyFilter)) {
                                $new_members_this_month->where($companyFilter);
                            }
                            $new_members_this_month = $new_members_this_month->count();
                        @endphp
                        <div class="text-2xl font-bold" id="team-members">{{ $users }}</div>
                        <p class="text-xs text-gray-500" id="new-members">
                            {{ $new_members_this_month > 0 ? $new_members_this_month . ' new this month' : 'No new this month' }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-medium">Completion Rate</h3>
                        <i data-lucide="bar-chart-2" class="h-4 w-4 text-gray-500"></i>
                    </div>
                    <div>
                        @php
                            $es = DB::table('estimated_time_entries');
                            if (!empty($companyFilter)) {
                                $es->where($companyFilter);
                            }
                            $es = $es->sum('hours');
                            
                            $aes = DB::table('time_entries');
                            if (!empty($companyFilter)) {
                                $aes->where($companyFilter);
                            }
                            $aes = $aes->sum('hours');

                            $a = ($aes/($es > 0 ? $es : 1))*100;
                            $a = round($a);

                            // Calculate completed projects
                            $total_projects = DB::table('projects');
                            if (!empty($companyFilter)) {
                                $total_projects->where($companyFilter);
                            }
                            $total_projects = $total_projects->count();
                            
                            $completed_projects = DB::table('projects')->where('is_archived', 1);
                            if (!empty($companyFilter)) {
                                $completed_projects->where($companyFilter);
                            }
                            $completed_projects = $completed_projects->count();
                        @endphp
                        <div class="text-2xl font-bold" id="completion-rate">{{ $a }}%</div>
                        <p class="text-xs text-gray-500" id="completion-stats">{{ $completed_projects }} of {{ $total_projects }} projects completed</p>
                    </div>
                </div>
            </div>

            <!-- My Dashboard Section -->
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">My dashboard</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- My week at a glance -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">My week at a glance</h3>
                        @php
                            $weekStart = now()->startOfWeek();
                            $weekEnd = now()->endOfWeek();
                            
                            $plannedHours = DB::table('estimated_time_entries')
                                ->where('user_id', $user->id)
                                ->whereBetween('entry_date', [$weekStart, $weekEnd])
                                ->sum('hours');
                            
                            $loggedHours = DB::table('time_entries')
                                ->where('user_id', $user->id)
                                ->whereBetween('created_at', [$weekStart, $weekEnd])
                                ->sum('hours');
                            
                            $balance = $loggedHours - $plannedHours;
                        @endphp
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold">{{ round($plannedHours) }} h</div>
                                <div class="text-xs text-gray-500">Planned hours</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">{{ round($loggedHours) }} h</div>
                                <div class="text-xs text-gray-500">Logged hours</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold" style="color: {{ $balance < 0 ? '#ef4444' : '#10b981' }}">{{ $balance >= 0 ? '' : '' }}{{ round($balance) }} h</div>
                                <div class="text-xs text-gray-500">Balance</div>
                            </div>
                        </div>
                    </div>

                    <!-- My active projects -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">My active projects</h3>
                        @php
                            $myProjects = DB::table('project_team_members')
                                ->join('projects', 'project_team_members.project_id', '=', 'projects.id')
                                ->where('project_team_members.user_id', $user->id)
                                ->where('projects.is_archived', 0)
                                ->select('projects.id', 'projects.name', 'projects.project_number')
                                ->limit(3)
                                ->get();
                            
                            $weekStart = now()->startOfWeek();
                            $weekEnd = now()->endOfWeek();
                        @endphp
                        <div class="space-y-2">
                            @forelse($myProjects as $proj)
                                @php
                                    $projectHours = DB::table('time_entries')
                                        ->join('tasks', 'time_entries.task_id', '=', 'tasks.id')
                                        ->where('time_entries.user_id', $user->id)
                                        ->where('tasks.project_id', $proj->id)
                                        ->whereBetween('time_entries.created_at', [$weekStart, $weekEnd])
                                        ->sum('time_entries.hours');
                                @endphp
                                <div class="flex justify-between items-center text-sm">
                                    <span>{{ $proj->project_number }} {{ $proj->name }}</span>
                                    <span class="font-semibold">{{ round($projectHours) }} h this week</span>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500">No active projects</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- My time tracking status -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">My time tracking status</h3>
                        @php
                            $thisWeek = now()->week;
                            $thisYear = now()->year;
                            
                            $daysLoggedThisWeek = DB::table('time_entries')
                                ->where('user_id', $user->id)
                                ->whereRaw('WEEK(created_at, 1) = ?', [$thisWeek])
                                ->whereYear('created_at', $thisYear)
                                ->selectRaw('COUNT(DISTINCT DATE(created_at)) as count')
                                ->value('count');
                            
                            $totalWorkDays = 5; // Mon-Fri
                            $today = now();
                            $currentDayOfWeek = $today->dayOfWeek;
                            
                            // Calculate how many work days have passed (Mon=1, Fri=5, Sat=6, Sun=0)
                            $workDaysPassed = $currentDayOfWeek == 0 ? 5 : ($currentDayOfWeek == 6 ? 5 : $currentDayOfWeek);
                            
                            $lastLogDate = DB::table('time_entries')
                                ->where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->value('created_at');
                        @endphp
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="text-2xl font-bold">{{ $daysLoggedThisWeek }}/{{ $totalWorkDays }}</div>
                                    <div class="text-sm text-gray-600">Days logged</div>
                                    <div class="text-xs text-gray-500 mt-1">This week</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">Yesterday</div>
                                    <div class="text-sm text-gray-600">Last logged</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">Mon, Tue,</div>
                                    <div class="text-sm text-gray-600">Missing</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My time off -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">My time off</h3>
                        @php
                            $holidaysAllowed = $user->holidays_allowed ?? 20;
                            $holidaysUsed = DB::table('user_holidays')
                                ->where('user_id', $user->id)
                                ->whereYear('holiday_date', now()->year)
                                ->count();
                            $holidaysRemaining = $holidaysAllowed - $holidaysUsed;
                            
                            $totalDaysInYear = 365;
                            $daysUnspent = DB::table('user_holidays')
                                ->where('user_id', $user->id)
                                ->where('holiday_date', '>', now())
                                ->count();
                        @endphp
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <div class="text-2xl font-bold">{{ $holidaysAllowed }}</div>
                                <div class="text-xs text-gray-500">Earned</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">{{ $holidaysUsed }}</div>
                                <div class="text-xs text-gray-500">Spent</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">{{ $holidaysRemaining }}</div>
                                <div class="text-xs text-gray-500"></div>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-gray-500">
                            Total days for the year {{ $holidaysAllowed }}, unspent from last year 0
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">Recent Projects</h2>
                        <div class="space-y-4" id="recent-projects"></div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">Today's Time Entries</h2>
                        <div class="space-y-4" id="time-entries"></div>
                    </div>
                </div>
            </div> --}}

            <!-- Team Time Off Section -->
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">Team time off</h2>
                <div style="background: #fff !important; border: 1px solid #D1D5DB; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15); border-radius: 8px; overflow: hidden;">
                    <div style="display: flex;">
                        <!-- Team Members Column -->
                        <div class="task-list" style="width: 600px; background-color: #f7fafc; border-right: 1px solid #ccc; border-radius: 4px 0 0 0;">
                            <div class="task-header" style="display: flex; justify-content: space-between; align-items: center; font-weight: bold; background-color: #000; color: white; padding: 10px; height: 52px; margin-bottom: 0;">
                                <span style="width: 100%; font-size: 12px; cursor: pointer; display: inherit; padding-left: 10px;">
                                    Team Member ↕
                                </span>
                            </div>
                            @php
                                $teamMembers = \App\Models\User::where('is_archived', 0);
                                if ($user->role_id != 8 && $user->company_id) {
                                    $teamMembers->where('company_id', $user->company_id);
                                }
                                $teamMembers = $teamMembers->get();
                            @endphp
                            @foreach($teamMembers as $member)
                            <div class="task-item team-member-row" data-user-id="{{ $member->id }}" style="padding: 10px; position: relative; display: flex; align-items: center; margin-bottom: 0px; border-bottom: 1px solid #eee; margin-left: 0px; background: #fff; height: 30px; padding-right: 0px;">
                                <span style="width: 100%; font-size: 12px; cursor: pointer; display: inherit;">
                                    @if($member->profile_image_url)
                                        <img src="{{ asset('storage/' . $member->profile_image_url) }}" alt="{{ $member->name }}" style="width: 20px; height: 20px; border-radius: 50%; display: inline-block; margin-right: 8px; vertical-align: middle;">
                                    @endif
                                    {{ $member->name }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Calendar Scroll Container -->
                        <div class="scroll-container" id="team-calendar-scroll" style="flex: 1; overflow-x: auto; overflow-y: hidden; cursor: grab; border-radius: 0 4px 0 0;">
                            <div class="calendar-container" id="team-calendar-container" style="display: flex; white-space: nowrap; position: relative;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Team Calendar
        $(document).ready(function() {
            renderTeamCalendar();

            // Drag to scroll functionality
            const scrollContainer = document.getElementById('team-calendar-scroll');
            let isDown = false;
            let startX;
            let scrollLeft;

            scrollContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                scrollContainer.style.cursor = 'grabbing';
                startX = e.pageX - scrollContainer.offsetLeft;
                scrollLeft = scrollContainer.scrollLeft;
            });

            scrollContainer.addEventListener('mouseleave', () => {
                isDown = false;
                scrollContainer.style.cursor = 'grab';
            });

            scrollContainer.addEventListener('mouseup', () => {
                isDown = false;
                scrollContainer.style.cursor = 'grab';
            });

            scrollContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offsetLeft;
                const walk = (x - startX) * 2;
                scrollContainer.scrollLeft = scrollLeft - walk;
            });

            // Mouse wheel horizontal scroll
            scrollContainer.addEventListener('wheel', (e) => {
                e.preventDefault();
                scrollContainer.scrollLeft += e.deltaY;
            });
        });

        function renderTeamCalendar() {
            const calendarContainer = $('#team-calendar-container');
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();
            
            // Show 3 months: current month and 2 months ahead
            const monthsToShow = 3;
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const teamMemberCount = {{ $teamMembers->count() }};

            // Fetch all team holidays
            $.ajax({
                url: '/team-holidays',
                method: 'GET',
                success: function(response) {
                    const teamHolidays = response.holidays || {};
                    renderCalendarWithHolidays(teamHolidays);
                },
                error: function() {
                    renderCalendarWithHolidays({});
                }
            });

            function renderCalendarWithHolidays(teamHolidays) {
                calendarContainer.empty();

                for (let m = 0; m < monthsToShow; m++) {
                    const monthDate = new Date(currentYear, currentMonth + m, 1);
                    const month = monthDate.getMonth();
                    const year = monthDate.getFullYear();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    
                    // Create month container
                    const monthContainer = $('<div class="month-container" style="display: inline-block; vertical-align: top;"></div>');
                    
                    // Add month header
                    const monthHeader = $(`<div class="month-header" style="display: block; width: ${daysInMonth * 32}px; margin: 0; padding: 0; font-size: 14px; background-color: #000 !important; color: white; text-align: center; border-bottom: 1px solid #e5e7eb; height: 21px; line-height: 21px; border-right: 1px solid #fff;">${monthNames[month]}</div>`);
                    monthContainer.append(monthHeader);
                    
                    // Create date row
                    const dateRow = $('<div style="display: block; white-space: nowrap;"></div>');
                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(year, month, day);
                        const dayOfWeek = date.getDay();
                        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
                        const dateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                        
                        const dayNumber = $(`<div class="calendar-day" data-date="${dateStr}" style="display: inline-block; width: 32px !important; min-width: 32px !important; max-width: 32px !important; height: 30px !important; text-align: center; border: 1px solid #ccc; box-sizing: border-box; vertical-align: top; padding-top: 6px; font-size: 10px; margin: 0; border-top: unset; flex-shrink: 0; border-right: 1px solid #eee; border-top: 1px solid #eee; border-bottom: unset; border-left: unset; ${isWeekend ? 'background-color: #fce4ec !important; color: #e91e63 !important;' : ''}">${day}</div>`);
                        dateRow.append(dayNumber);
                    }
                    monthContainer.append(dateRow);
                    
                    // Create rows for each team member
                    @foreach($teamMembers as $member)
                    const memberRow_{{ $member->id }} = $('<div style="display: block; white-space: nowrap;"></div>');
                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(year, month, day);
                        const dayOfWeek = date.getDay();
                        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
                        const dateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                        
                        const hasHoliday = teamHolidays['{{ $member->id }}'] && teamHolidays['{{ $member->id }}'].includes(dateStr);
                        const cell = $(`<div style="display: inline-block; width: 32px !important; min-width: 32px !important; max-width: 32px !important; height: 30px !important; text-align: center; border: 1px solid #ccc; box-sizing: border-box; vertical-align: top; font-size: 10px; margin: 0; border-top: unset; flex-shrink: 0; border-right: 1px solid #eee; border-top: 1px solid #eee; border-bottom: unset; border-left: unset; ${isWeekend ? 'background-color: #f9fafb !important;' : ''} ${hasHoliday ? 'background-color: #d1d5db !important;' : ''}"></div>`);
                        memberRow_{{ $member->id }}.append(cell);
                    }
                    monthContainer.append(memberRow_{{ $member->id }});
                    @endforeach
                    
                    calendarContainer.append(monthContainer);
                }
            }
        }

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
    </script>
    {{-- <script src="{{asset('/js/app.js')}}"></script> --}}

</body>
</html>
