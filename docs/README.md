## Table of contents

- [\Push\Client](#class-pushclient)
- [\Push\Service\Apns](#class-pushserviceapns)
- [\Push\Service\AbstractService (abstract)](#class-pushserviceabstractservice-abstract)
- [\Push\Service\Fcm](#class-pushservicefcm)

<hr />

### Class: \Push\Client

> send push notifications to different services

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct()</strong> : <em>void</em><br /><em>nothing to do here</em> |
| public | <strong>emit(</strong><em>string/array</em> <strong>$to</strong>, <em>array</em> <strong>$data</strong>, <em>array</em> <strong>$options=array()</strong>, <em>string</em> <strong>$platform=`'ANY'`</strong>)</strong> : <em>array</em><br /><em>for FCM topics (e.g. /topics/topicName, 'TopicA' in topics && ('TopicB' in topics || 'TopicC' in topics)) for any tokens use string token or array of tokens</em> |
| public | <strong>getApns()</strong> : <em>[\Push\Service\Apns](#class-pushserviceapns)</em> |
| public | <strong>getFcm()</strong> : <em>[\Push\Service\Fcm](#class-pushservicefcm)</em> |
| public | <strong>setApns(</strong><em>string</em> <strong>$prodCert</strong>, <em>string</em> <strong>$prodPass=null</strong>, <em>string</em> <strong>$devCert=null</strong>, <em>string</em> <strong>$devPass=null</strong>, <em>bool</em> <strong>$isSandbox=false</strong>)</strong> : <em>\Push\$this</em> |
| public | <strong>setFcm(</strong><em>string</em> <strong>$apiKey</strong>)</strong> : <em>\Push\$this</em> |
| public | <strong>using(</strong><em>string</em> <strong>$serviceName</strong>)</strong> : <em>\Push\$this</em> |

<hr />

### Class: \Push\Service\Apns

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>string</em> <strong>$prodCert</strong>, <em>string</em> <strong>$prodPass=null</strong>, <em>string</em> <strong>$devCert=null</strong>, <em>string</em> <strong>$devPass=null</strong>, <em>bool</em> <strong>$isSandbox=false</strong>)</strong> : <em>void</em> |
| public | <strong>emit(</strong><em>mixed</em> <strong>$to</strong>, <em>array</em> <strong>$data</strong>, <em>array</em> <strong>$options=array()</strong>, <em>string</em> <strong>$platform=`'IOS'`</strong>)</strong> : <em>void</em> |

*This class extends [\Push\Service\AbstractService](#class-pushserviceabstractservice-abstract)*

<hr />

### Class: \Push\Service\AbstractService (abstract)

| Visibility | Function |
|:-----------|:---------|
| public | <strong>abstract emit(</strong><em>string/array</em> <strong>$to</strong>, <em>array</em> <strong>$data</strong>, <em>array</em> <strong>$options=array()</strong>, <em>string</em> <strong>$platform=`'ANY'`</strong>)</strong> : <em>array</em><br /><em>for topics (e.g. /topics/topicName, 'TopicA' in topics && ('TopicB' in topics || 'TopicC' in topics)) for tokens use string token or array of tokens</em> |

<hr />

### Class: \Push\Service\Fcm

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>mixed</em> <strong>$apiKey</strong>)</strong> : <em>void</em><br /><em>nothing to do here</em> |
| public | <strong>deleteToken(</strong><em>mixed</em> <strong>$token</strong>)</strong> : <em>array</em><br /><em>unsubscribe from fcm push service</em> |
| public | <strong>emit(</strong><em>mixed</em> <strong>$to</strong>, <em>array</em> <strong>$data</strong>, <em>array</em> <strong>$options=array()</strong>, <em>string</em> <strong>$platform=`'ANY'`</strong>)</strong> : <em>void</em> |
| public | <strong>subscribeToTopic(</strong><em>mixed</em> <strong>$token</strong>, <em>mixed</em> <strong>$topic</strong>)</strong> : <em>array</em><br /><em>subscribe fcm token to a topic</em> |

*This class extends [\Push\Service\AbstractService](#class-pushserviceabstractservice-abstract)*

