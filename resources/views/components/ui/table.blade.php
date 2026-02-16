<div class="overflow-x-auto relative rounded-lg border border-gray-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                <tr>
                    {{ $thead }}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                {{ $tbody }}
            </tbody>
        </table>
    </div>

    @isset($footer)
        <div class="border-t border-gray-100">
            {{ $footer }}
        </div>
    @endisset
</div>
