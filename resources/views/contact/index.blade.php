<x-base-layout>
    @push('meta')
    <meta name="description" content="Get in touch with us. We'd love to hear from you — questions, feedback, or support." />
    @endpush

    <x-store.navbar />

    <div class="bg-white min-h-screen">

        {{-- Hero --}}
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 py-16 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold tracking-tight mb-4">Contact Us</h1>
                <p class="text-indigo-200 text-lg max-w-xl mx-auto">
                    Have a question, feedback, or just want to say hi? We're here to help.
                </p>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Contact Info --}}
                <div class="lg:col-span-1 space-y-8">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Get In Touch</h2>
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">Email</p>
                                    <p class="text-sm text-gray-500">support@zentro.com</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">Phone</p>
                                    <p class="text-sm text-gray-500">+84 (0) 123 456 789</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">Business Hours</p>
                                    <p class="text-sm text-gray-500">Mon–Fri: 9:00 AM – 6:00 PM</p>
                                    <p class="text-sm text-gray-500">Sat: 9:00 AM – 12:00 PM</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">Address</p>
                                    <p class="text-sm text-gray-500">123 Nguyen Hue, District 1<br>Ho Chi Minh City, Vietnam</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div class="rounded-xl bg-indigo-50 border border-indigo-100 p-5">
                        <p class="text-sm font-semibold text-indigo-800 mb-3">Need quick help?</p>
                        <div class="space-y-2">
                            <a href="{{ route('help.index') }}" class="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Help & Support Center
                            </a>
                            @auth
                            <a href="{{ route('tickets.index') }}" class="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                Submit a Support Ticket
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Send us a message</h2>

                        @if(session('success'))
                            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-sm text-green-700 flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                    <input id="contact-name" type="text" name="name" value="{{ old('name') }}"
                                        placeholder="Your full name"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-400 @enderror">
                                </div>
                                <div>
                                    <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                    <input id="contact-email" type="email" name="email" value="{{ old('email', auth()->user()?->email) }}"
                                        placeholder="your@email.com"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-400 @enderror">
                                </div>
                            </div>

                            <div>
                                <label for="contact-subject" class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                                <input id="contact-subject" type="text" name="subject" value="{{ old('subject') }}"
                                    placeholder="How can we help you?"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('subject') border-red-400 @enderror">
                            </div>

                            <div>
                                <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                                <textarea id="contact-message" name="message" rows="6"
                                    placeholder="Please describe your inquiry in as much detail as possible..."
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <x-store.footer />
</x-base-layout>
