<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-title { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FFFDF9] text-slate-800 pb-24 md:pb-6">

    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 px-4 py-3 flex justify-between items-center border-b border-orange-100">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center text-white font-bold text-lg">🛕</div>
            <span class="text-xl font-black text-orange-600 tracking-tight">Jin Pooja Seva</span>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="w-9 h-9 rounded-full bg-orange-200 border-2 border-orange-400 overflow-hidden shadow-inner cursor-pointer">
                @if(Auth::guard('admin')->user()->profile_image)
                    <img src="{{ asset('storage/' . Auth::guard('admin')->user()->profile_image) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-orange-700 font-bold text-sm">A</div>
                @endif
            </div>
        </div>
    </header>

    <main class="max-w-md mx-auto px-4 py-6 md:max-w-4xl lg:max-w-6xl">
        @yield('content')
    </main>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-100 px-3 py-2 flex justify-around items-center z-50 shadow-[0_-4px_12px_rgba(0,0,0,0.03)] md:max-w-md md:mx-auto md:rounded-t-2xl">
        
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ Route::is('admin.dashboard') ? 'text-orange-600' : 'text-slate-400 hover:text-slate-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.students.index') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ Route::is('admin.students.*') ? 'text-orange-600' : 'text-slate-400 hover:text-slate-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Students</span>
        </a>

        <a href="{{ route('admin.attendance.index') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ Route::is('admin.attendance.*') ? 'text-orange-600' : 'text-slate-400 hover:text-slate-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Attendance</span>
        </a>

        <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ Route::is('admin.reports.*') ? 'text-orange-600' : 'text-slate-400 hover:text-slate-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
            </svg>
            <span>Reports</span>
        </a>

        <form action="{{ route('admin.logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex flex-col items-center gap-1 text-xs font-semibold text-slate-400 hover:text-red-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Logout</span>
        </a>
    </nav>

</body>
</html>