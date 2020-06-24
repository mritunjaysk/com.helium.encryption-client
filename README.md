
# PHP Encryption Microservice Client

This library details how to connect to the encryption microservice for encrypting and decrypting text. The library is meant to be used with the following microservice:

- Docker Installation: https://hub.docker.com/r/heliumservicesllc/helium-encrpytion-service-node
- Git Repo For Docker: https://bitbucket.org/teamhelium/encryption-microservice-node/src/master/

## Installing

The library can be installed by using one fo the following:

 1. Downloaded

 2. Cloned with git 

 3. Installed via composer 

## How To Use (Laravel Projects)
In addition to the base Encryption class (see below), Laravel projects may utilize a set of helpful tools to more easily
and invisibly leverage this encryption package.

### Configuration
Although this library includes default configuration values, you should provide your own configuration settings to
select the host, algorithm, and password that will be used when connecting to the encryption microservice.

### Example 1
Simply set your chosen values in your project `.env` file.
```
ENCRYPTION_HOST=ws://encryption.mywebsite.com
ENCRYPTION_ALGORITHM=aes-256-cbc
ENCRYPTION_PASSWORD=${APP_KEY}
```

### Example 2
For a more custom configuration, you may publish the package configuration file and edit the values manually.

**WARNING:** For safety reasons, you should NEVER include sensitive information (such as the `encryption.password` 
value) directly in a configuration file, as it is stored directly in your project repository. To use the custom
configuration, you should not modify the `encryption.password` entry, as it relies on the untracked `.env` file (see
Example 1 above).
```
php artisan vendor:publish --provider="Helium\Encryption\Providers\EncryptionServiceProvider"
```
```
<?php

return [
    'host' => env('ENCRYPTION_HOST', 'ws://localhost:7050'),
    'algorithm' => env('ENCRYPTION_ALGORITHM', 'aes-256-cbc'),
    'password' => env('ENCRYPTION_PASSWORD') //DO NOT CHANGE!
];
```

### Singleton
#### Example 1
By default, an instantiated and connected singleton is bound to the project Service Container, and can directly utilize
the provided `encrypt()` and `decrypt()` functions.
```
use Helium\Encryption\Encryption;

$encrypted = app(Encryption::class)->encrypt('abc123');
$decrypted = app(Encryption::class)->decrypt($encrypted);
```

#### Example 2
In addition, a global helper function grants access to the singleton without the need to manually resolve from the 
Service Container.
```
$encrypted = encryption()->encrypt('abc123');
$decrupted = encryption()->encrypt($encrypted);
```

### Eloquent Model Trait
For Eloquent models, the `EncryptsAttributes` trait allows you to encrypt and decrypt data as it goes into and comes out
of the database without the need to manually reference the encryption client. Simply set the `$encrypedAttributes` array
on the model class to specify which attributes should be encrytped at the database level.

#### Example
```
use Helium\Encryption\Traits\EncryptsAttributes;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use EncryptsAttributes;

    $fillable = [
        'first_name',
        'last_name',
        'social_security_number'
    ];

    $encryptedAttributes = [
        'social_security_number'
    ];
}
```

##### Code:
```
User::create([
    'first_name' => 'George',
    'last_name' => 'Burdell',
    'social_security_number' => '123456789'
]);

$user = User::first();

echo $user->social_security_number;
// 123456789
```

##### Database:
```
first_name | last_name | social_security_number
------------------------------------------------
George     | Burdell   | 9560f15de26a31606f3a7541df05efb1
```

## How To Use (Non-Laravel Projects)
When the code is downloaded or cloned into a project, you can access the Encryption library with an engine. The parameters you will need to know are:

 - **host:** The host for the microservice when connecting. Because its a websocket, it will typically be `ws://host:7050` to connect to a websocket over port 7050.

 - **content:** The string of the text you want to encrypt/decrypt

  - **password:** An OPTIONAL password you can use with encryption/decryption

- **algorithm:** An OPTIONAL algorithm to use for the encyrption

Below demonstates different ways the library can be used.

#### Example 1

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

#### Example 2

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

#### Example 3

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

#### Example With Error Handling

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