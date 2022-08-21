<?php

namespace Sagordev\PowersmsGateway;

use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;

abstract class HttpAbstract
{
    protected $httpClient;
    protected $baseUrl = 'https://powersms.banglaphone.net.bd/';
    protected $endpoint = '/httpapi/sendsms';
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
        if($config['url'] != ''){
            $this->baseUrl = $config['url'];
        }
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 10.0
        ]);
    }

    protected function requestToServer($query){
        $query = array_merge($query, [
            'userId' => $this->config['user_id'],
            'password' => $this->config['password']
        ]);
        $response = $this->httpClient->request('GET', $this->endpoint, ['query' => $query]);
        return $response->getBody()->getContents();
    }
}