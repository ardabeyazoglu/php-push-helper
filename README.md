[![Latest Stable Version](https://poser.pugx.org/ardabeyazoglu/php-push-helper/v/stable)](https://packagist.org/packages/ardabeyazoglu/php-push-helper) [![License](https://poser.pugx.org/ardabeyazoglu/php-push-helper/license)](https://packagist.org/packages/ardabeyazoglu/php-push-helper)

# About
A php helper class to send push notifications to different services. Currently, it only supports `APNS` and Google's `FCM`.

# Features

- By using `APNS`, you can emit messages to iOS
- By using `FCM`, You can emit messages to iOS, Android, Browsers (Service Workers) or any client listening `Firebase Messaging Service`
    - Creates `data payloads` based on the rules specified in [phonegap-push-plugin](https://github.com/phonegap/phonegap-plugin-push).

# Installation
    composer install ardabeyazoglu/php-push-helper

# Usage
```php
    $fcmApiKey = "YOUR_FCM_API_KEY";
    
    $push = new \Push\Client();
    $push->setFcm($fcmApiKey);

    // send using fcm
    $regToken = "YOUR_DEVICE_TOKEN";
    $result = $push->emit($regToken, array(
         "title" => "Test push title",
         "body" => "That's it!",
         "custom" => "my custom data"
     ));
```

Please see the [docs](https://github.com/ardabeyazoglu/php-push-helper/tree/master/docs) and [examples](https://github.com/ardabeyazoglu/php-push-helper/tree/master/examples)

# ToDo

- More services to integrate (Window Phone 8, UWP, Web Push etc.)
- Writing a helper class to produce json payloads
