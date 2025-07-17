
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan - Project Management</title>

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
</head>
<body class="bg-gray-50">
    @include('front.nav')

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Plan</h1>
                <div class="flex items-center gap-4">
                    <select class="w-[280px] rounded-md border-gray-300 focus:border-black focus:ring-black">
                        <option value="">Select a project...</option>
                    </select>
                    <button class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add Task
                    </button>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Project Timeline</h2>
                        <div class="flex items-center gap-2">
                            <button class="p-2 rounded hover:bg-gray-100">
                                <i data-lucide="zoom-in" class="w-4 h-4"></i>
                            </button>
                            <button class="p-2 rounded hover:bg-gray-100">
                                <i data-lucide="zoom-out" class="w-4 h-4"></i>
                            </button>
                            <button class="p-2 rounded hover:bg-gray-100">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="h-[500px] border rounded-lg"></div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
