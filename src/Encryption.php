<?php
namespace Helium\Encryption;

use Helium\Encryption\EncryptionInterface;
use Helium\Encryption\Engines\TextTalkEngine;
use Helium\ServiceManager\EngineContract;
use Helium\ServiceManager\ServiceManager;

class Encryption extends ServiceManager implements EncryptionInterface {

    public function getEngineContract(): string
    {
        return EncryptionInterface::class;
    }

    protected function createDefaultEngine(): EngineContract
    {
        return new TextTalkEngine();
    }

    public function connect() {
        $this->engine()->connect();
    }

    public function close() {
        $this->engine()->close();
    }

    public function setHost(string $host) {
        $this->engine()->setHost($host);
    }

    public function setAlgorithm(string $algorithm) {
        $this->engine()->setAlgorithm($algorithm);
    }

    public function setPassword(string $password) {
        $this->engine()->setPassword($password);
    }
    
    public function encrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string {
        return $this->engine()->encrypt($message, $algorithm, $password);
    }

    public function decrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string {
        return $this->engine()->decrypt($message, $algorithm, $password);
    }
}
