<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Superadmin Control Panel</h1>

        <div class="flex items-center gap-4">
            <span class="text-gray-600 text-sm">Hello, <strong>{{ Auth::guard('superadmin')->user()->name }}</strong></span>

            <form action="{{ route('superadmin.logout') }}" method="POST" onsubmit="return confirm('Are you sure you want to log out?');">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 mt-8">

        @if(session('success'))
        <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-6 shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Welcome to your Dashboard</h2>
            <p class="text-gray-600 mb-6">From here, you will be able to register, view, and manage regular Admins for your PWA application.</p>

            <a href="{{ route('superadmin.admins.index') }}" class="inline-block bg-blue-600 text-white text-xs px-4 py-2 rounded hover:bg-blue-700">
                Go to Admins
            </a>
        </div>

    </main>

</body>

</html>