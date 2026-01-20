# Client Requests Overview

This library provides a thin PHP wrapper around HTTP requests via Guzzle.

## Request Methods
- `getRequest($path, $params, $auth, $extra_headers)` sends query parameters.
- `postRequest`, `putRequest`, and `deleteRequest` send `application/x-www-form-urlencoded` payloads.
- `postJSONRequest`, `putJSONRequest`, and `patchJSONRequest` send JSON bodies.
- `postMultipartRequest` sends multipart form data (e.g., file uploads).

## Authentication & Headers
- `$auth` can be a bearer token string or an array of session fields.
- Array auth maps to headers like `X-Spaaza-Session-Key`, `X-Spaaza-Session-User-Id`, and `X-Spaaza-Session-Username`.
- Optional request headers can be set via setters, including:
  - `setUserAgent`, `setLocale`, `setApiVersion`, `setXForwardedFor`
  - `setOnBehalfOf`, `setUserCookie`, `setMyPriceAppHostname`

## Response Handling
- By default, responses are JSON-decoded and returned as arrays.
- If `setThrowExceptions(true)` is enabled, API errors raise `APIException`.

Example:

```php
$client = new \spaaza\client\Client('https://api.example.com/');
$client->setApiVersion('v1');
$client->setThrowExceptions(true);
$response = $client->getRequest('public/search-products.json', ['q' => 'shoes']);
```
