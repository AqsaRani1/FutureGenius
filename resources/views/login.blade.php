<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FutureGenius LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <header class="bg-indigo-700 text-white p-6">
        <h1 class="text-3xl font-bold text-center">FutureGenius LMS</h1>
    </header>
    <main class="p-8">
        <h2 class="text-2xl mb-4 text-center">Welcome to FutureGenius</h2>
        <p class="mb-6 text-center">An advanced LMS with personalized learning, gamification, and analytics.</p>
        @if (!Auth::check())
            @yield('content')
        @else
            <div class="flex items-center justify-center mt-4">

                <a href="{{ route('dashboard') }} "
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    style="background-color: #4338CA"> Go to Dashboard</a>
            </div>
        @endif

    </main>
</body>


</html>
