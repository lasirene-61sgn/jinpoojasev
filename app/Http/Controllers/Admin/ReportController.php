<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Capture inputs safely
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $searchQuery = $request->input('search_student', '');

        // Initialize base query builder with eager loading
        $query = Attendance::with('student');

        // Enforce the specific target date range constraints first
        $query->whereBetween('attendance_date', [$startDate, $endDate]);

        // ISOLATE SEARCH CONTEXT: Only apply search logic if a string is provided
        if (!empty($searchQuery)) {
            $query->where(function($mainQuery) use ($searchQuery) {
                $mainQuery->whereHas('student', function($subQuery) use ($searchQuery) {
                    $subQuery->where('student_name', 'like', "%{$searchQuery}%")
                             ->orWhere('id', 'like', "%{$searchQuery}%");
                });
            });
        }

        // Fetch execution results
        $records = $query->orderBy('attendance_date', 'desc')->get();

        // Metric calculations
        $totalRecords = $records->count();
        $presentCount = $records->where('status', 'present')->count();
        $lateCount = $records->where('status', 'late')->count();
        $absentCount = $records->where('status', 'absent')->count();

        $presentPercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0;
        $latePercentage = $totalRecords > 0 ? round(($lateCount / $totalRecords) * 100, 1) : 0;
        $absentPercentage = $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100, 1) : 0;

        return view('admin.reports.index', compact(
            'records', 'startDate', 'endDate', 'searchQuery',
            'totalRecords', 'presentCount', 'lateCount', 'absentCount',
            'presentPercentage', 'latePercentage', 'absentPercentage'
        ));
    }
}