# Error Handling Notes

## Response Shape
- Responses are JSON-decoded into arrays.
- When `setThrowExceptions(true)` is enabled, API errors are raised as `APIException`.
- `APIException` is triggered when the response includes `error` or `errors` keys.

## Client Exceptions
- Guzzle `ClientException` is caught and the response body is still parsed.
- Check for error fields in the returned array when `setThrowExceptions(false)`.

Example:

```php
$client->setThrowExceptions(true);
try {
    $client->postJSONRequest('auth/login.json', ['username' => 'user']);
} catch (\spaaza\client\APIException $e) {
    // Handle API error details from the response.
}
```
