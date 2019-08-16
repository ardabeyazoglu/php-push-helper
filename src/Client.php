<?php

namespace Push;

use Push\Service\Apns;
use Push\Service\Fcm;

/**
 * send push notifications to different services
 * @author Arda Beyazoglu (mailto:arda@beyazoglu.com)
 */
class Client {

    // platform constants
    const PLATFORM_ANY = "ANY";
    const PLATFORM_IOS = "IOS";
    const PLATFORM_ANDROID = "ANDROID";
    const PLATFORM_BROWSER = "BROWSER";

    // service constants
    const SERVICE_FCM = "FCM";
    const SERVICE_APNS = "APNS";

    /**
     * @var string service to use
     */
    private $_service = null;

    /**
     * @var array
     */
    private $_services = array();

    /**
     * nothing to do here
     */
    public function __construct(){}

    /**
     * @param string $apiKey FCM api key
     * @return $this
     */
    public function setFcm($apiKey){
        $this->_services[self::SERVICE_FCM] = new Service\Fcm($apiKey);
        $this->using(self::SERVICE_FCM);
        return $this;
    }

    /**
     * @param string $prodCert production certificate path
     * @param string $prodPass production certificate pass
     * @param string $devCert development certificate path
     * @param string $devPass development certificate pass
     * @param bool $isSandbox
     * @return $this
     */
    public function setApns($prodCert, $prodPass = null, $devCert = null, $devPass = null, $isSandbox = false){
        $this->_services[self::SERVICE_APNS] = new Service\Apns($prodCert, $prodPass, $devCert, $devPass, $isSandbox);
        $this->using(self::SERVICE_APNS);
        return $this;
    }

    /**
     * @param string $serviceName one of the service constants
     * @return $this
     * @throws \Exception
     */
    public function using($serviceName){
        if(!isset($this->_services[$serviceName])){
            throw new \Exception("Service '$serviceName' not supported");
        }
        $this->_service = $serviceName;
        return $this;
    }

    /**
     * @param string|array $to
     *      for FCM topics (e.g. /topics/topicName, 'TopicA' in topics && ('TopicB' in topics || 'TopicC' in topics))
     *      for any tokens use string token or array of tokens
     * @param array $data
     * @param array $options
     * @param string $platform one of the platform constants
     * @return array
     * @throws \Exception
     */
    public function emit($to, $data, $options = array(), $platform = self::PLATFORM_ANY){
        if(!isset($this->_services[$this->_service])){
            throw new \Exception("Service '$this->_service' must be configured to emit messages");
        }

        return $this->_services[$this->_service]->emit($to, $data, $options, $platform);
    }

    /**
     * @return Fcm
     */
    public function getFcm(){
        return array_key_exists(self::SERVICE_FCM, $this->_services) ? $this->_services[self::SERVICE_FCM] : null;
    }

    /**
     * @return Apns
     */
    public function getApns(){
        return array_key_exists(self::SERVICE_APNS, $this->_services) ? $this->_services[self::SERVICE_APNS] : null;
    }
}

?>
