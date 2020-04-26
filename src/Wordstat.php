<?php
namespace Wordstat;


use Exception;

class Wordstat
{

    private $token = null;

    public function __construct()
    {
        $this->setToken('AgAAAAADq7m9AAWyq-oJGEZt7Ee0tzUBmuMMacM');

    }

    /**
     * @param $token
     */
    public function setToken($token){
        $this->token = $token;
    }


    /**
     * @param array $phrases
     * @return array|bool|string
     */
    public function createReport(array $phrases){
        $params = [
            'method'    => 'CreateNewWordstatReport',
            'token'     => $this->token,
            'param'     => [
                'Phrases'   => $phrases,
            ]
        ];

        $content = self::call('https://api-sandbox.direct.yandex.ru/live/v4/json/', json_encode($params, JSON_UNESCAPED_UNICODE), 'POST');

        try {
            $content = (array) json_decode($content, 1);
        }catch (Exception $exception){
            return [];
        }

        return $content;
    }

    /**
     * @param $url
     * @param $params
     * @param string $method
     * @param array $headers
     * @param string $cookie
     * @return bool|string
     */
    public static function call($url, $params, $method = "get", $headers = [], $cookie = ''){
        $curl = curl_init($url);
        $method = strtolower($method);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 50);
        curl_setopt($curl, CURLOPT_TIMEOUT, 50);
        curl_setopt($curl,CURLOPT_ENCODING , "gzip,deflate");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if($cookie){
            curl_setopt($curl, CURLOPT_COOKIE,"$cookie");
        }

        if(in_array($method, ['put', 'post', "delete"])){
            curl_setopt($curl, CURLOPT_POST, 1);
            if(is_array($params)){
                $params = http_build_query($params);
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}