# Multipart Uploads

## Overview
- `postMultipartRequest` sends multipart form data via Guzzle's `MultipartStream`.
- Each part is an associative array with `name` and `contents` keys.

Example:

```php
$params = [
    [
        'name' => 'imagefile',
        'contents' => fopen('/path/to/image.jpg', 'r'),
    ],
    [
        'name' => 'image_seq_num',
        'contents' => 1,
    ],
];

$client->postMultipartRequest('auth/upload-image.json', $params);
```

## Tips
- Always close file handles you open for `contents`.
- Add any required auth headers using the `$auth` argument or `set*` methods.
