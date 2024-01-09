<?php

namespace App\Trait;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait RequestTrait
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    private function doRequest(array $params, $method = self::METHOD_POST): ?array
    {

        try {

            $client = new Client([
                'base_uri' => 'http://localhost:8000',
            ]);

            $response = $client->request(
                self::METHOD_POST,
                '/calculate-hash',
                [
                    'json' => $params
                ]
            );

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData;

        } catch ( GuzzleException $e ) {
            throw new Exception($e->getMessage());
        }

    }
}