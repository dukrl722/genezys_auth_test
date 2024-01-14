<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($users as $user)
                <div class="scale-100 p-6 bg-gray-800 rounded-lg pt-10 pb-10 mb-10 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                            {{ $user->email }}
                        </p>
                    </div>
                    <div>
                        <p class="text-white">
                            {{ $user->address->fullAddress }}
                        </p>
                    </div>
                </div>
                <p class="h-10"></p>
            @endforeach
        </div>
    </div>
</x-app-layout>
