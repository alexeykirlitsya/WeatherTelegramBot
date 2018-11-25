<?php

namespace App;

class TelegramBot
{
    /**
     * @var string
     */
    protected $url = BOT_API_URL;

    /**
     * @var string
     */
    protected $token = BOT_API_TOKEN;

    /**
     * @var string
     */
    protected $httpsUrl = YOUR_HTTPS_URL;

    /**
     * @return mixed
     * Use this method to specify a url and receive incoming updates via an outgoing webhook
     */
    public function setWebhook()
    {
        $params =[
            'url' => $this->httpsUrl
        ];
        return $this->sendRequest('setWebhook', $params);
    }

    /**
     * @param $method
     * @param array $params
     * @return mixed
     * send request to the bot api
     */
    public function sendRequest($method, $params = [])
    {
        $url = $this->url . $this->token. '/'. $method;

        if(!empty($params)) {
            $url .= "?" . http_build_query($params);
        }

        return json_decode(file_get_contents($url), true);
    }

    /**
     * @return mixed
     * get update from the bot api
     */
    public function getUpdate()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * send message to user
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage()
    {
        $result = $this->getUpdate();
        $chatId = $result['message']['chat']['id'];
        $text = $result['message']['text'];
        $firstName = $result['message']['from']['first_name'];

        if ($text === '/start') {
            $response = 'Hi, ' . $firstName
                . '! I am a bot that determines the weather by geolocation or the name of the city that you enter.';
        } else{
            $response = OpenWeatherMap::sendMessageToUser($result);
        }
        $this->sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => $response]);
    }
}