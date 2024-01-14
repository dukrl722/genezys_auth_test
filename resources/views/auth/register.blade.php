<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('auth.register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="cep" value="{{ __('CEP') }}" />
                <x-input id="cep" class="block mt-1 w-full" type="number" :value="old('cep')" name="cep" required />
            </div>

            <div class="mt-4 flex justify-between">
                <div class="mr-2 w-full">
                    <x-label for="street" value="{{ __('Street') }}" />
                    <x-input id="street" class="block mt-1 w-full" type="text" :value="old('street')" name="street" required readonly />
                </div>
                <div>
                    <x-label for="number" value="{{ __('Number') }}" />
                    <x-input id="number" class="block mt-1 w-20" type="number" :value="old('number')" name="number" required />
                </div>
            </div>

            <div class="mt-4">
                <x-label for="district" value="{{ __('District') }}" />
                <x-input id="district" class="block mt-1 w-full" type="text" :value="old('district')" name="district" readonly />
            </div>

            <div class="mt-4 flex justify-between">
                <div class="mr-2 w-full">
                    <x-label for="city" value="{{ __('City') }}" />
                    <x-input id="city" class="block mt-1 w-full" type="text" :value="old('city')" name="city" readonly />
                </div>
                <div>
                    <x-label for="state" value="{{ __('State') }}" />
                    <x-input id="state" class="block mt-1 w-20" type="text" :value="old('state')" name="state" readonly />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
<script>
    $(document).ready(function () {
        $('#cep').on('change', function () {
            $.ajax({
                url: 'http://localhost:8000/api/address/' + $(this)[0].value,
                success: function (result) {
                    $('#street').val(result.street);
                    $('#district').val(result.district);
                    $('#city').val(result.city);
                    $('#state').val(result.state);
                }
            })
        });
    })
</script>
