<?php
namespace helium\encryption;

interface EncryptionInterface {

    public function connect();

    public function close();

    public function setHost(string $host);

    public function setAlgorithm(string $algorithm);

    public function setPassword(string $password);
    
    public function encrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string;

    public function decrypt(string $message, ?string $algorithm  = null, ?string $password = null) : string;
}