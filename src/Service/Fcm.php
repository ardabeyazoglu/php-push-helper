<?php

namespace Push\Service;
use Push\Client;

class Fcm extends AbstractService {

    /**
     * google firebase cloud messaging server api key
     * @var string
     */
    private $apiKey;

    /**
     * nothing to do here
     * @param $apiKey
     */
    public function __construct($apiKey){
        $this->apiKey = $apiKey;
    }

    // get from abstract
    public function emit($to, array $data, array $options = array(), $platform = Client::PLATFORM_ANY){
        if(is_string($to) && preg_match('/(topics|\s)+/', $to) > 0){
            return $this->_emitByTopic($to, $data, $options, $platform);
        }
        else{
            return $this->_emitByToken($to, $data, $options, $platform);
        }
    }

    /**
     * emit message to fcm by device tokens
     * @param array|string $to device registration ids (tokens)
     * @param array $data data to send
     * @param array $options extra options to send (priority|time_to_live)
     * @param string $platform check constants for available platforms
     * @return array|mixed
     */
    private function _emitByToken($to, array $data, array $options = array(), $platform = Client::PLATFORM_ANY){
        if(!is_array($to)){
            $to = array($to);
        }

        $message = array(
            "registration_ids" => $to,
            "data" => $data,
            "priority" => "normal"
        );

        if(!in_array($platform, [Client::PLATFORM_BROWSER])){
            $message["notification"] = array(
                "title" => $data["title"],
                "body" => $data["body"]
            );

            if(array_key_exists("sound", $data)) $message["notification"]["sound"] = $data["sound"];
            if(array_key_exists("icon", $data)) $message["notification"]["icon"] = $data["icon"];
        }

        $message = array_merge($message, $options);

        return $this->_emit($message);
    }

    /**
     * emit message to fcm by topics
     * @see https://firebase.google.com/docs/cloud-messaging/send-message#send_messages_to_topics
     * @param string $to topics (e.g. /topics/topicName, 'TopicA' in topics && ('TopicB' in topics || 'TopicC' in topics))
     * @param array $data data to send
     * @param array $options extra options to send (priority|time_to_live)
     * @param string $platform data structure can be different for different platforms
     * @return array|mixed
     */
    private function _emitByTopic($to, array $data, array $options = array(), $platform = Client::PLATFORM_ANY){
        $toKey = substr($to, 0, 8) === "/topics/" ? "to" : "condition";

        $message = array(
            $toKey => $to,
            "data" => $data,
            "priority" => "normal"
        );

        if(!in_array($platform, [Client::PLATFORM_BROWSER])){
            $message["notification"] = array(
                "title" => $data["title"],
                "body" => $data["body"]
            );

            if(array_key_exists("sound", $data)) $message["notification"]["sound"] = $data["sound"];
            if(array_key_exists("icon", $data)) $message["notification"]["icon"] = $data["icon"];
        }

        $message = array_merge($message, $options);

        return $this->_emit($message);
    }

    /**
     * post message to fcm
     * @param $postData
     * @return array an array containing success,error,code,response vars
     * @throws \Exception
     */
    private function _emit($postData){
        if(empty($this->apiKey)){
            throw new \Exception("You must set `fcmApiKey` to use fcm messaging");
        }

        $url = "https://fcm.googleapis.com/fcm/send";

        $headers = array(
            'Authorization: key=' . $this->apiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 15000);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = !empty($response) ? json_decode($response, true) : array();

        return array(
            "success" => $httpCode === 200 ? !!$response["success"] : false,
            "error" => $error,
            "code" => $httpCode,
            "response" => $response
        );
    }

    /**
     * subscribe fcm token to a topic
     * @param $token string fcm token
     * @param $topic /topics/topicName
     * @return array
     * @throws \Exception
     */
    public function subscribeToTopic($token, $topic){
        if(empty($this->apiKey)){
            throw new \Exception("You must set `fcmApiKey` to use fcm messaging");
        }

        $ch = curl_init("https://iid.googleapis.com/iid/v1/$token/rel/topics/$topic");
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Content-Length: 0",
                "Authorization: key=" . $this->apiKey
            ],
            CURLOPT_TIMEOUT_MS => 10000,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true
        ]);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return array(
            "success" => $httpCode === 200 || $httpCode === 204,
            "error" => $error,
            "code" => $httpCode,
            "response" => !empty($response) ? json_decode($response, true) : null
        );
    }

    /**
     * unsubscribe from fcm push service
     * @param $token
     * @return array
     * @throws \Exception
     */
    public function deleteToken($token){
        if(empty($this->apiKey)){
            throw new \Exception("You must set `fcmApiKey` to use fcm messaging");
        }

        $ch = curl_init("https://iid.googleapis.com/v1/web/iid/$token");
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Content-Length: 0",
                "Authorization: key=" . $this->apiKey
            ],
            CURLOPT_TIMEOUT_MS => 10000,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE"
        ]);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return array(
            "success" => $httpCode === 200 || $httpCode === 204,
            "error" => $error,
            "code" => $httpCode,
            "response" => !empty($response) ? json_decode($response, true) : null
        );
    }
}

?>
