<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - Border Buyers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-red-600 text-white p-6 text-center">
                <i class="fas fa-exclamation-circle text-6xl mb-4"></i>
                <h1 class="text-3xl font-bold">500</h1>
                <p class="text-lg">Server Error</p>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-6 text-center">
                    Sorry, something went wrong on our end. Our team has been notified and we're working on it.
                </p>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                The error has been logged and our technical team will investigate it shortly.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('home') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-3 px-4 rounded-md transition duration-200">
                        <i class="fas fa-home mr-2"></i>Go to Homepage
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 px-4 rounded-md transition duration-200">
                        <i class="fas fa-store mr-2"></i>Browse Marketplace
                    </a>
                    <a href="{{ route('contact') }}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white text-center py-3 px-4 rounded-md transition duration-200">
                        <i class="fas fa-envelope mr-2"></i>Contact Support
                    </a>
                </div>
            </div>
            <div class="bg-gray-100 p-4 text-center">
                <p class="text-sm text-gray-500">
                    We apologize for the inconvenience. Please try again in a few minutes.
                </p>
            </div>
        </div>
    </div>
</body>
</html>