<?php
namespace Helium\Encryption;

use Helium\Encryption\EncryptionInterface;

class Encryption implements EncryptionInterface {
    
    private $_engine = null;

    public function __construct(EncryptionInterface $engine){
        $this->_engine = $engine;
    }

    public function connect() {
        $this->_engine->connect();
    }

    public function close() {
        $this->_engine->close();
    }

    public function setHost(string $host) {
        $this->_engine->setHost($host);
    }

    public function setAlgorithm(string $algorithm) {
        $this->_engine->setAlgorithm($algorithm);
    }

    public function setPassword(string $password) {
        $this->_engine->setPassword($password);
    }
    
    public function encrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string {
        return $this->_engine->encrypt($message, $algorithm, $password);
    }

    public function decrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string {
        return $this->_engine->decrypt($message, $algorithm, $password);
    }
}