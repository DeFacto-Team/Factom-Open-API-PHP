# PHP client for Factom Open API

## Usage
1. Require library
```php
require_once("FactomOpenAPI.php");
```

2. Initialize client
```php
$endpoint = "https://demo.openapi.de-facto.pro";
$api_key = "qB2AK07xSgnR6FvvZcoJrPA575qhRILq";
$factom = new FactomOpenAPI($endpoint, $api_key);
```

3. Use client to work with Factom Open API
```php
// Get user chains
$chains = $factom->getChains();

// Create new chain
$extIds[0] = "My new chain";
$extIds[1] = "Second ExtID";
$content = "Content of the first entry"; // optional
$chain = $factom->createChain($extIds, $content);
```

More examples will be described later.
