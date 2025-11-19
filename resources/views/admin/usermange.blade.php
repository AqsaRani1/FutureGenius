@extends('dashboards.admin')

@section('content')
    <div class="flex justify-between items-center m-5">
        <h2 class="text-2xl font-bold">Manage Users</h2>
        <button onclick="openAddUserModal()" class="bg-indigo-600 text-white px-4 py-2 rounded">+ Add User</button>
    </div>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="bg-white rounded shadow m-5 w-full">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="py-2 px-4">Name</th>
                <th class="py-2 px-4">Email</th>
                <th class="py-2 px-4">Role</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b">
                    <td class="py-2 px-4">{{ $user->name }}</td>
                    <td class="py-2 px-4">{{ $user->email }}</td>
                    <td class="py-2 px-4">{{ ucfirst($user->role) }}</td>
                    <td class="py-2 px-4 space-x-2">
                        <!-- Edit -->
                        <button class="bg-yellow-500 text-white px-2 py-1 rounded"
                            onclick='openEditUserModal(@json($user))'>Edit</button>

                        <!-- Delete -->
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline"
                            onsubmit="return confirm('Delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- User Modal (Add / Edit) -->
    <div id="userModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h3 class="text-xl font-semibold mb-4" id="modalTitle">Add New User</h3>

            <form id="userForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="mb-4">
                    <label class="block mb-1">Name</label>
                    <input type="text" name="name" id="userName" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Email</label>
                    <input type="email" name="email" id="userEmail" class="w-full border rounded p-2">
                </div>

                <!-- Password field (only for Add) -->
                <div class="mb-4" id="passwordField">
                    <label class="block mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Role</label>
                 <select name="role" id="userRole" class="w-full border rounded p-2">
    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
    <option value="instructor" {{ old('role') == 'instructor' ? 'selected' : '' }}>Instructor</option>
    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
</select>

                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('userModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddUserModal() {
            document.getElementById('modalTitle').innerText = "Add New User";
            document.getElementById('userForm').action = "{{ route('admin.users.store') }}";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('passwordField').style.display = "block";

            // Clear values
            document.getElementById('userName').value = "";
            document.getElementById('userEmail').value = "";

            // ✅ Instead of blank, set default
            document.getElementById('userRole').value = "student";

            document.getElementById('userModal').classList.remove('hidden');
        }


        function openEditUserModal(user) {
            document.getElementById('modalTitle').innerText = "Edit User";
            document.getElementById('userForm').action = "/admin/users/" + user.id;
            document.getElementById('formMethod').value = "PUT";
            document.getElementById('passwordField').style.display = "none";

            // Fill values
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userRole').value = user.role;

            document.getElementById('userModal').classList.remove('hidden');
        }
    </script>
@endsection
