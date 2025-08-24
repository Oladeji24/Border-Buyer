@extends('layouts.app')

@section('title', 'Border Buyers — Home')
@section('meta_title', 'Border Buyers — Secure Cross‑Border Escrow & Monitoring')
@section('meta_description', 'Secure cross-border transactions with escrow, real-time monitoring, and verified agents. Build trust and buy safely across borders with Border Buyers.')
@section('meta_keywords', 'border buyers, escrow, monitoring, cross-border payments, trade, agents, marketplace')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Hero Section -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-green-600 tracking-wide uppercase">Welcome to Border Buyers</h2>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">Your Trusted Partner in Cross-Border Transactions</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">We provide secure and reliable escrow and monitoring services for your cross-border purchases.</p>
                <div class="mt-8 flex justify-center">
                    <div class="inline-flex rounded-md shadow">
                        <a href="#services" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Get started
                        </a>
                    </div>
                    <div class="ml-3 inline-flex">
                        <a href="#how-it-works" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200">
                            Learn more
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Services -->
    <div id="services" class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-green-600 tracking-wide uppercase">Our Services</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">
                    Everything you need for a secure transaction
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="pt-6">
                        <div class="flow-root bg-white rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-green-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Escrow Service</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Secure your funds with our trusted escrow service. We hold the payment until you confirm you have received your goods.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <div class="flow-root bg-white rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-green-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Transaction Monitoring</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    We monitor your transactions to ensure everything goes smoothly. We are here to help if any issues arise.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <div class="flow-root bg-white rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-green-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Verified Agents</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Our agents are verified and trusted. They can help you with your purchase and ensure you get what you paid for.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <div class="flow-root bg-white rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-green-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9V3" />
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Global Reach</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    We operate in multiple countries, making it easy for you to buy goods from anywhere in the world.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Escrow vs Monitoring -->
    <div class="relative bg-white pt-16 pb-32 overflow-hidden">
        <div class="relative">
            <div class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-16 lg:max-w-none lg:mx-0 lg:px-0">
                    <div>
                        <div>
                            <span class="h-12 w-12 rounded-md flex items-center justify-center bg-green-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-6">
                            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                Secure Escrow Service
                            </h2>
                            <p class="mt-4 text-lg text-gray-500">
                                Our escrow service provides a safety net for both buyers and sellers. We hold the payment in a secure account until the transaction is complete. This ensures that the buyer receives the goods and the seller gets paid.
                            </p>
                            <div class="mt-6">
                                <a href="#" class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                    Learn more
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-12 sm:mt-16 lg:mt-0">
                    <div class="pl-4 -mr-48 sm:pl-6 md:-mr-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                        <img class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:left-0 lg:h-full lg:w-auto lg:max-w-none" src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" alt="Escrow interface dashboard showing transaction status and secure fund holding">
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-24">
            <div class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-32 lg:max-w-none lg:mx-0 lg:px-0 lg:col-start-2">
                    <div>
                        <div>
                            <span class="h-12 w-12 rounded-md flex items-center justify-center bg-green-600">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-6">
                            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                                Real-time Monitoring
                            </h2>
                            <p class="mt-4 text-lg text-gray-500">
                                We monitor every transaction in real-time to detect and prevent fraud. Our system analyzes multiple data points to ensure the legitimacy of each transaction, providing peace of mind for all parties involved.
                            </p>
                            <div class="mt-6">
                                <a href="#" class="inline-flex px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                    Learn more
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-12 sm:mt-16 lg:mt-0 lg:col-start-1">
                    <div class="pr-4 -ml-48 sm:pr-6 md:-ml-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
                        <img class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:right-0 lg:h-full lg:w-auto lg:max-w-none" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" alt="Monitoring interface dashboard showing real-time transaction tracking and risk analysis">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div id="how-it-works" class="bg-gray-50 overflow-hidden">
        <div class="relative max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="relative lg:grid lg:grid-cols-3 lg:gap-x-8">
                <div class="lg:col-span-1">
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        A better way to buy and sell across borders.
                    </h2>
                </div>
                <dl class="mt-10 space-y-10 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-x-8 sm:gap-y-10 lg:mt-0 lg:col-span-2">
                    <div>
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9V3" />
                            </svg>
                        </div>
                        <div class="mt-5">
                            <dt class="text-lg leading-6 font-medium text-gray-900">
                                Create an account
                            </dt>
                            <dd class="mt-2 text-base text-gray-500">
                                Sign up for a free account in minutes.
                            </dd>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H7a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div class="mt-5">
                            <dt class="text-lg leading-6 font-medium text-gray-900">
                                Fund your transaction
                            </dt>
                            <dd class="mt-2 text-base text-gray-500">
                                Deposit funds into our secure escrow account.
                            </dd>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="mt-5">
                            <dt class="text-lg leading-6 font-medium text-gray-900">
                                Agent confirms purchase
                            </dt>
                            <dd class="mt-2 text-base text-gray-500">
                                Our agent verifies the goods and confirms the purchase.
                            </dd>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-5">
                            <dt class="text-lg leading-6 font-medium text-gray-900">
                                Funds are released
                            </dt>
                            <dd class="mt-2 text-base text-gray-500">
                                Once you confirm receipt of your goods, we release the funds to the seller.
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Testimonials (Dummy Carousel) -->
    <section class="bg-white" aria-label="Testimonials">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <div class="lg:col-span-1">
                    <h2 class="text-3xl font-extrabold text-gray-900">What our customers are saying</h2>
                    <p class="mt-4 text-lg text-gray-500">We are trusted by customers worldwide.</p>
                </div>
                <div class="mt-10 lg:mt-0 lg:col-span-2">
                    <div class="relative">
                        <div id="testimonial-slides" class="overflow-hidden">
                            <div class="space-y-6">
                                <!-- Slide 1 -->
                                <div class="testimonial-slide">
                                    <div class="flex items-start">
                                        <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=facearea&facepad=2&w=128&h=128&q=80" alt="Sarah">
                                        <div class="ml-4">
                                            <p class="text-base font-medium text-gray-900">"Border Buyers made it easy to buy goods from another country. Their escrow gave me peace of mind."</p>
                                            <footer class="mt-2"><p class="text-base font-medium text-gray-500">— Sarah, Canada</p></footer>
                                        </div>
                                    </div>
                                </div>
                                <!-- Slide 2 -->
                                <div class="testimonial-slide hidden">
                                    <div class="flex items-start">
                                        <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=facearea&facepad=2&w=128&h=128&q=80" alt="Ahmed">
                                        <div class="ml-4">
                                            <p class="text-base font-medium text-gray-900">"Great monitoring and fast support. I felt safe throughout the transaction."</p>
                                            <footer class="mt-2"><p class="text-base font-medium text-gray-500">— Ahmed, UAE</p></footer>
                                        </div>
                                    </div>
                                </div>
                                <!-- Slide 3 -->
                                <div class="testimonial-slide hidden">
                                    <div class="flex items-start">
                                        <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=facearea&facepad=2&w=128&h=128&q=80" alt="Lina">
                                        <div class="ml-4">
                                            <p class="text-base font-medium text-gray-900">"Verified agents were professional and ensured quality before payment was released."</p>
                                            <footer class="mt-2"><p class="text-base font-medium text-gray-500">— Lina, Germany</p></footer>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center space-x-3">
                            <button type="button" id="prev-testimonial" class="px-3 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Prev</button>
                            <button type="button" id="next-testimonial" class="px-3 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">Next</button>
                            <div class="ml-4 flex space-x-1" aria-hidden="true">
                                <span class="w-2 h-2 rounded-full bg-green-600" id="dot-0"></span>
                                <span class="w-2 h-2 rounded-full bg-gray-300" id="dot-1"></span>
                                <span class="w-2 h-2 rounded-full bg-gray-300" id="dot-2"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Demo -->
    <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    AI-Powered Transaction Analysis
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Enter a transaction description to see how our AI analyzes it for potential risks.
                </p>
                <div class="mt-8">
                    <form id="ai-demo-form" class="max-w-3xl mx-auto">
                        <div class="flex justify-center">
                            <input type="text" name="transaction_description" id="transaction_description" class="shadow-sm focus:ring-green-600 focus:border-green-600 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="e.g., 'Purchase of 100kg of coffee beans from Colombia'">
                            <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                                Analyze
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Agents Preview -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 text-center sm:px-6 lg:px-8 lg:py-24">
            <div class="space-y-8 sm:space-y-12">
                <div class="space-y-5 sm:mx-auto sm:max-w-xl sm:space-y-4 lg:max-w-5xl">
                    <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Our Agents</h2>
                    <p class="text-xl text-gray-500">Our verified agents are here to help you with your cross-border purchases.</p>
                </div>
                <ul class="mx-auto grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-4 md:gap-x-6 lg:max-w-5xl lg:gap-x-8 lg:gap-y-12 xl:grid-cols-6">
                    <li>
                        <div class="space-y-4">
                            <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1557862921-37829c790f19?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80" alt="Tunde Adekunle">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm">
                                    <h3>Tunde Adekunle</h3>
                                    <p class="text-green-600">Nigeria</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4">
                            <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80" alt="Ngozi Okafor">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm">
                                    <h3>Ngozi Okafor</h3>
                                    <p class="text-green-600">Nigeria</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4">
                            <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80" alt="Wei Zhang">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm">
                                    <h3>Wei Zhang</h3>
                                    <p class="text-green-600">China</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4">
                            <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80" alt="Li Mei">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm">
                                    <h3>Li Mei</h3>
                                    <p class="text-green-600">China</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4">
                            <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="James Brown">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm">
                                    <h3>James Brown</h3>
                                    <p class="text-green-600">UK</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4">
                            <img class="mx-auto h-20 w-20 rounded-full lg:w-24 lg:h-24" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Linda Williams">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm">
                                    <h3>Linda Williams</h3>
                                    <p class="text-green-600">Germany</p>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
@endsection

@push('scripts')
<script src="{{ asset('js/ai-demo.js') }}"></script>
<script>
    // Very small dummy carousel for testimonials (no external deps)
    (function() {
      const slides = Array.from(document.querySelectorAll('.testimonial-slide'));
      if (!slides.length) return;
      let idx = 0;
      const prev = document.getElementById('prev-testimonial');
      const next = document.getElementById('next-testimonial');
      const dots = [0,1,2].map(i => document.getElementById('dot-' + i));

      function render() {
        slides.forEach((s, i) => {
          if (i === idx) {
            s.classList.remove('hidden');
          } else {
            s.classList.add('hidden');
          }
          if (dots[i]) dots[i].className = 'w-2 h-2 rounded-full ' + (i === idx ? 'bg-green-600' : 'bg-gray-300');
        });
      }

      function go(n) {
        idx = (n + slides.length) % slides.length;
        render();
      }

      prev && prev.addEventListener('click', () => go(idx - 1));
      next && next.addEventListener('click', () => go(idx + 1));

      // Auto-advance every 6s
      setInterval(() => go(idx + 1), 6000);

      render();
    })();
</script>
@endpush
