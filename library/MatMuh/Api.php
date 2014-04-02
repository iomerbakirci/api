<?php
namespace MatMuh;
use Zend\Http\Client;

class Api
{
    public static function request($uri, $data = null, $debug = null)
    {
        $uri = API_URL . $uri;

        $client = new \Zend\Http\Client($uri);
        $client->setParameterPost($data);
        $client->setMethod('POST');

        $response = $client->send();
        $body=$response->getBody();

        if($debug)
            return $body;
        else
            return json_decode($body, true);
    }
}