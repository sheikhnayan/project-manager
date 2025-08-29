<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
<style>
    body {
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
    }
</style>
@php
    $setting = \App\Models\Setting::first();
@endphp
<nav class='bg-white border-b border-gray-200'>
    <div class='px-4' style="width: 100%">
        <div class='flex justify-between h-16'>
            <div class='flex items-center space-x-8'>
                <div class='flex items-center'>
                    <a href='/' class='text-md font-bold text-gray-900 ml-2'>
                    <img src="{{ asset('storage/'.$setting->logo) }}" style="width: 35.5px; height: 35.5px;">
                    </a>
                </div>
                <div class='flex items-center space-x-4 navv'>
                    <a href='/' class='nav-link {{ Route::currentRouteName() == 'index' ? 'active':''}}'>
                        <i data-lucide='layout-grid' class='w-4 h-4 mr-5'></i>
                        <span>Dashboard</span>
                    </a>
                    <a href='/time-tracking' class='nav-link {{ Route::currentRouteName() == 'time-tracking' ? 'active':''}}'>
                        <i data-lucide='clock' class='w-4 h-4 mr-5'></i>
                        <span>Time Tracking</span>
                    </a>
                    <a href='/projects' class='nav-link {{ Route::currentRouteName() == 'projects' ? 'active':''}}'>
                        <i data-lucide='folder' class='w-4 h-4 mr-5'></i>
                        <span>Projects</span>
                    </a>
                    {{-- <a href='/plan' class='nav-link {{ Route::currentRouteName() == 'plan' ? 'active':''}}'>
                        <i data-lucide='calendar' class='w-4 h-4 mr-5'></i>
                        <span>Plan</span>
                    </a> --}}
                    <a href='/resources' class='nav-link {{ Route::currentRouteName() == 'resources' ? 'active':''}}'>
                        <i data-lucide='users' class='w-4 h-4 mr-5'></i>
                        <span>Resources</span>
                    </a>
                    <a href='/reports' class='nav-link {{ Route::currentRouteName() == 'reports' ? 'active':''}}'>
                        <i data-lucide='bar-chart-2' class='w-4 h-4 mr-5'></i>
                        <span>Reports</span>
                    </a>
                </div>
            </div>
            <div class='flex items-center space-x-4'>
                @auth
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->role_id && Auth::user()->userRole)
                            <span class="text-gray-400">•</span>
                            <span>{{ Auth::user()->userRole->display_name ?? Str::title(str_replace('_', ' ', Auth::user()->role)) }}</span>
                        @endif
                    </div>
                @endauth
                <div class='relative'>
                    <button class='p-2 rounded-full hover:bg-gray-100' onclick='toggleSettings(event)'>
                        <i data-lucide='settings' class='w-5 h-5'></i>
                    </button>
                    <div class='settings-dropdown' id='settingsDropdown'>
                        <a href='/user-management'>User Management</a>
                        <a href='/client-management'>Client Management</a>
                        <a href='/project-management'>Project Management</a>
                        <a href='/settings'>Settings</a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                {{-- <i data-lucide='log-out' class='w-4 h-4 mr-2 inline'></i> --}}
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
