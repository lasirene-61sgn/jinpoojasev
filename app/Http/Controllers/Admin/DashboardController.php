<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // 1. Core Summary Counts
        $totalStudents = Student::where('status', 'active')->count();
        $presentToday = Attendance::where('attendance_date', $today)->where('status', 'present')->count();
        $lateToday = Attendance::where('attendance_date', $today)->where('status', 'late')->count();
        $absentToday = Attendance::where('attendance_date', $today)->where('status', 'absent')->count();

        // Combining Present and Late counts as active overall attendees per your image layout rules
        $totalPresentAggregate = $presentToday + $lateToday;

        // 2. Percentages calculation handling zero cases safely
        $presentPercentage = $totalStudents > 0 ? round(($totalPresentAggregate / $totalStudents) * 100, 1) : 0;
        $absentPercentage = $totalStudents > 0 ? round(($absentToday / $totalStudents) * 100, 1) : 0;

        // 3. Dynamic Recent History Module Query (Groups past sessions for the bottom table in image_ef7a82.png)
        $recentHistory = Attendance::select('attendance_date')
            ->selectRaw("COUNT(CASE WHEN status = 'present' THEN 1 END) as p_count")
            ->selectRaw("COUNT(CASE WHEN status = 'late' THEN 1 END) as l_count")
            ->selectRaw("COUNT(CASE WHEN status = 'absent' THEN 1 END) as a_count")
            ->groupBy('attendance_date')
            ->orderBy('attendance_date', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalPresentAggregate',
            'absentToday',
            'presentPercentage',
            'absentPercentage',
            'recentHistory'
        ));
    }
}