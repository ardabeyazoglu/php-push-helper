<?php

namespace Push\Service;
use Push\Client;

class Apns extends AbstractService {

    /**
     * file path for apns development certificate
     * @var string
     */
    private $_devCertificate;

    /**
     * file path for apns production certificate
     * @var string
     */
    private $_prodCertificate;

    /**
     * passphrase for development certificate if exists
     * @var string
     */
    private $_devPassword;

    /**
     * passphrase for production certificate if exists
     * @var string
     */
    private $_prodPassword;

    /**
     * @var bool
     */
    private $_isSandbox = false;

    /**
     * @param string $prodCert production certificate path
     * @param string $prodPass production certificate pass
     * @param string $devCert development certificate path
     * @param string $devPass development certificate pass
     * @param bool $isSandbox
     */
    public function __construct($prodCert, $prodPass = null, $devCert = null, $devPass = null, $isSandbox = false){
        $this->_prodCertificate = $prodCert;
        $this->_prodPassword = $prodPass;
        $this->_devCertificate = $devCert;
        $this->_devPassword = $devPass;

    }

    // get from abstract
    public function emit($to, array $data, array $options = array(), $platform = Client::PLATFORM_IOS){
        if(!$this->_isSandbox){
            $url = "gateway.push.apple.com";
            $cert = $this->_prodCertificate;
            $pass = !empty($this->_prodPassword) ? $this->_prodPassword : "";
        }
        else{
            $url = "gateway.sandbox.push.apple.com";
            $cert = $this->_devCertificate;
            $pass = !empty($this->_devPassword) ? $this->_devPassword : "";
        }

        if(empty($cert) || !file_exists($cert)){
            throw new \Exception("Certificate cannot be found at $cert");
        }

        // open connection to apns server
       try {
           $ctx = stream_context_create();
           stream_context_set_option($ctx, 'ssl', 'local_cert', $cert);
           stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);

           $fp = stream_socket_client("ssl://$url:2195", $err, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
           if(!$fp){
               return [
                   "success" => false,
                   "error" => "$err $errstr"
               ];
           }

           $payload = json_encode($data);

           if(!is_array($to)) {
               $to = array($to);
           }

           foreach($to as $id){
               // encode and post the message
               $msg = chr(0) . pack('n', 32) . pack('H*', $id) . pack('n', strlen($payload)) . $payload;
               fwrite($fp, $msg, strlen($msg));
           }

           fclose($fp);

           return array("success" => true);
       }
       catch(\Exception $ex){
           return array(
               "success" => false,
               "error" => $ex->getMessage()
           );
       }
    }

}

?>
