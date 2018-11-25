<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WeatherLocation extends OpenWeatherMap
{
    /**
     * @var string
     */
    private static $result;

    /**
     * @param $latitude
     * @param $longitude
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getWeatherLocation($latitude, $longitude)
    {
        $params = [];
        $params['lat'] = $latitude;
        $params['lon'] = $longitude;
        $params['units'] = 'metric';
        $params['appid'] = self::$token;

        $url = self::$url . '?' . http_build_query($params);

        $client = new Client([
            'base_uri' => $url
        ]);

        // Exception if city not found
        try {
            self::$result = json_decode($client->request('GET')->getBody());
        } catch (RequestException $e) {
            if ($e->getCode() == '404')
                return self::viewWeather(null);
        }

        return self::responseWeather(self::$result);
    }
}