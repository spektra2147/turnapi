<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## About Turnapi

Bu proje Case Study çalışmasıdır.

### System Req:
- Php  8.2
- PostgreSQL
- Composer 2.x


### How To Install

1. Clone project
2. Install Composer
    
        composer install
3. Copy `.env.example` and set values
4. Create Laravel key
        
        php artisan key:generate
5. Run migration
    
        php artisan migrate
6. Serve Project

        php artisan serve


### How to use Callback API

Endpoint URL = `http://127.0.0.1:8000/api/callback`

Example Request Parameters:

        {
            "price": "55.33",
            "point": "0.00",
            "order_id": "3307333742",
            "user_id": "3b43544b-10b4-45e2-ab81-2b2b3f1917e8",
            "user_phone": "905555555555",
            "ref_code": "0781534070",
            "callback_success_url": "https://case.altpay.dev/success",
            "callback_fail_url": "https://case.altpay.dev/fail",
            "hash": "2918f946ce80bd37e7dbf4ade4888df9d281de0d"
        }

Example PHP Request:

        <?php
            $client = new Client();

            $headers = [
            'Content-Type' => 'application/json'
            ];

            $body = '{
                "price": "55.33",
                "point": "0.00",
                "order_id": "3307333742",
                "user_id": "3b43544b-10b4-45e2-ab81-2b2b3f1917e8",
                "user_phone": "905555555555",
                "ref_code": "0781534070",
                "callback_success_url": "https://case.altpay.dev/success",
                "callback_fail_url": "https://case.altpay.dev/fail",
                "hash": "2918f946ce80bd37e7dbf4ade4888df9d281de0d"
            }';

            $request = new Request('POST', 'http://127.0.0.1:8000/api/callback', $headers, $body);
            $res = $client->sendAsync($request)->wait();
            echo $res->getBody();


### How to run test

        php artisan test

