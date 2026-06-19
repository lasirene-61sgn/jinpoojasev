@extends('layouts.admin')

@section('title', 'Admin Dashboard Summary')

@section('content')
    <!-- Dashboard Page Title Header -->
    <div class="mb-6 mt-2">
        <h2 class="text-5xl font-bold text-[#2A2421] serif-title tracking-tight">Dashboard</h2>
        <p class="text-sm font-medium text-amber-900/60 mt-1">Sunday Worship Attendance Summary</p>
    </div>

    <!-- Quick Stat Cards Layer (Matches image_ef7a82.png) -->
    <div class="grid grid-cols-3 gap-3 mb-6">
        <!-- Total Students Card -->
        <div class="bg-white border border-orange-100 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-xs">
            <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 mb-2">👥</div>
            <span class="text-[11px] font-bold text-slate-500 tracking-tight leading-tight">Total Students</span>
            <span class="text-3xl font-extrabold text-orange-500 serif-title mt-1">{{ $totalStudents }}</span>
        </div>

        <!-- Present Today Card -->
        <div class="bg-white border border-emerald-100 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-xs">
            <div class="w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mb-2">✅</div>
            <span class="text-[11px] font-bold text-slate-500 tracking-tight leading-tight">Present Today</span>
            <span class="text-3xl font-extrabold text-emerald-500 serif-title mt-1">{{ $totalPresentAggregate }}</span>
        </div>

        <!-- Absent Card -->
        <div class="bg-white border border-rose-100 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-xs">
            <div class="w-10 h-10 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 mb-2">🚫</div>
            <span class="text-[11px] font-bold text-slate-500 tracking-tight leading-tight">Absent</span>
            <span class="text-3xl font-extrabold text-rose-500 serif-title mt-1">{{ $absentToday }}</span>
        </div>
    </div>

    <!-- Layout Container for Secondary Detail Blocks -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <!-- Today's Summary Card Component -->
        <div class="bg-white border border-orange-100/70 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xl text-orange-500">📅</span>
                <h3 class="text-lg font-bold text-slate-800">Today's Summary</h3>
            </div>
            <p class="text-xs font-semibold text-rose-500 mb-4">{{ \Carbon\Carbon::today()->format('l, d F Y') }}</p>
            
            <div class="space-y-3 border-b border-dashed border-slate-100 pb-4 mb-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2 text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Present
                    </span>
                    <span class="font-bold text-slate-800">{{ $totalPresentAggregate }} <span class="text-xs font-normal text-slate-400 ml-1">{{ $presentPercentage }}%</span></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2 text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Absent
                    </span>
                    <span class="font-bold text-slate-800">{{ $absentToday }} <span class="text-xs font-normal text-slate-400 ml-1">{{ $absentPercentage }}%</span></span>
                </div>
            </div>

            <div class="flex justify-between items-center text-slate-800 text-sm font-medium">
                <span class="font-bold text-slate-500">Total Students</span>
                <span class="text-xl font-extrabold text-slate-800 serif-title">{{ $totalStudents }}</span>
            </div>
        </div>

        <!-- Attendance Distribution Chart Component -->
        <div class="bg-white border border-orange-100/70 rounded-2xl p-5 shadow-sm flex flex-col items-center">
            <div class="w-full flex items-center gap-2 mb-4">
                <span class="text-xl text-orange-500">📊</span>
                <h3 class="text-lg font-bold text-slate-800">Attendance Distribution</h3>
            </div>

            <!-- CSS Conic Gradient Donut Representation mapping dynamic percentages -->
            <div class="relative w-36 h-36 rounded-full flex items-center justify-center shadow-inner" 
                 style="background: conic-gradient(#10b981 0% {{ $presentPercentage }}%, #f43f5e {{ $presentPercentage }}% 100%);">
                <div class="w-24 h-24 bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                    <span class="text-2xl font-black text-slate-800">{{ $totalStudents }}</span>
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total</span>
                </div>
            </div>

            <div class="flex gap-4 mt-4 text-xs font-semibold">
                <span class="flex items-center gap-1.5 text-slate-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Present ({{ $presentPercentage }}%)
                </span>
                <span class="flex items-center gap-1.5 text-slate-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Absent ({{ $absentPercentage }}%)
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Attendance Log Module -->
    <div class="bg-white border border-orange-100/70 rounded-2xl p-5 shadow-sm">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl text-orange-500">📅</span>
                <h3 class="text-lg font-bold text-slate-800">Recent Attendance</h3>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="text-orange-500 hover:text-orange-600 font-extrabold text-sm tracking-tight">View All</a>
        </div>

        <div class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
            @forelse($recentHistory as $history)
                <div class="py-3 flex justify-between items-center first:pt-0 last:pb-0">
                    <div class="text-slate-500 font-semibold w-1/3">
                        {{ \Carbon\Carbon::parse($history->attendance_date)->format('l, d F Y') }}
                    </div>
                    <div class="flex gap-4 justify-center w-1/3 text-center">
                        <span class="text-emerald-600 font-bold">{{ $history->p_count + $history->l_count }} Present</span>
                        <span class="text-rose-500 font-bold">{{ $history->a_count }} Absent</span>
                    </div>
                    <div class="text-right font-black text-slate-800 w-1/3">
                        {{ $history->p_count + $history->l_count + $history->a_count }} Total
                    </div>
                </div>
            @empty
                <div class="text-center text-slate-400 py-4 italic">No attendance records found yet.</div>
            @endforelse
        </div>
    </div>
@endsection