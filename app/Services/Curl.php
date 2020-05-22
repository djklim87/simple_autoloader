<?php

namespace App\Services;

class Curl
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    private $error = '';

    public function __construct()
    {
        $this->userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 ' .
                           '(KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
    }

    public function get($url)
    {
        return $this->curlRequest([
            CURLOPT_URL => $url,
        ]);
    }


    public function getError()
    {
        return $this->error;
    }


    private function curlRequest($options)
    {
        $this->error = [];
        $ch          = curl_init();

        $defaultOptions = [
            CURLOPT_USERAGENT      => $this->userAgent,
            CURLOPT_ENCODING       => 'gzip, deflate',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/yaml'
            ],
        ];

        $defaultOptions = $options + $defaultOptions;

        curl_setopt_array($ch, $defaultOptions);

        $serverOutput = curl_exec($ch);

        if ( ! curl_errno($ch)) {

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ( ! in_array($httpCode, [200, 201, 202])) {

                $this->error = date('Y-m-d H:i:s') . ': Request error. Status = ' . $httpCode . ", <br> Response: " . $serverOutput;

                return ['status' => self::STATUS_ERROR, 'message' => 'Request error. Status = ' . $httpCode];
            }

        } else {
            $this->error = date('Y-m-d H:i:s') . ': Curl error. ' . curl_error($ch);

            return ['status' => self::STATUS_ERROR, 'message' => 'Curl error. ' . curl_error($ch)];
        }

        curl_close($ch);

        return ['status' => self::STATUS_SUCCESS, 'result' => $serverOutput];
    }
}