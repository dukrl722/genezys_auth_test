<?php

namespace App\Http\Services;

use App\Http\Repositories\contracts\AddressRepositoryInterface;

class AddressService
{
    public function __construct(
        protected AddressRepositoryInterface $addressRepository
    ) {}

    public function create(array $data) {
        return $this->addressRepository->create($data);
    }

    public function AddressAPI(string $cep) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://viacep.com.br/ws/' . $cep . '/json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}
