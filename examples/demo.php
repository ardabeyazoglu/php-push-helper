<?php

include_once __DIR__ . "/../vendor/autoload.php";

$fcmApiKey = "YOUR_FCM_API_KEY";
$apnsCert = "YOUR_PRODUCTION_CERTIFICATE_PATH";
$apnsPass = "YOUR_PRODUCTION_CERTIFICATE_PASSPHRASE";

$push = new \Push\Client();
$push->setFcm($fcmApiKey);
$push->setApns($apnsCert, $apnsPass);

// send using fcm
$regToken = "YOUR_DEVICE_TOKEN";
$result = $push->using(\Push\Client::SERVICE_FCM)
     ->emit($regToken, array(
         "title" => "Test push title",
         "body" => "That's it!",
         "custom" => "my custom data"
     ));
print_r($result);

// send using apns
$regToken = "YOUR_DEVICE_TOKEN";
$result = $push->using(\Push\Client::SERVICE_APNS)
     ->emit($regToken, array(
         "aps" => array(
             "alert" => "Test push title",
             "sound" => "default"
         ),
         "content" => "That's it!",
         "custom" => "my custom data"
     ));
print_r($result);