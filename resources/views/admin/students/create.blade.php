@extends('layouts.admin')

@section('title', 'Add New Student')

@section('content')
    <div class="mb-6 mt-2">
        <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-orange-500 hover:underline">&larr; Back to Student List</a>
        <h2 class="text-3xl font-bold text-[#2A2421] serif-title tracking-tight mt-1">Add Student</h2>
    </div>

    <div class="bg-white border border-orange-100 rounded-2xl p-5 shadow-sm">
        
        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-700 p-3 rounded-xl mb-4 text-xs font-medium">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf

            <!-- Identity Picker Configurations Block -->
            <div class="mb-4 bg-orange-50/40 p-3.5 border border-orange-100/60 rounded-xl">
                <label class="block text-xs font-bold text-slate-700 mb-2">Student ID Assignment Mode</label>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <label class="border bg-white p-3 rounded-xl flex items-center gap-2 cursor-pointer text-xs font-bold shadow-sm">
                        <input type="radio" name="id_type" value="new" checked onclick="toggleIdMode('new')" class="text-orange-500 focus:ring-orange-500">
                        <span>New System ID</span>
                    </label>
                    <label class="border bg-white p-3 rounded-xl flex items-center gap-2 cursor-pointer text-xs font-bold shadow-sm">
                        <input type="radio" name="id_type" value="reusable" onclick="toggleIdMode('reusable')" class="text-orange-500 focus:ring-orange-500">
                        <span>Reusable ID</span>
                    </label>
                </div>

                <!-- Input Context Switches -->
                <div id="new-id-input">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Generated ID</label>
                    <input type="number" name="new_id" value="{{ $nextNewId }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-600">
                </div>

                <div id="reusable-id-input" class="hidden">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Select Available Recycled ID</label>
                    <select name="reusable_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-700">
                        @foreach($availableIds as $reusedId)
                            <option value="{{ $reusedId }}">ID: {{ str_pad($reusedId, 3, '0', STR_PAD_LEFT) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Student Profile Information Fields Matrix -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Student Name *</label>
                    <input type="text" name="student_name" required value="{{ old('student_name') }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" required value="{{ old('date_of_birth') }}" onchange="calculateStudentAge()" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Calculated Age</label>
                        <input type="text" id="age_display" readonly placeholder="Auto-calculated" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-600">
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-4">
                    <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Guardian Details</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Guardian Name *</label>
                            <input type="text" name="guardian_name" required value="{{ old('guardian_name') }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Number *</label>
                            <input type="tel" name="mobile_number" required value="{{ old('mobile_number') }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-4">
                    <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Additional Context</h4>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-700">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Remarks</label>
                            <textarea name="remarks" rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 rounded-xl shadow-md transition text-sm mt-2">
                    Register Roster Record
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript For Interactivity Logic Operations -->
    <script>
        function toggleIdMode(mode) {
            const newBox = document.getElementById('new-id-input');
            const reuseBox = document.getElementById('reusable-id-input');
            if(mode === 'new') {
                newBox.classList.remove('hidden');
                reuseBox.classList.add('hidden');
            } else {
                newBox.classList.add('hidden');
                reuseBox.classList.remove('hidden');
            }
        }

        function calculateStudentAge() {
            const dobInput = document.getElementById('date_of_birth').value;
            if(!dobInput) return;
            
            const dob = new Date(dobInput);
            const today = new Date();
            
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            const display = document.getElementById('age_display');
            display.value = age + " Years Old";
            
            // Front-end warning interface block if condition breaches constraint parameters
            if (age > 13) {
                display.classList.remove('text-slate-600', 'bg-slate-50');
                display.classList.add('text-rose-600', 'bg-rose-50');
                alert("Alert: Age exceeds parameters. System restricts student registration if over 13 years old.");
            } else {
                display.classList.remove('text-rose-600', 'bg-rose-50');
                display.classList.add('text-slate-600', 'bg-slate-50');
            }
        }
    </script>
@endsection