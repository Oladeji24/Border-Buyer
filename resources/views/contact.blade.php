@extends('layouts.app')

@section('title', 'Contact Us - Border Buyers')
@section('meta_title', 'Contact Us - Border Buyers')
@section('meta_description', 'Get in touch with Border Buyers for secure cross-border transaction services. Visit our office in Lagos, Nigeria or contact us via email or phone.')
@section('meta_keywords', 'contact border buyers, border buyers office, cross-border transactions, escrow service contact')

@section('content')
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-600 to-cyan-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">
                    <span class="block">Contact Us</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-green-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Info & Form Section -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8">
                <!-- Contact Information -->
                <div>
                    <div class="max-w-full mx-auto rounded-lg overflow-hidden">
                        <div class="px-6 py-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>

                            <div class="space-y-6">
                                <!-- Nigeria Office -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                                            <i class="fas fa-map-marker-alt h-6 w-6"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">Nigeria Office</h3>
                                        <p class="mt-1 text-gray-600">
                                            9 Adebayo Street, Fafunwa Bus Stop<br>
                                            Ipaja, Lagos, Nigeria
                                        </p>
                                    </div>
                                </div>

                                <!-- China Office -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                                            <i class="fas fa-map-marker-alt h-6 w-6"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">China Office</h3>
                                        <p class="mt-1 text-gray-600">
                                            123 Guangzhou International Trade Center<br>
                                            Tianhe District, Guangzhou, China
                                        </p>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                                            <i class="fas fa-envelope h-6 w-6"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">Email</h3>
                                        <p class="mt-1 text-gray-600">
                                            info@border-buyer.com
                                        </p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                                            <i class="fas fa-phone h-6 w-6"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">Phone</h3>
                                        <p class="mt-1 text-gray-600">
                                            +234 802 843 1302
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Follow Us</h3>
                                <div class="flex space-x-6">
                                    <a href="#" class="text-gray-400 hover:text-green-600 transition">
                                        <span class="sr-only">Facebook</span>
                                        <i class="fab fa-facebook-f text-2xl"></i>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-green-600 transition">
                                        <span class="sr-only">Instagram</span>
                                        <i class="fab fa-instagram text-2xl"></i>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-green-600 transition">
                                        <span class="sr-only">Twitter</span>
                                        <i class="fab fa-twitter text-2xl"></i>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-green-600 transition">
                                        <span class="sr-only">LinkedIn</span>
                                        <i class="fab fa-linkedin-in text-2xl"></i>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-green-600 transition">
                                        <span class="sr-only">WhatsApp</span>
                                        <i class="fab fa-whatsapp text-2xl"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="mt-10 lg:mt-0">
                    <div class="max-w-full mx-auto rounded-lg overflow-hidden shadow-lg">
                        <div class="px-6 py-8 bg-white">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                            <form action="#" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <div class="mt-1">
                                        <input type="text" name="name" id="name" autocomplete="name" required
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Your name">
                                    </div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1">
                                        <input type="email" name="email" id="email" autocomplete="email" required
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="your.email@example.com">
                                    </div>
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <div class="mt-1">
                                        <input type="tel" name="phone" id="phone" autocomplete="tel"
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Your phone number">
                                    </div>
                                </div>

                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                    <div class="mt-1">
                                        <input type="text" name="subject" id="subject" required
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Subject of your message">
                                    </div>
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                    <div class="mt-1">
                                        <textarea id="message" name="message" rows="4" required
                                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Your message"></textarea>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Map Section -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Find Us on the Map</h2>
            <div class="rounded-lg overflow-hidden shadow-md">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.364502935658!2d3.325855314770758!3d6.627466695186625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b922f5c5b2b45%3A0x9b8a5e4f5e4f5e4f!2sIpaja%2C%20Lagos%2C%20Nigeria!5e0!3m2!1sen!2sus!4v1629784956789!5m2!1sen!2sus" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
@endsection