<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Phone - Border Buyers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-green-600 text-white p-6 text-center">
                <i class="fas fa-mobile-alt text-5xl mb-4"></i>
                <h1 class="text-2xl font-bold">Verify Your Phone</h1>
                <p class="text-green-100 mt-2">Enter the 6-digit code sent to your phone</p>
            </div>
            
            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>{{ $errors->first('code') }}</span>
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

                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                We've sent a 6-digit verification code to <strong>{{ Auth::user()->phone }}</strong>. 
                                The code will expire in 10 minutes.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('phone.verify') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Verification Code
                        </label>
                        <input 
                            id="code" 
                            type="text" 
                            name="code" 
                            maxlength="6" 
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent text-center text-2xl font-mono tracking-widest"
                            placeholder="000000"
                            required
                            autofocus
                        >
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 flex items-center justify-center">
                        <i class="fas fa-check mr-2"></i>
                        Verify Phone Number
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <form method="POST" action="{{ route('phone.resend') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-green-600 hover:text-green-700 font-medium">
                            <i class="fas fa-redo mr-1"></i>
                            Resend Code
                        </button>
                    </form>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Skip for now
                    </a>
                </div>
            </div>

            <div class="bg-gray-100 p-4 text-center">
                <p class="text-sm text-gray-500">
                    Having trouble? <a href="{{ route('contact') }}" class="text-green-500 hover:text-green-600">Contact Support</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-format the verification code input
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Auto-submit when 6 digits are entered
        document.getElementById('code').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                e.target.form.submit();
            }
        });

        // Timer for code expiration
        let timeLeft = 600; // 10 minutes in seconds
        const timerElement = document.createElement('div');
        timerElement.className = 'text-center mt-4 text-sm text-gray-500';
        timerElement.innerHTML = '<i class="fas fa-clock mr-1"></i>Code expires in <span id="timer">10:00</span>';
        
        const form = document.querySelector('form');
        form.parentNode.insertBefore(timerElement, form.nextSibling);

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('timer').textContent = 
                `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft > 0) {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                timerElement.innerHTML = '<span class="text-red-500"><i class="fas fa-exclamation-triangle mr-1"></i>Code expired</span>';
                document.getElementById('code').disabled = true;
                document.querySelector('button[type="submit"]').disabled = true;
            }
        }

        updateTimer();
    </script>
</body>
</html>