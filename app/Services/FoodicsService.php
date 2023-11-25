<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FoodicsService {


    public function getBaseUrl(): string
    {
        return config('foodics.foodics_base_url');
    }

    public function getAuthorizationUrl(): string
    {
        return config('foodics.foodics_base__authorize_url') . '/authorize?client_id=' . config('foodics.foodics_client_id') . '&state=initiate';
    }

    public function getAccessTokenUrl()
    {
        return $this->getBaseUrl() . '/oauth/token';
    }

    public function getApiUrl()
    {
        return $this->getBaseUrl() . '/v5';
    }

    public function getRedirectUrl()
    {
        return config('foodics.foodics_redirect_url');
    }

    public function getAccessToken(string $code): Response
    {

        // return Http::accept('application/json')
        //     ->post('https://api-sandbox.foodics.com/oauth/token', [
        //         "grant_type"    => "authorization_code",
        //         "code"          => $code,
        //         "client_id"     => '9731a158-b71e-4a97-9819-cde8b34b97d4',
        //         "client_secret" =>'bXx9a4Zpz6j5AbeokKKFNxLSBMShiAbgdDkpkkWk',
        //         "redirect_uri"  => 'https://restaurant-dashboard.remmsh.com/foodics/success'
        //     ])->throw();


        return Http::accept('application/json')
            ->post($this->getAccessTokenUrl(), [
                "grant_type"    => "authorization_code",
                "code"          => $code,
                "client_id"     => config('foodics.foodics_client_id'),
                "client_secret" => config('foodics.foodics_secret'),
                "redirect_uri"  => $this->getRedirectUrl()
            ])->throw();
    }

    public function getBusinessInfo(string $accessToken): Response
    {
        return Http::withToken($accessToken)
            ->contentType('application/json')
            ->accept('application/json')
            ->get($this->getApiUrl() . '/whoami')->throw();
    }


    public function getBusinessBranches(string $accessToken): Response
    {
        return Http::withToken($accessToken)
            ->contentType('application/json')
            ->accept('application/json')
            ->get($this->getApiUrl() . '/branches')->throw();
    }


}
