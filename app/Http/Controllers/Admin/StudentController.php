<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('id', 'asc')->paginate(10);
        $totalActive = Student::where('status', 'active')->count();
        $newThisYear = Student::whereYear('created_at', date('Y'))->count();

        $maxId = Student::max('id') ?? 0;
        $allPossibleIds = range(1, max($maxId + 1, 1));
        $existingIds = Student::pluck('id')->toArray();
        $availableIds = array_diff($allPossibleIds, $existingIds);

        $reusableIdsDeck = array_slice($availableIds, 0, 5);
        $totalReusableCount = count($availableIds);
        return view('admin.students.index', compact('students', 'totalActive', 'newThisYear',
        'reusableIdsDeck', 'availableIds', 'totalReusableCount'));
    }

    public function create()
    {
        $maxId = Student::max('id') ?? 0;
        $allPossibleIds = range(1, max($maxId + 1, 1));
        $existingIds = Student::pluck('id')->toArray();

        $availableIds = array_diff($allPossibleIds, $existingIds);

        $nextNewId = $maxId + 1;
        return view('admin.students.create', compact('availableIds', 'nextNewId'));
    }

    public function store(Request $request)
    {
        $dob = Carbon::parse($request->date_of_birth);
        $age = $dob->age;

        if($age > 13){
            return back()->withErrors([
                'date_of_birth' => "Registartion failed stdent age is {$age} above 13 years"
            ])->withInput();
        }

        $studentId = $request->id_type === 'reusable' ? $request->reusable_id : $request->new_id;

        $request->validate([
            'student_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'guardian_name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        if(Student::where('id', $studentId)->exists()){
            return back()->withErrors([
                'new_id' => 'Id Already Have been taken select another id'
            ])->withInput();
        }

        Student::create([
            'id' => $studentId,
            'student_name' => $request->student_name,
            'date_of_birth' => $request->date_of_birth,
            'age' => $age,
            'guardian_name' => $request->guardian_name,
            'mobile_number' => $request->mobile_number,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.students.index')->with('success', 'Created');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $dob = Carbon::parse($request->date_of_birth);
        $age = $dob->age;

        if($age > 13){
            return back()->withErrors([
                'date_of_birth' => "Update Fail Because student age greater that {$age}"
            ])->withInput();
        }
        $request->validate([
            'student_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'mobile_number' => 'required|string',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
        $student->update([
            'student_name' => $request->student_name,
            'date_of_birth' => $request->date_of_birth,
            'age' => $age, // Set the newly calculated age
            'guardian_name' => $request->guardian_name,
            'mobile_number' => $request->mobile_number,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.students.index')->with('success', 'updated');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return view('admin.students.index')->with('success', 'deleted');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'bulk_file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('bulk_file');
        $filePath = $file->getRealPath();
        
        $fileHandle = fopen($filePath, 'r');
        $header = fgetcsv($fileHandle, 1000, ","); // Skip CSV Header columns line

        $skippedRowsErrorLog = [];
        $successCount = 0;
        $rowNumber = 1;

        // Begin transaction loop
        while (($row = fgetcsv($fileHandle, 1000, ",")) !== FALSE) {
            $rowNumber++;
            
            // Expected columns structural array map:
            // 0: student_name, 1: date_of_birth (YYYY-MM-DD), 2: guardian_name, 3: mobile_number, 4: status, 5: remarks
            if (count($row) < 5) {
                $skippedRowsErrorLog[] = "Row {$rowNumber}: Skipped due to missing column data data paths.";
                continue;
            }

            $name = trim($row[0]);
            $dobString = trim($row[1]);
            $guardian = trim($row[2]);
            $mobile = trim($row[3]);
            $status = strtolower(trim($row[4])) === 'active' ? 'active' : 'inactive';
            $remarks = isset($row[5]) ? trim($row[5]) : null;

            if (empty($name) || empty($dobString)) {
                $skippedRowsErrorLog[] = "Row {$rowNumber}: Missing student name or date of birth.";
                continue;
            }

            try {
                $dob = Carbon::parse($dobString);
                $age = $dob->age;
            } catch (\Exception $e) {
                $skippedRowsErrorLog[] = "Row {$rowNumber}: Invalid date format string choice ('{$dobString}'). Use YYYY-MM-DD.";
                continue;
            }

            // CRITICAL: Strict registration limit ceiling check
            if ($age > 13) {
                $skippedRowsErrorLog[] = "Row {$rowNumber}: Skipped student '{$name}' (Age: {$age}) because they are above 13 years old.";
                continue;
            }

            // DYNAMIC ID SELECTION: Recycles missing sequence slots first, or shifts to next new max increment
            $maxId = Student::max('id') ?? 0;
            $existingIds = Student::pluck('id')->toArray();
            $allPossibleIds = $maxId > 0 ? range(1, $maxId) : [];
            $availableIds = array_diff($allPossibleIds, $existingIds);

            if (!empty($availableIds)) {
                $assignedId = reset($availableIds); // Take first available recycled slot
            } else {
                $assignedId = $maxId + 1; // Otherwise shift forward sequentially
            }

            Student::create([
                'id' => $assignedId,
                'student_name' => $name,
                'date_of_birth' => $dob->toDateString(),
                'age' => $age,
                'guardian_name' => $guardian,
                'mobile_number' => $mobile,
                'remarks' => $remarks,
                'status' => $status,
            ]);

            $successCount++;
        }
        fclose($fileHandle);

        if (count($skippedRowsErrorLog) > 0) {
            return redirect()->route('admin.students.index')
                ->with('success', "Bulk upload complete. Successfully added {$successCount} students.")
                ->withErrors($skippedRowsErrorLog);
        }

        return redirect()->route('admin.students.index')->with('success', "Bulk upload successful! Added {$successCount} students.");
    }
}
