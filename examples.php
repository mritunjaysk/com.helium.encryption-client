<?php
require __DIR__ . '/vendor/autoload.php';

require('helium/encryption/EncryptionInterface.php');
require('helium/encryption/engines/TextTalkEngine.php');
require('helium/encryption/Encryption.php');
require('helium/encryption/exceptions/EncryptionError.php');

use helium\encryption\exceptions\EncryptionError;
use helium\encryption\engines\TextTalkEngine;
use helium\encryption\Encryption;


//Example 1
$host = 'ws://localhost:7050';
$password = 'somepassword'; //Optional
$algorithm = 'aes-128-cbc'; //Optional

$client = new Encryption(new TextTalkEngine($host, $algorithm, $password));
$client->connect();

$encrypted_content = $client->encrypt("ABC 123");
echo "Encrytped Content: " . $encrypted_content . "\n";
$decrypted_content = $client->decrypt($encrypted_content);
echo "Decrypted Content: " . $decrypted_content . "\n";

$client->close();


//Example 2
$host = 'ws://localhost:7050';
$password = 'somepassword'; //Optional
$algorithm = 'aes-128-cbc'; //Optional

$client = new Encryption(new TextTalkEngine());
$client->setHost($host);
$client->setAlgorithm($algorithm);
$client->setPassword($password);
$client->connect();

$encrypted_content = $client->encrypt("Doe Rae Me");
echo "Encrytped Content: " . $encrypted_content . "\n";
$decrypted_content = $client->decrypt($encrypted_content);
echo "Decrypted Content: " . $decrypted_content . "\n";

$client->close();


//Example 3
$host = 'ws://localhost:7050';
$password = 'somepassword'; //Optional
$algorithm = 'aes-128-cbc'; //Optional

$client = new Encryption(new TextTalkEngine());
$client->setHost($host);
$client->connect();

$encrypted_content = $client->encrypt("Jumanji", $algorithm, $password);
echo "Encrytped Content: " . $encrypted_content . "\n";
$decrypted_content = $client->decrypt($encrypted_content, $algorithm, $password);
echo "Decrypted Content: " . $decrypted_content . "\n";

$client->close();

//Error Example
$host = 'ws://localhost:7050';

$client = new Encryption(new TextTalkEngine($host));
$client->connect();

try {
    $encrypted_content = $client->encrypt('');
} catch(EncryptionError $e) {
    echo $e->getMessage() . "\n";;
}

$client->close();

