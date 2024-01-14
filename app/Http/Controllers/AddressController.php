<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(
        protected AddressService $addressService
    ) {}

    public function getAddressInfo(string $cep) {

        try {

            if (!$address = $this->addressService->AddressAPI($cep)) {
                return response()->json([
                    'message' => 'Address not found'
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            return response()->json([
                'cep' => $address['cep'],
                'street' => $address['logradouro'],
                'district' => $address['bairro'],
                'city' => $address['localidade'],
                'state' => $address['uf']
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
