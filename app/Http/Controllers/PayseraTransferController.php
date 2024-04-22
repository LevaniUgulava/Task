<?php

namespace App\Http\Controllers;

use App\Services\PayseraService;
use Illuminate\Http\Request;

class PayseraTransferController extends Controller
{
    protected $payseraService;

    public function __construct(PayseraService $payseraService)
    {
        $this->payseraService = $payseraService;
    }

    public function createTransfer(Request $request)
    {
        // Validate incoming request if needed

        $transferData = [
            "amount" => [
                "amount" => $request->input('amount'),
                "currency" => $request->input('currency'),
            ],
            "beneficiary" => [
                "type" => "bank",
                "name" => $request->input('beneficiary_name'),
                "bank_account" => [
                    "iban" => $request->input('iban'),
                ],
                "additional_information" => [
                    "type" => $request->input('beneficiary_type'),
                    "city" => $request->input('city'),
                    "state" => $request->input('state'),
                    "country" => $request->input('country'),
                    "postal_code" => $request->input('postal_code'),
                    "bank_branch_code" => $request->input('bank_branch_code'),
                ],
                "client_identifier" => [
                    "date_and_place_of_birth" => [
                        "date_of_birth" => $request->input('date_of_birth'),
                        "city_of_birth" => $request->input('city_of_birth'),
                        "country_of_birth" => $request->input('country_of_birth'),
                    ],
                ],
            ],
            "payer" => [
                "account_number" => $request->input('account_number'),
            ],
            "urgency" => $request->input('urgency'),
            "purpose" => [
                "details" => $request->input('purpose_details'),
            ],
        ];

        $result = $this->payseraService->createTransfer($transferData);

        // Handle the result as needed
        return response()->json($result);
    }
}
