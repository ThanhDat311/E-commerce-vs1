<div class="relative bg-gray-900 overflow-hidden" x-data="{
    activeSlide: 0,
    slides: [
        {
            image: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=600&fit=crop', 
            title: 'Summer Collection Launch Event',
            subtitle: 'Discover the hottest trends of the season. Up to 50% off on all summer essentials.',
            cta: 'Shop Now',
            link: '#'
        },
        {
            image: 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=1920&h=600&fit=crop', 
            title: 'New Arrivals: Tech Gadgets',
            subtitle: 'Upgrade your life with the latest smartphones, laptops, and smart home devices.',
            cta: 'Explore Tech',
            link: '#'
        },
        {
            image: 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=1920&h=600&fit=crop', 
            title: 'Premium Home Decor',
            subtitle: 'Transform your living space with our exclusive range of furniture and accessories.',
            cta: 'View Collection',
            link: '#'
        }
    ],
    timer: null,
    startAutoSlide() {
        this.timer = setInterval(() => {
            this.next();
        }, 5000);
    },
    stopAutoSlide() {
        clearInterval(this.timer);
    },
    next() {
        this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
    },
    prev() {
        this.activeSlide = (this.activeSlide === 0) ? this.slides.length - 1 : this.activeSlide - 1;
    }
}" x-init="startAutoSlide()" @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()">

    <!-- Slides -->
    <div class="relative h-[500px] sm:h-[600px]">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 transform scale-105"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-700"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-105"
                 class="absolute inset-0">
                
                <!-- Background Image (Using placeholder for now if real image missing) -->
                <!-- Ideally use real images. For demo, we use a gray background placeholder if image fails -->
                <div class="absolute inset-0 bg-gray-600">
                    <img :src="slide.image" alt="Hero Image" class="w-full h-full object-cover object-center opacity-60">
                </div>
                
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/40 to-transparent"></div>

                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-xl">
                        <span class="inline-block py-1 px-3 rounded bg-blue-600 text-white text-xs font-semibold tracking-wider uppercase mb-4">
                            New Arrival
                        </span>
                        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl" x-text="slide.title"></h1>
                        <p class="mt-4 text-xl text-gray-300" x-text="slide.subtitle"></p>
                        <div class="mt-8 flex gap-4">
                            <a :href="slide.link" class="inline-block bg-blue-600 border border-transparent rounded-md py-3 px-8 font-medium text-white hover:bg-blue-700">
                                <span x-text="slide.cta"></span> &rarr;
                            </a>
                            <a href="#" class="inline-block bg-white/10 backdrop-blur-sm border border-white/20 rounded-md py-3 px-8 font-medium text-white hover:bg-white/20">
                                View Lookbook
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Controls -->
    <div class="absolute bottom-5 left-0 right-0 flex justify-center space-x-3">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="activeSlide = index" 
                    :class="{'bg-blue-600 w-8': activeSlide === index, 'bg-white/50 w-2': activeSlide !== index}"
                    class="h-2 rounded-full transition-all duration-300"></button>
        </template>
    </div>

    <button @click="prev()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white p-2 rounded-full hidden sm:block">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <button @click="next()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white p-2 rounded-full hidden sm:block">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>
