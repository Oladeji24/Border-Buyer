<nav class="bg-white shadow-md sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex items-center">
        <div class="flex-shrink-0 flex items-center">
          <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center">
            <i class="fas fa-shield-alt text-white"></i>
          </div>
          <a href="/" class="ml-2 text-xl font-bold text-gray-800">Border Buyers</a>
        </div>
        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
          <a href="/" class="border-transparent text-gray-500 hover:border-green-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
            Home
          </a>
          <a href="/#services" class="border-transparent text-gray-500 hover:border-green-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
            Services
          </a>
          <a href="/#how-it-works" class="border-transparent text-gray-500 hover:border-green-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
            How it works
          </a>
          <a href="{{ route('contact') }}" class="border-transparent text-gray-500 hover:border-green-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
            Contact Us
          </a>
        </div>
      </div>
      <div class="hidden sm:ml-6 sm:flex sm:items-center">
        @auth
          <form method="POST" action="{{ route('logout') }}" class="ml-3 relative">
            @csrf
            <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
              <i class="fas fa-sign-out-alt mr-2"></i>
              Log Out
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Log In
          </a>
          <a href="{{ route('register') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
            <i class="fas fa-user-plus mr-2"></i>
            Sign Up
          </a>
        @endauth
      </div>

      <!-- Mobile menu button -->
      <div class="-mr-2 flex items-center sm:hidden">
        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500" id="mobile-menu-button">
          <span class="sr-only">Open main menu</span>
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="sm:hidden hidden" id="mobile-menu">
    <div class="pt-2 pb-3 space-y-1">
      <a href="/" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition">
        Home
      </a>
      <a href="/#services" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition">
        Services
      </a>
      <a href="/#how-it-works" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition">
        How it works
      </a>
      <a href="/#contact" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition">
        Contact Us
      </a>
    </div>
    <div class="pt-4 pb-3 border-t border-gray-200">
      @auth
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
            <i class="fas fa-sign-out-alt mr-3"></i>
            Log Out
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
          <i class="fas fa-sign-in-alt mr-3"></i>
          Log In
        </a>
        <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
          <i class="fas fa-user-plus mr-3"></i>
          Sign Up
        </a>
      @endauth
    </div>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
    });
  });
</script>