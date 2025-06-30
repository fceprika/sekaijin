@extends('layout')

@section('title', 'Services - Laravel Website')

@section('content')
<div class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Our Services</h1>
            <p class="text-xl text-gray-600">What we offer</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-center mb-4">Web Development</h3>
                <p class="text-gray-600 text-center">Custom web applications built with Laravel and modern technologies.</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-center mb-4">Database Design</h3>
                <p class="text-gray-600 text-center">Efficient database architecture and optimization for your applications.</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-center mb-4">Mobile Responsive</h3>
                <p class="text-gray-600 text-center">Responsive design that works perfectly on all devices.</p>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <button id="services-btn" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                Get Quote
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#services-btn').click(function() {
        alert('Contact us for a custom quote!');
    });
});
</script>
@endsection