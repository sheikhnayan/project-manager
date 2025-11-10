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

    <!-- Custom CSS -->
    <link rel='stylesheet' href='{{asset('/css/styles.css')}}'>
</head>
<body class="bg-gray-50">

    @include('front.nav')

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
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
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

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
