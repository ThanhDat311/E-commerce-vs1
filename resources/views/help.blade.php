<x-base-layout>
    @push('meta')
    <meta name="description" content="Help & Support Center — find answers to common questions, browse FAQs, and get assistance." />
    @endpush

    <x-store.navbar />

    <div class="bg-white min-h-screen">

        {{-- Hero --}}
        <div class="bg-gradient-to-br from-purple-600 to-indigo-700 py-16 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold tracking-tight mb-4">Help & Support</h1>
                <p class="text-purple-200 text-lg max-w-xl mx-auto">
                    Find answers, browse FAQs, or reach out to our support team.
                </p>
                @auth
                <div class="mt-6">
                    <a href="{{ route('tickets.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white text-indigo-700 text-sm font-semibold hover:bg-indigo-50 transition-colors shadow">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Open a Support Ticket
                    </a>
                </div>
                @endauth
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            {{-- FAQ Categories --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-16">
                <div class="text-center p-6 rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-sm transition-all cursor-pointer group">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Orders & Shipping</h3>
                    <p class="text-xs text-gray-500">Track orders, delivery times, returns</p>
                </div>
                <div class="text-center p-6 rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-sm transition-all cursor-pointer group">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Payments & Billing</h3>
                    <p class="text-xs text-gray-500">Payment methods, invoices, refunds</p>
                </div>
                <div class="text-center p-6 rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-sm transition-all cursor-pointer group">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Account & Security</h3>
                    <p class="text-xs text-gray-500">Password, profile, privacy settings</p>
                </div>
            </div>

            {{-- FAQ Accordion --}}
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Frequently Asked Questions</h2>
                <div class="space-y-3" x-data="{ open: null }">
                    @foreach([
                        ['q' => 'How do I track my order?', 'a' => 'After placing an order, you can track it in the "My Orders" section of your account. You will also receive email updates at each stage of delivery.'],
                        ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit/debit cards, bank transfers, and VNPay. Cash on delivery (COD) is also available for most locations.'],
                        ['q' => 'How can I return a product?', 'a' => 'You can initiate a return from your order detail page within 7 days of delivery. The item must be unused and in original packaging.'],
                        ['q' => 'How do I become a vendor?', 'a' => 'Register a new account and select "Vendor" as your role. Your account will be reviewed by our team and approved within 1–2 business days.'],
                        ['q' => 'How long does shipping take?', 'a' => 'Standard shipping takes 3–5 business days. Express shipping is available at checkout for 1–2 business day delivery.'],
                        ['q' => 'How do I contact customer support?', 'a' => 'You can reach us via the Contact Us page, or submit a support ticket if you have an account. We typically respond within 24 hours.'],
                    ] as $i => $item)
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <button
                            @click="open = open === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex items-center justify-between px-5 py-4 text-left text-sm font-medium text-gray-900 hover:bg-gray-50 transition-colors"
                        >
                            <span>{{ $item['q'] }}</span>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200"
                                :class="open === {{ $i }} ? 'rotate-180' : ''"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open === {{ $i }}" x-transition.opacity class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">
                            {{ $item['a'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Still Need Help --}}
            <div class="rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 p-8 text-center">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Still need help?</h3>
                <p class="text-sm text-gray-500 mb-6">Our support team is ready to assist you.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('contact.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact Us
                    </a>
                    @auth
                    <a href="{{ route('tickets.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white border border-indigo-200 text-indigo-700 text-sm font-semibold hover:bg-indigo-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        Open a Ticket
                    </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>

    <x-store.footer />

</x-base-layout>
