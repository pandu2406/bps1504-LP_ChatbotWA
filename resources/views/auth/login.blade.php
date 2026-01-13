<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Chatbot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Admin Login</h1>
            <p class="text-gray-500 mt-2">Masuk untuk mengelola Knowledge Base AI</p>
        </div>

        <form action="{{ route('authenticate') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            </div>

            <button type="submit"
                class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-gray-800 transition-colors shadow-lg shadow-gray-900/20">
                Masuk Dashboard
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <a href="/" class="text-gray-500 hover:text-gray-800">Kembali ke Landing Page</a>
        </div>
    </div>
</body>

</html>