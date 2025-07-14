{{-- Country Banner Partial --}}
{{-- Parameters: $bannerImage, $countryModel, $allCountries, $isGradient (optional) --}}

<div class="relative h-96 md:h-[500px] overflow-hidden">
    @if(isset($bannerImage) && $bannerImage)
        <img src="{{ asset($bannerImage) }}" 
             alt="Bannière {{ $countryModel->name_fr }}" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40 pointer-events-none"></div>
    @else
        {{-- Gradient background for countries without banner --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700"></div>
        <div class="absolute inset-0 bg-black bg-opacity-20 pointer-events-none"></div>
    @endif
    
    <!-- Content Center -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div class="text-center text-white max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                {{ $countryModel->emoji }}
                @if(isset($bannerImage) && $bannerImage)
                    <span class="text-white drop-shadow-lg">
                        {{ $countryModel->name_fr }}
                    </span>
                @else
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-pink-400">
                        {{ $countryModel->name_fr }}
                    </span>
                @endif
            </h1>
            <p class="text-xl md:text-2xl mb-8 {{ isset($bannerImage) && $bannerImage ? 'text-white drop-shadow' : 'text-blue-100' }} max-w-3xl mx-auto">
                {{ $countryModel->description }}
            </p>
        </div>
    </div>
    
    <!-- Country Switcher - Top Left (Must be last for z-index) -->
    <div class="absolute top-4 left-4 flex space-x-2 {{ !isset($bannerImage) || !$bannerImage ? 'z-10' : '' }}">
        @if(isset($allCountries) && $allCountries->count() > 0)
            @foreach($allCountries as $country)
                <a href="{{ route('country.index', $country->slug) }}" 
                   class="w-10 h-10 rounded-full flex items-center justify-center text-xl transition-all duration-300 cursor-pointer
                          {{ $country->slug === $countryModel->slug 
                             ? 'bg-white shadow-lg transform scale-110' 
                             : 'bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm' }}"
                   title="{{ $country->name_fr }}">
                    {{ $country->emoji }}
                </a>
            @endforeach
        @else
            <!-- Debug: No countries found -->
            <div class="text-white text-sm">No countries available</div>
        @endif
    </div>
</div>