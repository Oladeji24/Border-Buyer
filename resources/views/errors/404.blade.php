<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Border Buyers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-red-500 text-white p-6 text-center">
                <i class="fas fa-exclamation-triangle text-6xl mb-4"></i>
                <h1 class="text-3xl font-bold">404</h1>
                <p class="text-lg">Page Not Found</p>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-6 text-center">
                    Sorry, the page you are looking for could not be found.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('home') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-3 px-4 rounded-md transition duration-200">
                        <i class="fas fa-home mr-2"></i>Go to Homepage
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 px-4 rounded-md transition duration-200">
                        <i class="fas fa-store mr-2"></i>Browse Marketplace
                    </a>
                    <a href="{{ route('agents.directory') }}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white text-center py-3 px-4 rounded-md transition duration-200">
                        <i class="fas fa-users mr-2"></i>Find Agents
                    </a>
                </div>
            </div>
            <div class="bg-gray-100 p-4 text-center">
                <p class="text-sm text-gray-500">
                    Need help? <a href="{{ route('contact') }}" class="text-blue-500 hover:text-blue-600">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>