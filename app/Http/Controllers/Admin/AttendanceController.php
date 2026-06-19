<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));

        $students = Student::where('status', 'active')->orderBy('id', 'desc')->get();

        $attendanceLogs = Attendance::where('attendance_date', $date)->get()
        ->keyBy('student_id');

        $presentToday = Attendance::where('attendance_date', $date)->where('status', 'present')->count();
        $lateToday = Attendance::where('attendance_date', $date)->where('status', 'late')->count();

        $absentToday = Attendance::where('attendance_date', $date)->where('status', 'absent')->count();
        $totalStudentsCount = $students->count();
        

        return view('admin.attendance.index', compact(
            'students', 'date', 'attendanceLogs', 'lateToday', 'presentToday', 'absentToday', 'totalStudentsCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:present,late,absent',
            'remarks' => 'nullable|string|max:1000'
        ]);
        $date = date('Y-m-d');
            Attendance::updateOrCreate([
                'student_id' => $request->student_id,
                'attendance_date' => $date
            ],
            [
                'status' => $request->status,
                'remarks' => $request->remarks
            ]
            );
        return redirect()->route('admin.attendance.index')->with('success', 'status added');
    }
}
