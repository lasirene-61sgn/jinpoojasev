@extends('layouts.admin')

@section('title', 'Mark Attendance')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ 
    searchQuery: '',
    selectedStudent: null,
    selectedStatus: 'present',
    remarks: '',
    selectedTime: '',

    // Inject properties instantly into the top action card component on item tap
    selectStudent(student) {
        this.selectedStudent = student;
        this.selectedStatus = student.currentStatus || 'present';
        this.remarks = student.currentRemarks || '';
        this.selectedTime = student.currentLogTime || '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}">
    <div class="mb-4 mt-2">
        <h2 class="text-4xl font-bold text-[#2A2421] serif-title tracking-tight">Mark Attendance</h2>
        <p class="text-sm font-medium text-amber-900/60 mt-0.5">Search by ID or Name</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-800 p-3.5 rounded-2xl mb-4 text-xs font-bold shadow-2xs">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">🔍</span>
        <input type="text" placeholder="Enter Student ID / Name" x-model="searchQuery"
               class="w-full bg-white pl-9 pr-4 py-3 border border-orange-500 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 shadow-2xs">
        <p class="text-[11px] text-orange-600 font-semibold mt-1.5 flex items-center gap-1 px-1">
            <span>ℹ️</span> Search a student by ID or name to load actions.
        </p>
    </div>

    <div class="bg-white border border-orange-100 rounded-2xl p-4 shadow-sm mb-6" x-show="selectedStudent" x-transition>
        <form action="{{ route('admin.attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" :value="selectedStudent.id">
            <input type="hidden" name="status" :value="selectedStatus">

            <div class="bg-amber-50/40 border border-orange-100/60 rounded-xl p-3 flex flex-col gap-2 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-orange-100 border border-orange-200 flex items-center justify-center text-xl shadow-2xs">👦🏻</div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 flex-grow">
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm tracking-tight" x-text="selectedStudent.name"></h4>
                            <span class="text-[11px] font-bold text-orange-600 block mt-0.5">ID: <span x-text="'JPJ' + String(selectedStudent.id).padStart(5, '0')"></span></span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-bold space-y-0.5">
                            <p>📅 Age: <span x-text="selectedStudent.age"></span> Years</p>
                            <p>👥 Guard: <span x-text="selectedStudent.guardian"></span></p>
                        </div>
                    </div>
                </div>

                <template x-if="selectedTime">
                    <div class="border-t border-orange-100/60 pt-1.5 mt-0.5 flex items-center gap-1 text-[10px] font-semibold text-amber-900/60">
                        <span>🕒 Log Status Timeline:</span>
                        <span class="text-orange-700 font-bold" x-text="'Saved on ' + selectedTime"></span>
                    </div>
                </template>
            </div>

            <div class="grid grid-cols-3 gap-2 mb-4">
                <button type="button" @click="selectedStatus = 'present'"
                        :class="selectedStatus === 'present' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-emerald-600 border-emerald-600 border'"
                        class="py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-1 transition-all duration-150 cursor-pointer">
                    ✅ Present
                </button>
                
                <button type="button" @click="selectedStatus = 'late'"
                        :class="selectedStatus === 'late' ? 'bg-orange-500 text-white border-orange-500 shadow-md' : 'bg-white text-orange-500 border-orange-500 border'"
                        class="py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-1 transition-all duration-150 cursor-pointer">
                    ⏰ Late
                </button>

                <button type="button" @click="selectedStatus = 'absent'"
                        :class="selectedStatus === 'absent' ? 'bg-rose-600 text-white border-rose-600 shadow-md' : 'bg-white text-rose-600 border-rose-600 border'"
                        class="py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-1 transition-all duration-150 cursor-pointer">
                    ❌ Absent
                </button>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-600 mb-1">Remark (Optional)</label>
                <textarea name="remarks" x-model="remarks" rows="2" placeholder="Enter remark (optional)"
                          class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-orange-400 placeholder-slate-400 text-slate-700"></textarea>
            </div>

            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 rounded-xl shadow-md text-sm flex items-center justify-center gap-2 transition cursor-pointer">
                💾 Save Attendance Record
            </button>
        </form>
    </div>

    <div class="mb-6">
        <h3 class="text-base font-black text-orange-600 mb-3 tracking-tight">Student List</h3>
        
        <div class="space-y-2">
            @foreach($students as $student)
                @php
                    $log = $attendanceLogs->get($student->id);
                    $currentStatus = $log ? $log->status : 'absent';
                    $paddedId = 'JPJ' . str_pad($student->id, 5, '0', STR_PAD_LEFT);
                    
                    // Format the timestamp explicitly matching your locale setup rules
                    $formattedTime = $log ? \Carbon\Carbon::parse($log->updated_at)->timezone('Asia/Kolkata')->format('d M, h:i A') : '';
                @endphp
                
                <div @click="selectStudent({ 
                        id: {{ $student->id }}, 
                        name: '{{ $student->student_name }}', 
                        age: {{ $student->age }}, 
                        guardian: '{{ $student->guardian_name }}',
                        currentStatus: '{{ $log ? $log->status : 'absent' }}',
                        currentRemarks: '{{ $log ? $log->remarks : '' }}',
                        currentLogTime: '{{ $formattedTime }}'
                     })"
                     x-show="!searchQuery || '{{ strtolower($student->student_name) }}'.includes(searchQuery.toLowerCase()) || '{{ $student->id }}'.includes(searchQuery) || '{{ strtolower($paddedId) }}'.includes(searchQuery.toLowerCase())"
                     :class="selectedStudent && selectedStudent.id === {{ $student->id }} ? 'border-orange-500 bg-orange-50/20' : 'border-slate-100 bg-white'"
                     class="border rounded-2xl p-3 flex items-center justify-between shadow-2xs cursor-pointer hover:border-orange-200 transition duration-150">
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-50 border border-amber-100 rounded-full flex items-center justify-center text-lg shadow-3xs">🧑🏼</div>
                        <div>
                            <h5 class="font-bold text-sm text-slate-800 leading-tight">{{ $student->student_name }}</h5>
                            <span class="text-[10px] font-bold text-slate-400 block mt-0.5">ID: {{ $paddedId }}</span>
                        </div>
                    </div>

                    <div class="text-right flex flex-col items-end gap-0.5">
                        <div class="flex items-center gap-1 text-xs font-bold">
                            @if($log && $log->status === 'present')
                                <span class="text-emerald-600 flex items-center gap-1 text-[11px]">🟠 Present</span>
                            @elseif($log && $log->status === 'late')
                                <span class="text-amber-500 flex items-center gap-1 text-[11px]">⏰ Late</span>
                            @elseif($log && $log->status === 'absent')
                                <span class="text-rose-500 flex items-center gap-1 text-[11px]">❌ Absent</span>
                            @else
                                <span class="text-slate-400 flex items-center gap-1 text-[11px]">⚪ Not Marked</span>
                            @endif
                            <span class="text-slate-300 text-base font-normal ml-0.5">&rsaquo;</span>
                        </div>
                        @if($formattedTime)
                            <span class="text-[9px] font-medium text-slate-400 pr-4">
                                {{ \Carbon\Carbon::parse($log->updated_at)->timezone('Asia/Kolkata')->format('h:i A') }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-4 gap-1.5 mt-6 border-t border-slate-100 pt-4">
        
        <div class="bg-white border border-emerald-100 rounded-xl p-2 flex flex-col items-center justify-center text-center shadow-2xs">
            <span class="text-[9px] font-bold text-slate-500 leading-tight">Present</span>
            <span class="text-lg font-black text-emerald-600 serif-title mt-0.5">{{ $presentToday }}</span>
        </div>

        <div class="bg-white border border-amber-100 rounded-xl p-2 flex flex-col items-center justify-center text-center shadow-2xs">
            <span class="text-[9px] font-bold text-slate-500 leading-tight">Late</span>
            <span class="text-lg font-black text-amber-500 serif-title mt-0.5">{{ $lateToday }}</span>
        </div>

        <div class="bg-white border border-rose-100 rounded-xl p-2 flex flex-col items-center justify-center text-center shadow-2xs">
            <span class="text-[9px] font-bold text-slate-500 leading-tight">Absent</span>
            <span class="text-lg font-black text-rose-600 serif-title mt-0.5">{{ $absentToday }}</span>
        </div>

        <div class="bg-white border border-slate-100 rounded-xl p-2 flex flex-col items-center justify-center text-center shadow-2xs">
            <span class="text-[9px] font-bold text-slate-400 leading-tight">Total</span>
            <span class="text-lg font-black text-orange-500 serif-title mt-0.5">{{ $totalStudentsCount }}</span>
        </div>
        
    </div>
</div>
@endsection