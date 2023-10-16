<?php

namespace App\Services;

use App\Services\ApiGetwayService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ExternalClientService
{

    private $base_url;
    /**
     * @var ApiGetwayService
     */
    private ApiGetwayService $apiGetwayService;
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @param \App\Services\ApiGetwayService $apiGetwayService
     * @param Client $client
     */
    public function __construct(
        ApiGetwayService $apiGetwayService,
        Client           $client

    )
    {
        $this->apiGetwayService = $apiGetwayService;
        $this->client = $client;
        $this->setParameters();
    }

    /**
     * @return void
     */
    private function checkIfSessionExists()
    {
        if (empty(session('session_token'))) {
            $this->authenticate();
        }
    }

    /**
     * @return array
     */
    public static function getHttpHeaders(): array
    {
        $bearerToken = Session::get('session_token');

        return [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $bearerToken,
            ],
            'http_errors' => false,
        ];
    }

    /**
     * @return mixed|void|null
     */
    public function authenticate()
    {
        try {
            $url = $this->apiGetwayService->getBaseUrl();

            $response = $this->client->post($url . 'authenticate', [

                'form_params' => [
                    "UserName" => $this->apiGetwayService->getUserName() ?? '',
                    "Password" => $this->apiGetwayService->getPassword() ?? ''
                ],
                'header' => [
                    "key" => "Content-Type",
                    "value" => "application/json"
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());
            $this->apiGetwayService->setToken($result->SessionToken);
            Session::put('session_token', $this->apiGetwayService->getToken());

        } catch (RequestException $e) {
            return $this->StatusCodeHandling($e);
        } catch (GuzzleException $e) {
        }

    }

    /**
     * @param $e
     * @return mixed|void
     */
    public function StatusCodeHandling($e)
    {
        if ($e->getResponse()->getStatusCode() == "400") {
            $this->authenticate();
        } elseif ($e->getResponse()->getStatusCode() == "422") {
            return json_decode($e->getResponse()->getBody(true)->getContents());
        } elseif ($e->getResponse()->getStatusCode() == "500") {
            return json_decode($e->getResponse()->getBody(true)->getContents());
        } elseif ($e->getResponse()->getStatusCode() == "401") {
            return json_decode($e->getResponse()->getBody(true)->getContents());
        } elseif ($e->getResponse()->getStatusCode() == "403") {
            return json_decode($e->getResponse()->getBody(true)->getContents());
        } else {
            return json_decode($e->getResponse()->getBody(true)->getContents());
        }
    }


    /**
     * @param $endPoint
     * @param array $query
     * @return Response|PromiseInterface
     */
    public function get($endPoint, array $query = [])
    {
        $this->checkIfSessionExists();
        $bearerToken = Session::get('session_token');
        $baseApiUrl = $this->apiGetwayService->getBaseUrl();
        $url = $baseApiUrl . $endPoint;

        return Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'SessionToken ' . $bearerToken,
            ]
        )->get($url, $query);
    }

    /**
     * @param $endPoint
     * @param array $query
     * @return array
     */
    public function post($endPoint, array $query = []): array
    {
        $this->checkIfSessionExists();
        $bearerToken = Session::get('session_token');
        $baseApiUrl = $this->apiGetwayService->getBaseUrl();
        $url = $baseApiUrl . $endPoint;


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'SessionToken ' . $bearerToken,
        ])->post($url, $query);

        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();
        return $resp;
    }

    /**
     * @param $endPoint
     * @param array $query
     * @return PromiseInterface|Response
     */
    public function put($endPoint, array $query = [])
    {
        $this->checkIfSessionExists();
        $bearerToken = Session::get('session_token');
        $baseApiUrl = $this->apiGetwayService->getBaseUrl();
        $url = $baseApiUrl . $endPoint;


        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'SessionToken ' . $bearerToken,
        ])->put($url, $query);
    }

    /**
     * @param $endPoint
     * @param array $query
     * @return PromiseInterface|Response
     */
    public function delete($endPoint, array $query = [])
    {
        $this->checkIfSessionExists();
        $bearerToken = Session::get('session_token');
        $baseApiUrl = $this->apiGetwayService->getBaseUrl();
        $url = $baseApiUrl . $endPoint;


        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'SessionToken ' . $bearerToken,
        ])->delete($url, $query);
    }

    /**
     * @return void
     */
    private function setParameters()
    {
        $base_url = $this->apiGetwayService->setBaseUrl("https://www.medpraxapi.co.za/api/");
        $user_name = $this->apiGetwayService->setUserName(config('medprex.UserName'));
        $secret_password = $this->apiGetwayService->setPassword(config('medprex.Password'));
    }

}
