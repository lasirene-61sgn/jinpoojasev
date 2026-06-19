@extends('layouts.admin')

@section('title', 'Edit Student Profile')

@section('content')
    <div class="mb-6 mt-2">
        <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-orange-500 hover:underline">&larr; Back to Student List</a>
        <h2 class="text-3xl font-bold text-[#2A2421] serif-title tracking-tight mt-1">Edit Student</h2>
    </div>

    <div class="bg-white border border-orange-100 rounded-2xl p-5 shadow-sm">
        
        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-700 p-3 rounded-xl mb-4 text-xs font-medium">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4 bg-slate-50 p-3.5 border border-slate-200/60 rounded-xl flex justify-between items-center">
                <div>
                    <span class="block text-[10px] uppercase font-black text-slate-400 tracking-wider">Assigned Identity Reference</span>
                    <span class="text-sm font-bold text-slate-700">Student Account Number</span>
                </div>
                <span class="text-base font-black text-orange-600 bg-orange-50 border border-orange-100 px-4 py-1 rounded-xl">
                    ID: {{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Student Name *</label>
                    <input type="text" name="student_name" required value="{{ old('student_name', $student->student_name) }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" required value="{{ old('date_of_birth', $student->date_of_birth) }}" onchange="calculateStudentAge()" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Calculated Age</label>
                        <input type="text" id="age_display" readonly value="{{ $student->age }} Years Old" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-600">
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-4">
                    <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Guardian Details</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Guardian Name *</label>
                            <input type="text" name="guardian_name" required value="{{ old('guardian_name', $student->guardian_name) }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Number *</label>
                            <input type="tel" name="mobile_number" required value="{{ old('mobile_number', $student->mobile_number) }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-4">
                    <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Additional Context</h4>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-700">
                                <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Remarks</label>
                            <textarea name="remarks" rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('remarks', $student->remarks) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex flex-col gap-2">
                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 rounded-xl shadow-md transition text-sm">
                        Save System Profile Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Run age calculation instantly on load to configure UI state formatting rules cleanly
        window.onload = function() {
            calculateStudentAge();
        };

        function calculateStudentAge() {
            const dobInput = document.getElementById('date_of_birth').value;
            if(!dobInput) return;
            
            const dob = new Date(dobInput);
            const today = new Date();
            
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            // Adjust calculation logic if birthday hasn't happened yet this calendar year
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            const display = document.getElementById('age_display');
            display.value = age + " Years Old";
            
            // Enforce conditional error alerts if parameters violate your strict age ceiling 
            if (age > 13) {
                display.classList.remove('text-slate-600', 'bg-slate-50');
                display.classList.add('text-rose-600', 'bg-rose-50');
                alert("Alert: Modification violates registration guidelines. System restricts profiles over 13 years old.");
            } else {
                display.classList.remove('text-rose-600', 'bg-rose-50');
                display.classList.add('text-slate-600', 'bg-slate-50');
            }
        }
    </script>
@endsection