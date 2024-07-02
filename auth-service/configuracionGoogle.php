<?php
  require_once 'vendor/autoload.php';

  $clientID = '631022063904-0n6jn3vcl53bi1gp1urp0o721ef0q2p0.apps.googleusercontent.com';
  $clientSecret = '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';
  $redirectUri = 'https://animalresort.com.co/web/inicio.html';

  // create Client Request to access Google API
  $client = new Google_Client();
  $client->setClientId($clientID);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($redirectUri);
  $client->addScope("email");
  $client->addScope("profile");

 
?>