<?php

namespace App;

class OpenWeatherMap
{
    /**
     * @var string
     */
    public static $url = WEATHER_API_URL;

    /**
     * @var string
     */
    public static $token = WEATHER_API_TOKEN;

    /**
     * @param $result
     * @return string
     */
    public static function responseWeather($result)
    {
        //plus or minus sign
        switch (round($result->main->temp)) {
            case 0:
                $temp = 0;
                break;
            case round($result->main->temp)>0:
                $temp = "+".round($result->main->temp);
                break;
            default:
                $temp = round($result->main->temp);
                break;
        }

        //icons
        $icons = [
            'clear'        => '☀',
            'clouds'       => '☁',
            'rain'         => '🌧',
            'drizzle'      => '🌨',
            'thunderstorm' => '🌩',
            'snow'         => '🌨',
            'mist'         => '🌫️',
            'haze'         => '🌫️',
        ];

        $response = [
            'weather' => $result->weather[0]->main,
            'icon' => $icons[strtolower($result->weather[0]->main)],
            'temp' => $temp,
            'humidity' => round($result->main->humidity),
            'wind' => round($result->wind->speed),
            'city' => $result->name
        ];

        return self::viewWeather($response);
    }

    /**
     * @param $response
     * @return string
     */
    public static function viewWeather($response)
    {
        if ($response == null){
            return "This city not found.\nTry typing the name again ". hex2bin('F09F9889');
        }
        return "The weather: ".$response['weather']. " ".$response['icon'].
            "\nTemp: ".$response['temp'].
            " °С\nHumidity: ".$response['humidity'].
            " %\nWind: ".$response['wind'].
            " m/s\nCity: ".$response['city'].
            "\nGood day!";
    }

    /**
     * @param $result
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendMessageToUser($result)
    {
        if($result['message']['text']){
            $response = WeatherCity::getWeatherCity($result['message']['text']);
        } else{
            $response = WeatherLocation::getWeatherLocation($result['message']['location']['latitude'], $result['message']['location']['longitude']);
        }

        return $response;
    }
}