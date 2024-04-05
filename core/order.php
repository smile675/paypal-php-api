<?php
class Order
{
    // order for subscribe MYR 10.00
    public function createOrderforSubscribe($accessToken, $rootUrl)
    {
        $orderUrl = $rootUrl . "/v2/checkout/orders";
        // $orderUrl = "https://api-m.sandbox.paypal.com/v2/checkout/orders";
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $accessToken,
            "PayPal-Request-Id: " . uniqid()
        ];
        $data = '{
            "intent": "CAPTURE",
            "purchase_units": [
                {
                    "amount": {
                        "currency_code": "MYR",
                        "value": "10.00"
                    }
                }
            ],
            "payment_source": {
                "paypal": {
                    "experience_context": {
                        "payment_method_preference": "IMMEDIATE_PAYMENT_REQUIRED",
                        "brand_name": "EXAMPLE INC",
                        "locale": "en-US",
                        "landing_page": "LOGIN",
                        "user_action": "PAY_NOW",
                        "return_url": "https://example.com/returnUrl",
                        "cancel_url": "https://example.com/cancelUrl"
                    }
                }
            }
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $orderUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        return $response;
    }
}
