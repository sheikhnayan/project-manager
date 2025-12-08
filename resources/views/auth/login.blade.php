<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Management</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Tailwind CSS -->
    <script src='https://cdn.tailwindcss.com'></script>

    <!-- Alpine.js -->
    <script defer src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js'></script>

    <!-- Lucide Icons -->
    <script src='https://unpkg.com/lucide@latest'></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .login-gradient {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            position: relative;
            overflow: hidden;
        }
        
        .login-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveGrid 20s linear infinite;
        }
        
        @keyframes moveGrid {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50px, 50px);
            }
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 15s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }
        
        .shape1 {
            width: 300px;
            height: 300px;
            background: white;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape2 {
            width: 200px;
            height: 200px;
            background: white;
            bottom: 15%;
            right: 15%;
            animation-delay: 3s;
        }
        
        .shape3 {
            width: 150px;
            height: 150px;
            background: white;
            top: 60%;
            left: 20%;
            animation-delay: 6s;
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-hover::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-hover:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in {
            animation: slideIn 0.8s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">
    <div class="flex h-screen">
        <!-- Left Panel - Dynamic Gradient Background -->
        <div class="hidden lg:flex lg:w-1/2 login-gradient relative items-center justify-center">
            <div class="floating-shape shape1"></div>
            <div class="floating-shape shape2"></div>
            <div class="floating-shape shape3"></div>
            
            <div class="relative z-10 text-white px-12 slide-in">
                <div class="mb-8">
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="layout-dashboard" class="w-10 h-10 text-gray-900"></i>
                    </div>
                    <h1 class="text-5xl font-bold mb-4">Project Management</h1>
                    <p class="text-xl text-gray-300">Streamline your workflow and boost productivity</p>
                </div>
                
                <div class="space-y-4 mt-12">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-lg">Manage projects efficiently</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-lg">Track time and progress</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-lg">Collaborate with your team</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md fade-in">
                <!-- Logo for mobile -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="w-16 h-16 bg-gray-900 rounded-lg flex items-center justify-center mb-4 mx-auto">
                        <i data-lucide="layout-dashboard" class="w-10 h-10 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Project Management</h2>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
                    <p class="text-gray-600">Sign in to continue to your account</p>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                        <div class="flex items-center">
                            <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus
                                class="input-focus block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                placeholder="you@example.com"
                            >
                        </div>
                        @error('email')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                required
                                class="input-focus block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                placeholder="Enter your password"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-5 h-5 text-gray-400 hover:text-gray-600"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-gray-900 hover:text-gray-700">
                                Forgot password?
                            </a>
                        @endif
                    </div> --}}

                    <button 
                        type="submit" 
                        class="btn-hover w-full py-3 px-4 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 relative"
                    >
                        <span class="relative z-10 flex items-center justify-center">
                            Sign In
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        © {{ date('Y') }} Project Management. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // Re-initialize icons when Alpine updates the DOM
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => {
                lucide.createIcons();
            }, 100);
        });

        // Watch for password visibility toggle
        document.addEventListener('click', function() {
            setTimeout(() => {
                lucide.createIcons();
            }, 50);
        });
    </script>
</body>
</html>


