@extends('layouts.admin')

@section('title', 'Student List')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ 
    searchQuery: '', 
    statusFilter: 'All', 
    ageFilter: 'All',
    showFilters: false,
    
    // Core function to check if a student record passes active search criteria
    matches(name, id, guardian, status, age) {
        const query = this.searchQuery.toLowerCase().trim();
        const matchesSearch = !query || 
                              name.toLowerCase().includes(query) || 
                              id.toLowerCase().includes(query) || 
                              guardian.toLowerCase().includes(query);
        
        const matchesStatus = this.statusFilter === 'All' || status.toLowerCase() === this.statusFilter.toLowerCase();
        
        let matchesAge = true;
        if (this.ageFilter !== 'All') {
            if (this.ageFilter === 'Under 10') matchesAge = age < 10;
            if (this.ageFilter === '10-12') matchesAge = age >= 10 && age <= 12;
            if (this.ageFilter === 'Age 13') matchesAge = age === 13;
        }
        
        return matchesSearch && matchesStatus && matchesAge;
    },
    
    resetFilters() {
        this.searchQuery = '';
        this.statusFilter = 'All';
        this.ageFilter = 'All';
    }
}">
    <div class="mb-4 mt-2">
        <h2 class="text-4xl font-bold text-[#2A2421] serif-title tracking-tight">Student List</h2>
        <p class="text-sm font-medium text-amber-900/60 mt-1">Active Student Management</p>
    </div>

    <div class="mb-6">
        <a href="{{ route('admin.students.create') }}" class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-md transition">
            + Add New Student
        </a>
    </div>
    <div class="bg-white border border-dashed border-orange-200 rounded-2xl p-4 shadow-3xs mb-6">
        <div class="flex justify-between items-center mb-2.5">
            <h3 class="text-xs font-black text-orange-700 uppercase tracking-wider">📁 Bulk Import Roster (CSV)</h3>
            
            <button onclick="downloadCSVTemplate()" class="text-[11px] font-black text-orange-600 hover:text-orange-700 hover:underline flex items-center gap-1 cursor-pointer">
                📥 Download Excel/CSV Template
            </button>
        </div>
        
        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-700 p-3 rounded-xl mb-3 text-[11px] font-bold">
                <p class="mb-1 text-rose-800">Some entries were skipped:</p>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.students.bulk-upload') }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
            @csrf
            <input type="file" name="bulk_file" accept=".csv" required 
                   class="flex-grow bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-black file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-2xs transition whitespace-nowrap cursor-pointer">
                Upload
            </button>
        </form>
        <p class="text-[9px] text-slate-400 font-medium mt-1.5">
            💡 CSV Column Sequence: <span class="font-mono bg-slate-100 text-slate-600 px-1 py-0.5 rounded">student_name, date_of_birth (YYYY-MM-DD), guardian_name, mobile_number, status (active/inactive), remarks</span>
        </p>
    </div>

    <script>
        function downloadCSVTemplate() {
            // Define the explicit columns array mapping your backend data constraints
            const headers = ["student_name", "date_of_birth", "guardian_name", "mobile_number", "status", "remarks"];
            
            // Add two perfect baseline sample rows to guide data entry format (showing under-13 ages)
            const sampleRow1 = ["Aarav Jain", "2016-05-12", "Nitin Jain", "9876543210", "active", "Regular attendee"];
            const sampleRow2 = ["Ananya Jain", "2018-11-23", "Amit Jain", "9812345678", "active", ""];
            
            // Compile lines cleanly adding true quotes sanitizer encapsulation formatting blocks
            let csvContent = [
                headers.join(","),
                sampleRow1.map(val => `"${val}"`).join(","),
                sampleRow2.map(val => `"${val}"`).join(",")
            ].join("\n");
            
            // Convert template string into an immediate dynamic browser data download blob action
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", "Student_Bulk_Upload_Template.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <div class="grid grid-cols-3 gap-2.5 mb-6">
        <div class="bg-white border border-orange-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-sm">
            <div class="w-8 h-8 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 text-sm mb-1">👥</div>
            <span class="text-[10px] font-bold text-slate-500 leading-tight">Total Active Students</span>
            <span class="text-2xl font-extrabold text-orange-500 serif-title mt-0.5">{{ $totalActive }}</span>
        </div>

        <div class="bg-white border border-emerald-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-sm">
            <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 text-sm mb-1">🎓</div>
            <span class="text-[10px] font-bold text-slate-500 leading-tight">New Students This Year</span>
            <span class="text-2xl font-extrabold text-emerald-500 serif-title mt-0.5">{{ $newThisYear }}</span>
        </div>

        <div class="bg-white border border-purple-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-sm">
            <div class="w-8 h-8 bg-purple-50 rounded-full flex items-center justify-center text-purple-500 text-sm mb-1">🆔</div>
            <span class="text-[10px] font-bold text-slate-500 leading-tight">Available Reusable IDs</span>
            <span class="text-2xl font-extrabold text-purple-600 serif-title mt-0.5">{{ $totalReusableCount }}</span>
        </div>
    </div>

    <div class="space-y-3 mb-6">
        <div class="flex gap-2">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">🔍</span>
                <input x-model="searchQuery" type="text" placeholder="Search by name, ID, or guardian..." class="w-full bg-white pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <button @click="showFilters = !showFilters" :class="showFilters ? 'bg-orange-600 text-white' : 'bg-white text-orange-500 border border-orange-500'" class="text-sm font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 transition shadow-sm">
                <span>⏳</span> Filter
            </button>
        </div>

        <div x-show="showFilters" x-transition class="bg-orange-50/50 p-3 rounded-2xl border border-orange-100 grid grid-cols-2 gap-3 text-xs mb-2">
            <div>
                <label class="block font-bold text-slate-600 mb-1">Roster Status</label>
                <select x-model="statusFilter" class="w-full bg-white border border-slate-200 p-2 rounded-xl focus:outline-none">
                    <option value="All">All Statuses</option>
                    <option value="Active">Active Only</option>
                    <option value="Inactive">Inactive Only</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Age Matrix Category</label>
                <select x-model="ageFilter" class="w-full bg-white border border-slate-200 p-2 rounded-xl focus:outline-none">
                    <option value="All">All Ages</option>
                    <option value="Under 10">Under 10</option>
                    <option value="10-12">Ages 10 - 12</option>
                    <option value="Age 13">Age 13 Ceiling</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 items-center text-xs font-bold text-orange-700">
            <span class="bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-lg">
                Status: <span x-text="statusFilter" class="text-orange-900 font-extrabold"></span>
            </span>
            <span class="bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-lg">
                Age Group: <span x-text="ageFilter" class="text-orange-900 font-extrabold"></span>
            </span>
            
            <button @click="resetFilters()" class="text-orange-600/60 text-xs ml-auto hover:underline font-semibold">Reset</button>
        </div>
    </div>

    <div class="space-y-3 mb-6">
        @forelse($students as $student)
            @php 
                $paddedId = str_pad($student->id, 3, '0', STR_PAD_LEFT);
            @endphp
            <div x-show="matches('{{ $student->student_name }}', '{{ $paddedId }}', '{{ $student->guardian_name }}', '{{ $student->status }}', {{ $student->age }})"
                 class="bg-white border border-orange-100 rounded-2xl p-3 flex items-center justify-between shadow-sm relative transition-all duration-200 hover:border-orange-200">
                
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-amber-100 border border-amber-200 flex items-center justify-center text-xl overflow-hidden font-bold text-amber-800">
                        👦🏻
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-base text-slate-800 leading-tight">{{ $student->student_name }}</h4>
                        <span class="text-xs font-bold text-rose-500 block mt-0.5">ID: {{ $paddedId }}</span>
                        
                        <div class="text-[11px] text-slate-500 mt-1 space-y-0.5">
                            <p>Age: {{ $student->age }} Years</p>
                            <p>Guardian: {{ $student->guardian_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($student->age >= 12)
                        <span class="bg-orange-50 border border-orange-200 text-orange-700 text-[10px] font-bold px-2.5 py-1 rounded-full text-center max-w-[110px] leading-tight shadow-2xs">
                            Will be removed on 31 Dec
                        </span>
                    @else
                        <span class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full shadow-2xs">
                            {{ ucfirst($student->status) }}
                        </span>
                    @endif

                    <a href="{{ route('admin.students.edit', $student->id) }}" class="text-slate-400 p-2 hover:text-slate-600 text-lg font-bold transition">
                        ⋮
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-slate-200 p-8 rounded-2xl text-center text-sm text-slate-400">
                No students registered within the system directory database.
            </div>
        @endforelse
    </div>

    <div class="mt-4 mb-6 px-1">
        {{ $students->links('pagination::tailwind') }}
    </div>

    <div class="border-t border-slate-100 pt-4 mt-6">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-bold text-slate-800 text-sm">Available Reusable IDs</h3>
            <span class="text-xs font-bold text-orange-500">Track Allocation Deck</span>
        </div>
        
        <div class="flex gap-2 overflow-x-auto pb-2">
            @forelse($reusableIdsDeck as $reusedId)
                <span class="border border-orange-200 bg-orange-50/50 text-orange-700 font-black px-4 py-1.5 rounded-xl text-sm shadow-2xs">
                    {{ str_pad($reusedId, 3, '0', STR_PAD_LEFT) }}
                </span>
            @empty
                <span class="text-xs text-slate-400 italic">No historical IDs available for recycling yet.</span>
            @endforelse
        </div>
    </div>
</div>
@endsection