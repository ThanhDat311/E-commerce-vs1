<x-admin-layout :pageTitle="'System Settings'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Settings' => route('admin.settings.index')]">

    <div x-data="{ activeTab: 'general' }">
        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    General
                </button>
                <button @click="activeTab = 'payment'" 
                        :class="activeTab === 'payment' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Payment & Tax
                </button>
                <button @click="activeTab = 'notification'" 
                        :class="activeTab === 'notification' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Notifications
                </button>
                <button @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Security
                </button>
                <button @click="activeTab = 'backup'" 
                        :class="activeTab === 'backup' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Backup & Maintenance
                </button>
            </nav>
        </div>

        <!-- General Settings -->
        <div x-show="activeTab === 'general'" x-cloak class="space-y-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                @csrf
                <input type="hidden" name="group" value="general">
                
                <h3 class="text-lg font-medium text-gray-900 mb-4">General Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                        <input type="text" name="site_name" value="{{ \App\Models\Setting::get('site_name', 'E-commerce Platform') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
                        <input type="email" name="support_email" value="{{ \App\Models\Setting::get('support_email', 'support@example.com') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ \App\Models\Setting::get('currency_symbol', '$') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                        <select name="timezone" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="UTC" {{ \App\Models\Setting::get('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Asia/Ho_Chi_MinH" {{ \App\Models\Setting::get('timezone') == 'Asia/Ho_Chi_MinH' ? 'selected' : '' }}>Asia/Ho_Chi_Minh</option>
                            <option value="America/New_York" {{ \App\Models\Setting::get('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            <option value="Europe/London" {{ \App\Models\Setting::get('timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Payment Settings -->
        <div x-show="activeTab === 'payment'" x-cloak class="space-y-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                @csrf
                <input type="hidden" name="group" value="payment">
                
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Configuration</h3>
                
                <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Enable Stripe</h4>
                        <p class="text-xs text-gray-500">Allow customers to pay via Credit Card using Stripe.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_stripe" value="1" class="sr-only peer" {{ \App\Models\Setting::get('enable_stripe') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stripe Public Key</label>
                        <input type="text" name="stripe_key" value="{{ \App\Models\Setting::get('stripe_key') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stripe Secret Key</label>
                        <input type="password" name="stripe_secret" value="******" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Enable PayPal</h4>
                        <p class="text-xs text-gray-500">Allow customers to pay via PayPal.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_paypal" value="1" class="sr-only peer" {{ \App\Models\Setting::get('enable_paypal') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Client ID</label>
                        <input type="text" name="paypal_client_id" value="{{ \App\Models\Setting::get('paypal_client_id') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Secret</label>
                        <input type="password" name="paypal_secret" value="******" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Save Payment Settings</button>
                </div>
            </form>
        </div>

        <!-- Notification Settings -->
        <div x-show="activeTab === 'notification'" x-cloak class="space-y-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                @csrf
                <input type="hidden" name="group" value="notification">
                
                <h3 class="text-lg font-medium text-gray-900 mb-4">Email Notifications (SMTP)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Host</label>
                        <input type="text" name="mail_host" value="{{ \App\Models\Setting::get('mail_host', 'smtp.mailtrap.io') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Port</label>
                        <input type="number" name="mail_port" value="{{ \App\Models\Setting::get('mail_port', '2525') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Username</label>
                        <input type="text" name="mail_username" value="{{ \App\Models\Setting::get('mail_username') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Password</label>
                        <input type="password" name="mail_password" value="******" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail From Address</label>
                        <input type="email" name="mail_from_address" value="{{ \App\Models\Setting::get('mail_from_address', 'hello@example.com') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail From Name</label>
                        <input type="text" name="mail_from_name" value="{{ \App\Models\Setting::get('mail_from_name', 'System') }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Save Notification Settings</button>
                </div>
            </form>
        </div>

        <!-- Security Settings -->
        <div x-show="activeTab === 'security'" x-cloak class="space-y-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                @csrf
                <input type="hidden" name="group" value="security">
                
                <h3 class="text-lg font-medium text-gray-900 mb-4">Security Policies</h3>
                
                <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Allow Vendor Registration</h4>
                        <p class="text-xs text-gray-500">If disabled, new vendors cannot sign up.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="allow_vendor_registration" value="1" class="sr-only peer" {{ \App\Models\Setting::get('allow_vendor_registration', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Require Email Verification</h4>
                        <p class="text-xs text-gray-500">Users must verify email before accessing dashboard.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="require_email_verification" value="1" class="sr-only peer" {{ \App\Models\Setting::get('require_email_verification', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Save Security Settings</button>
                </div>
            </form>
        </div>
        
        <!-- Backup & Maintenance Settings -->
        <div x-show="activeTab === 'backup'" x-cloak class="space-y-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                @csrf
                <input type="hidden" name="group" value="general"> <!-- Maintenance is updated with general usually, but logic in controller checks request -->
                <!-- Actually maintenance logic is in general update block in controller if passed -->
                
                <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Mode</h3>
                <div class="flex items-center justify-between mb-4 p-4 bg-amber-50 rounded-lg border border-amber-100">
                    <div>
                        <h4 class="text-sm font-medium text-amber-900">Maintenance Mode</h4>
                        <p class="text-xs text-amber-700">Put the site in maintenance mode. Only admins can access.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ app()->isDownForMaintenance() ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                    </label>
                </div>
                
                 <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Update Maintenance Schema</button>
                </div>
            </form>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                 @csrf
                 <input type="hidden" name="group" value="backup">
                 <input type="hidden" name="trigger_backup" value="1">
                 
                 <h3 class="text-lg font-medium text-gray-900 mb-4">System Backup</h3>
                 <p class="text-sm text-gray-500 mb-4">Trigger a manual backup of the database and files.</p>
                 
                 <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg font-medium hover:bg-gray-900 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        Start Backup Now
                    </button>
                 </div>
            </form>
        </div>

    </div>
</x-admin-layout>
