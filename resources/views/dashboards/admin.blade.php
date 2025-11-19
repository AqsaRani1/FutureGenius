<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - FutureGenius</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<style>
    .d-none {
        display: none;
    }

    .user_profile {
        cursor: pointer;
    }
</style>

<body class="text-gray-900 bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    <div class="flex h-screen">
        <aside class="flex flex-col w-64 text-white bg-indigo-700" style="height: 100%">
            <div class="px-6 py-4 text-2xl font-bold border-b border-indigo-600">FutureGenius</div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.users') }}" style="text-decoration: none"><button data-tab="dashboard"
                        class="w-full px-3 py-2 text-left rounded hover:bg-indigo-600 tabBtn">Dashboard</button></a>
                <a href="{{ route('admin.manageuser') }}" style="text-decoration: none"><button data-tab="users"
                        class="w-full px-3 py-2 text-left rounded hover:bg-indigo-600 tabBtn">Manage Users</button></a>
                <a href="{{ route('admin.course') }}" style="text-decoration: none"><button data-tab="users"
                        class="w-full px-3 py-2 text-left rounded hover:bg-indigo-600 tabBtn">Manage
                        Courses</button></a>
                {{-- <button data-tab="settings" --}}
                {{-- class="w-full px-3 py-2 text-left rounded hover:bg-indigo-600 tabBtn">Settings</button> --}}
            </nav>
        </aside>
        <div class="flex flex-col flex-1" style="height: 100%; overflow-y: auto;">
            <header class="flex items-center justify-between px-6 py-4 bg-white shadow dark:bg-gray-800">
                <h1 class="text-xl font-semibold">Admin Panel</h1>
                <div class="flex items-center space-x-4">
                    {{-- <button id="darkModeToggle"class="px-3 py-1 bg-gray-200 rounded dark:bg-gray-700">🌙</button> --}}
                    <div class="w-8 h-8 bg-pink-400 rounded-full user_profile"
                        style="align-content: center; position: relative;:">

                        @if (Auth::user())
                            <h1 class="text-center text-white fw-semibold " style="text-transform: uppercase">
                                {{ Auth::user()->name[0] }}</h1>
                            <form action="{{ route('logout') }}" method="POST" enctype="multipart/form-data"
                                class="logout d-none">
                                @csrf
                                <button class="text-white bg-indigo-600 shadow hover:bg-indigo-700"
                                    style="top: 130%;
                                       position: absolute;
                                       right: 30%;
                                        background-color: white;
                                        padding: 0px 20px;
                                        font-size: 18px;
                                           color: black;
                                              border-radius: 6px;
                                             border: 1px solid lightgray;">Logout</button>
                            </form>
                        @endif

                    </div>
                </div>
            </header>
            @yield('content')
        </div>
    </div>
    <script type="module">
        document.querySelectorAll('.tabBtn').forEach(b => b.addEventListener('click', () => {
            document.querySelectorAll('.tabPane').forEach(p => p.classList.add('hidden'));
            document.getElementById(b.dataset.tab).classList.remove('hidden');
        }));
        document.getElementById('darkModeToggle').onclick = () => document.documentElement.classList.toggle('dark');
    </script>
    <script>
        const user = document.querySelector('.user_profile');
        const logout = document.querySelector('.logout');
        console.log(logout);
        console.log(user);
        user.addEventListener('click', () => {
            logout.classList.toggle('d-none');
        })
    </script>
</body>

</html>
