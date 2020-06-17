<?php

namespace helium\encryption\engines;

use helium\encryption\EncryptionInterface;

class TextTalkEngine implements EncryptionInterface {

    private $_client = null;

    private $_host = null;

    private $_algorithm = null;

    private $_password = null;

    public function __construct(?string $host = null, ?string $algorithm  = null, ?string $password = null) {
        $this->_host = $host;
        $this->_algorithm = $algorithm;
        $this->_password = $password;
    }

    public function connect() {
        $this->_client = new \WebSocket\Client($this->_host);

    }

    public function setHost(string $host) {
        $this->_host = $host;
    }

    public function setAlgorithm(string $algorithm) {
        $this->_algorithm = $algorithm;
    }

    public function setPassword(string $password){
        $this->_password  = $password;   
    }

    public function encrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string {

        $data = $this->_formatMessage('encrypt', $message, $algorithm, $password);

        return $this->_send(json_encode($data));
    }

    public function decrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string {

        $data = $this->_formatMessage('decrypt', $message, $algorithm, $password);
        
        return $this->_send(json_encode($data));
    }

    public function close() {
        $this->_client->close();
    }

    private function _send($message) {
        $this->_client->send($message);
        return $this->_client->receive();
    }

    private function _formatMessage(string $command, string $message, ?string $algorithm  = null, ?string $password ) : array {

        $data = [
            'command' => $command,
            'content' => $message
        ];

        $password = ($password) ?: $this->_password;

        $algorithm = ($algorithm) ?: $this->_algorithm;

        if($password) {
            $data['password'] = $password;
        }

        if($algorithm) {
            $data['algorithm'] = $algorithm;
        }

        return $data;

    }
}