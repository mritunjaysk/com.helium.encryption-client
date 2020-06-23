
# PHP Encryption Microservice Client

This library details how to connect to the encryption microservice for encrypting and decrypting text. The library is meant to be used with the following microservice:

- Docker Installation: https://hub.docker.com/r/heliumservicesllc/helium-encrpytion-service-node
- Git Repo For Docker: https://bitbucket.org/teamhelium/encryption-microservice-node/src/master/

## Installing

The library can be installed by using one fo the following:

 1. Downloaded

 2. Cloned with git 

 3. Installed via composer 

## How To Use
When the code is downloaded or cloned into a project, you can access the Encryption library with an engine. The parameters you will need to know are:

 - **host:** The host for the microservice when connecting. Because its a websocket, it will typically be `ws://host:7050` to connect to a websocket over port 7050.

 - **content:** The string of the text you want to encrypt/decrypt

  - **password:** An OPTIONAL password you can use with encryption/decryption

- **algorithm:** An OPTIONAL algorithm to use for the encyrption

Below demonstates different ways the library can be used.

### Example 1

    <?php
    use Helium\Encryption\Engines\TextTalkEngine;
    use Helium\Encryption\Encryption;
    
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

### Example 2

    <?php
    use Helium\Encryption\Engines\TextTalkEngine;
    use Helium\Encryption\Encryption;
    
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

### Example 3

    use Helium\Encryption\Engines\TextTalkEngine;
    use Helium\Encryption\Encryption;
    
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

### Example With Error Handling

    <?php
    use Helium\Encryption\Exceptions\EncryptionError;
    use Helium\Encryption\Engines\TextTalkEngine;
    use Helium\Encryption\Encryption;
    
    $host = 'ws://localhost:7050';
    
    $client = new Encryption(new TextTalkEngine($host));
    $client->connect();
    
    try {
        $encrypted_content = $client->encrypt('');
    } catch(EncryptionError $e) {
        echo $e->getMessage() . "\n";;
    }





