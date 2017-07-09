<?php

namespace Push\Service;
use Push\Client;

abstract class AbstractService {

    /**
     * @param string|array $to
     *      for topics (e.g. /topics/topicName, 'TopicA' in topics && ('TopicB' in topics || 'TopicC' in topics))
     *      for tokens use string token or array of tokens
     * @param array $data
     * @param array $options
     * @param string $platform
     * @return array
     */
    abstract public function emit($to, array $data, array $options = array(), $platform = Client::PLATFORM_ANY);
}

?>