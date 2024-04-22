<?php

namespace App\Services;

use App\Models\Order;
use GuzzleHttp\Client;

class PayseraService
{
    protected $client;
    protected $accessToken; // Add this property

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;

        $this->client = new Client([
            'base_uri' => 'https://wallet.paysera.com/transfer/rest/v1/',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken, // Use the provided access token
            ],
        ]);
    }

    public function createTransfer(array $transferData)
    {
        $response = $this->client->post('transfers', [
            'json' => $transferData,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
    public static function initiateTransaction(Order $order)
    {
        // Placeholder for Paysera API integration logic
        // This is where you would make a request to Paysera API to start the transaction
        // You'll need to replace this with your actual Paysera API integration

        // For demonstration, let's assume Paysera responds with a success message
        return "Transaction initiated successfully for order #" . $order->id;
    }
}
