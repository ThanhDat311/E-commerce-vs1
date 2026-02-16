<x-base-layout>
    <x-store.navbar />

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-gray-500 text-sm">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-900">Account</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Saved Addresses</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8" x-data="addressManager()">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 shrink-0 sticky top-4 self-start">
                    @include('profile.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Addresses</h2>
                                <p class="text-sm text-gray-500 mt-1">Manage your shipping and billing locations.</p>
                            </div>
                            <button @click="openAddModal()"
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add New Address
                            </button>
                        </div>

                        <!-- Address Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($addresses as $address)
                                <div class="border rounded-lg p-5 relative {{ $address->is_default ? 'border-orange-300 bg-orange-50/30' : 'border-gray-200' }}">
                                    <!-- Label & Default Badge -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="font-semibold text-gray-900">{{ $address->address_label ?? 'Address' }}</span>
                                        @if($address->is_default)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">Default</span>
                                        @endif
                                    </div>

                                    <!-- Address Details -->
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p>{{ $address->recipient_name }}</p>
                                        <p>{{ $address->address_line1 }}</p>
                                        <p>
                                            {{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}
                                            {{ $address->zip_code }}
                                        </p>
                                        @if($address->country)
                                            <p>{{ $address->country }}</p>
                                        @endif
                                        @if($address->phone_contact)
                                            <p class="flex items-center gap-1 mt-2 text-gray-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                {{ $address->phone_contact }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center gap-4">
                                        <button @click="openEditModal({{ $address->toJson() }})"
                                                class="text-sm text-orange-600 hover:text-orange-800 font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Delete this address?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                        @unless($address->is_default)
                                            <form action="{{ route('addresses.default', $address) }}" method="POST" class="ml-auto">
                                                @csrf
                                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Set as Default</button>
                                            </form>
                                        @endunless
                                    </div>
                                </div>
                            @empty
                                <!-- Add New Address Card (Empty State) -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-colors"
                                     @click="openAddModal()">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600">Add New Address</p>
                                </div>
                            @endforelse

                            @if($addresses->count() > 0)
                                <!-- Add New Address Card -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-colors"
                                     @click="openAddModal()">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600">Add New Address</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Address Modal -->
                <div x-show="showModal" x-cloak
                     class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeModal()"></div>
                        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full p-6 z-10"
                             @click.away="closeModal()">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-bold text-gray-900" x-text="isEditing ? 'Edit Address' : 'Add New Address'"></h3>
                                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form :action="formAction" method="POST" class="space-y-4">
                                @csrf
                                <template x-if="isEditing">
                                    <input type="hidden" name="_method" value="PUT">
                                </template>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Label</label>
                                    <input type="text" name="address_label" x-model="form.address_label"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                           placeholder="e.g. Home, Work Office">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="recipient_name" x-model="form.recipient_name" required
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                               placeholder="e.g. Alex Morgan">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                        <input type="text" name="phone_contact" x-model="form.phone_contact" required
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                               placeholder="e.g. +1 (555) 000-0000">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                                    <input type="text" name="address_line1" x-model="form.address_line1" required
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                           placeholder="e.g. 123 Main St, Apt 4B">
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                                        <input type="text" name="city" x-model="form.city" required
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                               placeholder="e.g. New York">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">State / Province</label>
                                        <input type="text" name="state" x-model="form.state"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                               placeholder="e.g. NY">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                                        <input type="text" name="zip_code" x-model="form.zip_code"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                               placeholder="e.g. 10001">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                    <input type="text" name="country" x-model="form.country"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                           placeholder="e.g. United States">
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="is_default" value="0">
                                    <input type="checkbox" name="is_default" value="1" x-model="form.is_default"
                                           id="is_default_address"
                                           class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                    <label for="is_default_address" class="text-sm text-gray-700">Set as Default Address</label>
                                </div>

                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                    <button type="button" @click="closeModal()"
                                            class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                        <span x-text="isEditing ? 'Save Changes' : 'Save Address'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-store.footer />

    <script>
        function addressManager() {
            return {
                showModal: false,
                isEditing: false,
                formAction: '{{ route("addresses.store") }}',
                form: {
                    address_label: '',
                    recipient_name: '',
                    phone_contact: '',
                    address_line1: '',
                    city: '',
                    state: '',
                    zip_code: '',
                    country: '',
                    is_default: false,
                },
                openAddModal() {
                    this.isEditing = false;
                    this.formAction = '{{ route("addresses.store") }}';
                    this.resetForm();
                    this.showModal = true;
                },
                openEditModal(address) {
                    this.isEditing = true;
                    this.formAction = '/my-addresses/' + address.id;
                    this.form = {
                        address_label: address.address_label || '',
                        recipient_name: address.recipient_name || '',
                        phone_contact: address.phone_contact || '',
                        address_line1: address.address_line1 || '',
                        city: address.city || '',
                        state: address.state || '',
                        zip_code: address.zip_code || '',
                        country: address.country || '',
                        is_default: address.is_default ? true : false,
                    };
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                },
                resetForm() {
                    this.form = {
                        address_label: '',
                        recipient_name: '',
                        phone_contact: '',
                        address_line1: '',
                        city: '',
                        state: '',
                        zip_code: '',
                        country: '',
                        is_default: false,
                    };
                }
            };
        }
    </script>
</x-base-layout>
