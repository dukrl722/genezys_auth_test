<?php

namespace App\Http\Repositories;

use App\Http\Repositories\contracts\AddressRepositoryInterface;
use App\Models\Address;

class AddressRepository implements AddressRepositoryInterface
{

    public function __construct(
        protected Address $address
    ) {}

    public function create(array $data)
    {
        return $this->address->create([
            'cep' => data_get($data, 'cep'),
            'street' => data_get($data, 'street'),
            'number' => data_get($data, 'number'),
            'district' => data_get($data, 'district'),
            'city' => data_get($data, 'city'),
            'state' => data_get($data, 'state')
        ]);
    }
}
