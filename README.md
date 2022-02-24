# Spaaza PHP Client #

PHP Client to facilitate connection to the Spaaza API

Example:

    $client = new \spaaza\client\Client('http://api0.spaaza.com/');
    var_dump($client->getRequest('public/search-products.json'));

## Updating with composer ##

This version can be updated with composer. DO NOT FORGET to update the version in this project's composer.json too
or else you will get errors about the version being skipped when you update dependencies in other projects.

## Dependencies ##

* Guzzle 7.0 (PHP >= 7.3)
