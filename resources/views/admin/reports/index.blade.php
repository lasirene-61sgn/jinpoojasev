@extends('layouts.admin')

@section('title', 'Attendance Reports')

@section('content')
    <!-- Header Title Section -->
    <div class="mb-4 mt-2">
        <h2 class="text-4xl font-bold text-[#2A2421] serif-title tracking-tight">Reports</h2>
        <p class="text-sm font-medium text-amber-900/60 mt-1">Sunday Worship Attendance Report</p>
    </div>

    <!-- 1. Selection Parameter Filter Form Panel Layout -->
    <div class="bg-white border border-orange-100 rounded-2xl p-4 shadow-sm mb-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">📅 Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full bg-white border border-slate-200 rounded-xl p-2 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-orange-500 text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">📅 End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full bg-white border border-slate-200 rounded-xl p-2 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-orange-500 text-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">👤 Student Name / ID</label>
                <input type="text" name="search_student" value="{{ $searchQuery }}" placeholder="Search by name or ID..." class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-orange-400 text-slate-700">
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1 items-center">
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-center text-orange-600 hover:underline">Reset Filters</a>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold py-2.5 rounded-xl shadow-xs transition flex items-center justify-center gap-1 cursor-pointer">
                    📊 View Report
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Dynamic Metric Breakdown Card Row Layout -->
    <div class="grid grid-cols-3 gap-2 mb-6">
        <div class="bg-white border border-orange-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-3xs">
            <div class="w-7 h-7 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 text-xs mb-1">📋</div>
            <span class="text-[9px] font-bold text-slate-500 leading-tight">Total Records</span>
            <span class="text-xl font-black text-orange-500 serif-title mt-0.5">{{ $totalRecords }}</span>
        </div>

        <div class="bg-white border border-emerald-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-3xs">
            <div class="w-7 h-7 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 text-xs mb-1">✅</div>
            <span class="text-[9px] font-bold text-slate-500 leading-tight">Present</span>
            <span class="text-xl font-black text-emerald-500 serif-title mt-0.5">{{ $presentCount }}</span>
            <span class="text-[8px] text-slate-400 font-bold">({{ $presentPercentage }}%)</span>
        </div>

        <div class="bg-white border border-rose-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-3xs">
            <div class="w-7 h-7 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 text-xs mb-1">❌</div>
            <span class="text-[9px] font-bold text-slate-500 leading-tight">Absent / Late</span>
            <span class="text-xl font-black text-rose-500 serif-title mt-0.5">{{ $absentCount + $lateCount }}</span>
            <span class="text-[8px] text-slate-400 font-bold">({{ round($absentPercentage + $latePercentage, 1) }}%)</span>
        </div>
    </div>

    <!-- 3. Attendance Distribution Pie Chart Overview -->
    <div class="bg-white border border-orange-100 rounded-2xl p-4 shadow-3xs flex flex-col items-center mb-6">
        <div class="w-full flex items-center gap-1.5 mb-4">
            <span class="text-sm">📈</span>
            <h3 class="text-sm font-black text-slate-800">Attendance Overview</h3>
        </div>

        @php
            $presentEnd = $presentPercentage;
            $lateEnd = $presentEnd + $latePercentage;
        @endphp
        <div class="relative w-32 h-32 rounded-full flex items-center justify-center shadow-inner mb-4" 
             style="background: conic-gradient(
                #10b981 0% {{ $presentEnd }}%, 
                #f59e0b {{ $presentEnd }}% {{ $lateEnd }}%, 
                #f43f5e {{ $lateEnd }}% 100%
             );">
            <div class="w-22 h-22 bg-white rounded-full flex flex-col items-center justify-center shadow-xs">
                <span class="text-xl font-black text-slate-800">{{ $totalRecords }}</span>
                <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Total</span>
            </div>
        </div>

        <div class="w-full grid grid-cols-3 gap-1 text-[10px] font-bold text-slate-600 border-t border-slate-50 pt-3">
            <span class="flex items-center gap-1 justify-center"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pres ({{ $presentPercentage }}%)</span>
            <span class="flex items-center gap-1 justify-center"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Late ({{ $latePercentage }}%)</span>
            <span class="flex items-center gap-1 justify-center"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Abs ({{ $absentPercentage }}%)</span>
        </div>
    </div>

    <!-- 4. Report Details Table Grid (Targeted ID wrapper for isolated background printing) -->
    <div class="mb-20" id="print-report-target">
        <div class="flex items-center gap-1 mb-2">
            <span class="text-sm">📅</span>
            <h3 class="text-sm font-black text-orange-600">Report Details ({{ \Carbon\Carbon::parse($startDate)->format('d M') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</h3>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl shadow-3xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[11px]" id="report-table-id">
                    <thead>
                        <tr class="bg-amber-50/50 border-b border-orange-100/50 font-bold text-slate-700">
                            <th class="p-2.5">Date</th>
                            <th class="p-2.5">Student Name</th>
                            <th class="p-2.5">Member ID</th>
                            <th class="p-2.5">Status</th>
                            <th class="p-2.5">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @forelse($records as $row)
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-2.5 whitespace-nowrap">{{ \Carbon\Carbon::parse($row->attendance_date)->format('d M Y') }}</td>
                                <td class="p-2.5 font-bold text-slate-800">{{ $row->student->student_name ?? 'Deleted' }}</td>
                                <td class="p-2.5 text-slate-400 font-mono">JPJ{{ str_pad($row->student_id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-2.5 font-bold">
                                    @if($row->status === 'present')
                                        <span class="text-emerald-600">Present</span>
                                    @elseif($row->status === 'late')
                                        <span class="text-amber-500">Late</span>
                                    @else
                                        <span class="text-rose-500">Absent</span>
                                    @endif
                                </td>
                                <td class="p-2.5 text-slate-400 truncate max-w-[100px]">{{ $row->remarks ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400 italic">No historical data logs match search configurations.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. Sticky Floating Mobile Export Action Bar -->
    <div class="fixed bottom-14 left-0 right-0 px-4 py-2 z-40 max-w-md mx-auto flex items-center justify-between bg-white/90 backdrop-blur-md border border-slate-100 rounded-t-2xl shadow-lg">
        <span class="text-xs font-black text-slate-700 flex items-center gap-1">📥 Export Report:</span>
        <div class="flex gap-2">
            <button onclick="downloadTableAsExcel()" class="border border-emerald-500 bg-white text-emerald-600 font-bold px-4 py-1.5 rounded-xl text-xs hover:bg-emerald-50 transition cursor-pointer">Excel</button>
            <button onclick="downloadTableAsPDF()" class="border border-rose-500 bg-white text-rose-600 font-bold px-4 py-1.5 rounded-xl text-xs hover:bg-rose-50 transition cursor-pointer">PDF</button>
        </div>
    </div>

    <!-- JavaScript Data Extraction Engine -->
    <script>
        // Formats data layout explicitly to downloadable Excel CSV format mapping
        function downloadTableAsExcel() {
            const table = document.getElementById("report-table-id");
            let csvContent = [];
            
            for (let i = 0; i < table.rows.length; i++) {
                let rowData = [];
                for (let j = 0; j < table.rows[i].cells.length; j++) {
                    let cellText = table.rows[i].cells[j].innerText.trim().replace(/"/g, '""');
                    rowData.push('"' + cellText + '"');
                }
                csvContent.push(rowData.join(","));
            }
            
            const csvBlob = new Blob([csvContent.join("\n")], { type: "text/csv;charset=utf-8;" });
            const link = document.createElement("a");
            const filename = "Attendance_Report_" + new Date().toISOString().slice(0,10) + ".csv";
            
            link.href = URL.createObjectURL(csvBlob);
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // FIX: Grabs just the table container body layout to bypass blank screen issues completely
        function downloadTableAsPDF() {
            const reportContent = document.getElementById("print-report-target").innerHTML;
            
            // Generate clean standalone print layout canvas string
            const printWindow = window.open('', '', 'height=600,width=800');
            
            printWindow.document.write('<html><head><title>Attendance Report Download</title>');
            printWindow.document.write('<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"><\/script>');
            printWindow.document.write('<style>body{font-family:sans-serif;padding:24px;background-color:white;}table{width:100%!important;border-collapse:collapse;}th,td{border-bottom:1px solid #e2e8f0;padding:10px!important;}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(reportContent);
            printWindow.document.write('</body></html>');
            
            printWindow.document.close();
            
            // Allow Tailwind components compilation timing and launch system printer UI drawer
            setTimeout(function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }, 600);
        }
    </script>
@endsection