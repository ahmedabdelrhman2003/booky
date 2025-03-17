<x-filament::page>
    <div class="bg-white shadow rounded-lg p-6  ">
        <!-- Wallet Header -->
        <div class="flex items-center justify-between border-b pb-4">
               <span class="text-2xl font-semibold text-primary-600">
                {{ number_format($wallet, 2) }} EGP
            </span>
            <x-filament::button
                color="primary"
                size="lg"
                icon="heroicon-s-currency-dollar"
                wire:click="requestMoney"
            >
                Request withdraw
            </x-filament::button>

        </div>

        <!-- Actions Section -->
        <div class="mt-4 ">
            <p class="text-danger-600 text-sm"> the minimum amount to withdraw is {{$min_amount}} EGP</p>
        </div>
    </div>
</x-filament::page>

