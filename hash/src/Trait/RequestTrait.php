<?php

namespace App\Trait;

use Exception;

trait RequestTrait
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    private function doRequest(array $params, $url, array $header, $method = self::METHOD_POST): ?array
    {

        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 40,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $header
        ];

        if ($method !== self::METHOD_GET) {
            $options[CURLOPT_POSTFIELDS] = json_encode($params);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new Exception('Erro ao executar requisição: ' . $err);
        }

        $raw = json_decode($response, true);

        return is_array($raw) ? $raw : [];

    }
}