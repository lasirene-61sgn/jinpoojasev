<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center gap-4">
        <a href="{{ route('superadmin.admins.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to List</a>
        <h1 class="text-xl font-bold text-gray-800">Edit Admin Settings</h1>
    </nav>

    <main class="max-w-2xl mx-auto p-6 mt-6">
        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
            
            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-md mb-6 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('superadmin.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Temple Name</label>
                        <input type="text" name="temple_name" value="{{ old('temple_name', $admin->temple_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number *</label>
                        <input type="text" name="mobile_number" value="{{ old('mobile_number', $admin->mobile_number) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="p-4 bg-yellow-50 rounded border border-yellow-100 mb-4">
                    <p class="text-xs text-yellow-800 font-semibold mb-2">Security Modification Policy:</p>
                    <p class="text-xs text-yellow-700">Leave the password fields empty below unless you explicitly want to assign a brand new password to this administrator.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="mb-6 flex items-center gap-4">
                    @if($admin->profile_image)
                        <div class="flex-shrink-0">
                            <span class="block text-xs font-medium text-gray-500 mb-1">Current Image:</span>
                            <img src="{{ asset('storage/' . $admin->profile_image) }}" class="w-16 h-16 rounded object-cover border">
                        </div>
                    @endif
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Replace Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition">
                    Update Account
                </button>
            </form>
        </div>
    </main>

</body>
</html>