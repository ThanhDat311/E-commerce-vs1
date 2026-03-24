<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <h2 class="text-2xl font-bold mb-4">Multi-Factor Authentication</h2>
                    
                    <p class="mb-6 text-sm text-gray-600">
                        For your security, we have detected a login from a new device or this account requires mandatory MFA. 
                        We have sent a 6-digit code to your registered email address.
                    </p>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('auth.mfa.verify') }}">
                        @csrf

                        <!-- MFA Code -->
                        <div>
                            <x-input-label for="mfa_code" value="Authentication Code" />
                            <x-text-input id="mfa_code" class="block mt-1 w-full text-center text-2xl tracking-widest"
                                            type="text"
                                            name="mfa_code"
                                            required autofocus maxlength="6" autocomplete="one-time-code" />
                            <x-input-error :messages="$errors->get('mfa_code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="w-full justify-center">
                                Verify and Login
                            </x-primary-button>
                        </div>
                    </form>
                    
                    <div class="mt-6 border-t pt-4 text-sm text-gray-500 flex flex-col items-center gap-2">
                        <form method="POST" action="{{ route('auth.mfa.resend') }}">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800 hover:underline focus:outline-none font-medium text-sm transition ease-in-out duration-150">
                                Resend Authentication Code
                            </button>
                        </form>
                        <p><a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-600 hover:underline transition ease-in-out duration-150">Cancel and back to login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
