<?php

namespace Helium\Encryption\Engines;

use Helium\Encryption\EncryptionInterface;
use Helium\Encryption\Exceptions\EncryptionError;
use Helium\Encryption\Exceptions\InvalidResponse;
use Helium\Encryption\Exceptions\InvalidStatus;
use WebSocket\Client;

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
        $this->_client = new Client($this->_host);

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

        $response = $this->_send(json_encode($data));

        return $this->_processResponse($response);
    }

    public function decrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string {

        $data = $this->_formatMessage('decrypt', $message, $algorithm, $password);
        
        $response = $this->_send(json_encode($data));

        return $this->_processResponse($response);
    }

    public function close() {
        $this->_client->close();
    }

    private function _send($message) {
        $this->_client->send($message);
        return $this->_client->receive();
    }

    private function _formatMessage(string $command, string $message, ?string $algorithm  = null, ?string $password = null) : array {

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

    private function _processResponse(string $json) {

        $response = json_decode($json, true);

        if(json_last_error() != JSON_ERROR_NONE){
            throw new InvalidResponse("Invalid response from server. Response should be JSON. The following string was passed: " . $json);
        }

        $allowed_status = ['success', 'error'];

        if(!isset($response['status']) || !in_array($response['status'], $allowed_status)) {
            throw new InvalidStatus('An incorrect status was return from the server.');
        }

        if($response['status'] == 'error') {
            throw new EncryptionError($response['message']);
        }

        return $response['content'];
    }

}