<?php

namespace App\Http\Controllers;

use App\Http\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    public function __construct(
        protected AddressService $addressService
    ) {}

    public function getAddressInfo(string $cep): JsonResponse
    {

        try {

            if (!$address = $this->addressService->AddressAPI($cep)) {
                return response()->json([
                    'message' => 'Address not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'cep' => $address['cep'],
                'street' => $address['logradouro'],
                'district' => $address['bairro'],
                'city' => $address['localidade'],
                'state' => $address['uf']
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {
            
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
