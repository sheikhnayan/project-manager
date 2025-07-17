<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Project Management</title>

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
    <link rel='stylesheet' href='{{asset('css/styles.css')}}'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-white">
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Reset Password</h1>
            </div>

            <div class="mt-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">

                                <div class="p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password:</label>
                                    <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border: 1px solid #eee">
                                    @error('password')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password:</label>
                                    <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border: 1px solid #eee">
                                </div>

                                <div>
                                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
</body>
</html>

