<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to Dashboard</a>
            <h1 class="text-xl font-bold text-gray-800">Admin Accounts</h1>
        </div>
        <a href="{{ route('superadmin.admins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded font-semibold transition">
            + Create New Admin
        </a>
    </nav>

    <main class="max-w-7xl mx-auto p-6 mt-6">
        
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-gray-700 text-sm font-semibold">
                            <th class="p-4">Profile</th>
                            <th class="p-4">Name</th>
                            <th class="p-4">Temple Name</th>
                            <th class="p-4">Mobile</th>
                            <th class="p-4">Email</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-600 text-sm">
                        @forelse($admins as $admin)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4">
                                    @if($admin->profile_image)
                                        <img src="{{ asset('storage/' . $admin->profile_image) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 font-semibold text-gray-800">{{ $admin->name }}</td>
                                <td class="p-4">{{ $admin->temple_name ?? 'N/A' }}</td>
                                <td class="p-4">{{ $admin->mobile_number }}</td>
                                <td class="p-4">{{ $admin->email }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('superadmin.admins.edit', $admin->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                        
                                        <form action="{{ route('superadmin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Delete this Admin permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400">No admin accounts found. Create one to get started.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>