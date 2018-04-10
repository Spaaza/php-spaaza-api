# Spaaza PHP Client #

PHP Client for Spaaza API

Example:

    $client = new \spaaza\client\Client('http://api0.spaaza.com/',  'v1');
    var_dump($client->getRequest('public/search-products.json'));



## Dependencies ##

* Guzzle 6.2 (PHP >= 5.5)
