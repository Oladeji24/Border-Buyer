<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Border Buyers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 text-white p-6 text-center">
                <i class="fas fa-envelope-check text-5xl mb-4"></i>
                <h1 class="text-2xl font-bold">Verify Your Email</h1>
                <p class="text-blue-100 mt-2">Check your inbox for a verification link</p>
            </div>
            
            <div class="p-6">
                @if (session('resent'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>A fresh verification link has been sent to your email address.</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Before proceeding, please check your email for a verification link. 
                                If you did not receive the email, click the button below to request another.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                    @csrf
                    
                    <div class="text-center">
                        <p class="text-gray-600 text-sm mb-4">
                            We sent a verification link to <strong>{{ Auth::user()->email }}</strong>
                        </p>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Resend Verification Email
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-sign-out-alt mr-1"></i>
                        Log Out
                    </a>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>

            <div class="bg-gray-100 p-4 text-center">
                <p class="text-sm text-gray-500">
                    Having trouble? <a href="{{ route('contact') }}" class="text-blue-500 hover:text-blue-600">Contact Support</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh to check if email is verified
        setTimeout(function() {
            fetch('/email/verification-notification', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    window.location.href = '{{ route("home") }}';
                }
            });
        }, 5000); // Check every 5 seconds
    </script>
</body>
</html>