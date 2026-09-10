<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TilakaCtrl extends Controller
{
    protected $baseUrl;
    protected $clientId;
    protected $grandType;
    protected $clientSecret;
    protected $scope;
    protected $accessToken = null;
    public function __construct()
    {
        $this->clientId = "50ec5a45-7848-4480-842d-c4dbc1e7834d";
        $this->grandType = "client_credentials";
        $this->clientSecret = "78fedfdb-e320-4123-83b6-76dbba31c9fc";
        $this->scope = "ekyc-result";
        $this->baseUrl = "https://sb-api.tilaka.id";
        $this->accessToken ="";
    }

    protected function getAccessToken()
    {
        $data = [
            "Content-Type" => "Application/x-www-form-urlencoded",
            "client_id" => $this->clientId,
            "grant_type" => $this->grandType,
            "client_secret" => $this->clientSecret,
            "scope" => $this->scope
        ];
        $url = $this->baseUrl . "/auth";
        $response = Http::asForm()
            ->withoutVerifying()
            ->withOptions(["verify" => false])
            ->post($url, $data);
        if ($response->status() != 200) {
            $result = [
                "status" => $response->status(),
                "result" => $response->json()
            ];
            throw new HttpResponseException($this->respond($result['result'], $response->status(), $result['result']['error']));
        }
        $token = $response->json()['access_token'];
        $this->accessToken = "";
        Log::error('GET ACCESS TOKEN : ' . json_encode($token, JSON_PRETTY_PRINT));
        $this->accessToken = $token;
    }

    public function upload(Request $request)
    {
        return  $this->accessToken;
    }

    public function apiTools(Request $request)
    {
        try {
            $isRefreshing = false;
            $methods = strtolower($request->method);
            $baseURL = $this->baseUrl;
            $headers = [
                "token" => "Bearer " . $this->accessToken,
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . $this->accessToken,
            ];
            $url = $baseURL . $request->url;

            if (empty($dataJsonSend)) {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url);
            } else {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url, $dataJsonSend);
                }
                $test = [
                    "header" => $headers
                ];
                $responseJson = $response->json();
                if ($response->status() == 401 && !$isRefreshing) {
                    $this->getAccessToken();
                    $isRefreshing = true;
                    return $this->apiTools($request);
                }elseif ($response->status() == 200){
                    $metadata['data'] = $responseJson['data'] ?? $responseJson;
                }else{
                    $metadata['data'] = [];
                }
            $metadata['code'] = $response->status();
            $metadata['message'] = 'Sukses';
            $metadata['token'] = $this->accessToken;
        } catch (Exception $e) {
            $metadata['message'] = 'Gagal';
            $metadata['code'] = $e->getCode() || 400;
            $metadata['data'] = $e->getMessage() . ' ' . $e->getLine();
        }
        return $this->respond($metadata['data'], $metadata['code'], $metadata['message']);
    }
}
