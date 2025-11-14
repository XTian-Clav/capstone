<div>
    <div class="flex items-center gap-2">
        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
            Read the
        </span>

        <x-filament::modal width="4xl">
            <x-slot name="heading">
                Terms and Conditions
            </x-slot>

            <x-slot name="trigger">
                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white underline cursor-pointer">
                    Terms and Conditions
                </span>
            </x-slot>

            {{-- Modal content --}}
            <div class="p-4">
                <p>Here are the terms and conditions...</p>
            </div>
        </x-filament::modal>
    </div>
</div>
