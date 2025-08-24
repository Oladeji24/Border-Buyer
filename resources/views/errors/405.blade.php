<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Method Not Allowed - Border Buyers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-orange-500 text-white p-6 text-center">
                <i class="fas fa-ban text-6xl mb-4"></i>
                <h1 class="text-3xl font-bold">405</h1>
                <p class="text-lg">Method Not Allowed</p>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-6 text-center">
                    The HTTP method used for this request is not supported.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                This usually happens when trying to access a page with the wrong HTTP method (GET, POST, PUT, DELETE).
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
                    <a href="javascript:history.back()" class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center py-3 px-4 rounded-md transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Go Back
                    </a>
                </div>
            </div>
            <div class="bg-gray-100 p-4 text-center">
                <p class="text-sm text-gray-500">
                    If you believe this is an error, please <a href="{{ route('contact') }}" class="text-blue-500 hover:text-blue-600">contact us</a>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>