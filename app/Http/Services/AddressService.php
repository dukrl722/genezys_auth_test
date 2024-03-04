<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class AddressService
{
    public function AddressAPI(string $cep): mixed
    {
        return Http::get(config('services.address.path') . $cep . '/json')->json();
    }
}
