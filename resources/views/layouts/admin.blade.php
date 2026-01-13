<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chatbot BPS</title>
    <!-- Use the same Tailwind setup as the landing page or CDN for simplicity in admin -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex flex-col w-64 bg-gray-900 border-r border-gray-800">
            <div class="flex items-center justify-center h-16 bg-gray-900 border-b border-gray-700">
                <span class="text-white font-bold uppercase">Admin BPS</span>
            </div>
            <div class="flex-1 flex flex-col overflow-y-auto">
                <nav class="flex-1 px-2 py-4 space-y-2">
                    @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            Dashboard
                        </a>
                    @endif

                    <a href="{{ route('admin.training.index') }}"
                        class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.training*') ? 'bg-gray-800 text-white' : '' }}">
                        <i class="fas fa-chart-line w-6"></i>
                        Training Monitor
                    </a>

                    <a href="{{ route('knowledge-base.index') }}"
                        class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors {{ request()->routeIs('knowledge-base*') ? 'bg-gray-800 text-white' : '' }}">
                        <i class="fas fa-brain w-6"></i>
                        Knowledge Base
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-gray-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-2 text-gray-300 hover:text-red-400 transition-colors">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between px-6 py-4 bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800 ml-4">@yield('title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ Auth::user()->name ?? 'Admin' }}</span>
                        @if(Auth::user()->isSuperAdmin())
                            <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full">Super Admin</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">Admin</span>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Main -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>