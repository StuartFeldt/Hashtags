<?php

/**
 * Socket interface for pulling tweets & instas
 *
 * @author stuartfeldt
 */

namespace Stuart\HashtagBundle;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class TweetPull implements MessageComponentInterface {
    /**
     * A lookup of all the hashtags clients have subscribed to
     */
    protected $subscribedHashTags = array();
    
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

}

?>
