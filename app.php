<?php
require __DIR__ . '/vendor/autoload.php';

require('helium/encryption/EncryptionInterface.php');
require('helium/encryption/engines/TextTalkEngine.php');
require('helium/encryption/Encryption.php');


use helium\encryption\engines\TextTalkEngine;
use helium\encryption\Encryption;


$client = new Encryption(new TextTalkEngine);
$client->setHost("ws://localhost:7050");
$client->connect();
echo $client->encrypt("ABC 123");

/*
$client = new WebSocket\Client("ws://localhost:7050");
$client->send(json_encode(['command'=>'encrypt', 'content'=>'abc123']));
$message = $client->receive();

echo $message;

$data = json_decode($message, true);

$sending = ['command'=>'decrypt', 'content'=>$data['content']];

print_r($sending);

$client->send(json_encode($sending));
echo $client->receive();

$client->close();*/
