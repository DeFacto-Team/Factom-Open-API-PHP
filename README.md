# PHP client for Factom Open API
This is PHP client for <a href="https://github.com/DeFacto-Team/Factom-Open-API" target="_blank">Factom Open API</a>.

## Usage

### 1. Require library
```php
require_once("FactomOpenAPI.php");
```

### 2. Initialize client
```php
$endpoint = "http://1.2.3.4:8081";
$api_key = "USER_API_KEY";
$factom = new FactomOpenAPI($endpoint, $api_key);
```

### 3. Use client to work with Factom Open API
Get API info
```php
// Get API version
$chains = $factom->getAPIInfo();
```

Get chains
```php
// Get all user’s chains
$chains = $factom->getChains();

// Get user's chains from 41th to 60th
$chains = $factom->getChains(40, 20);

// Get user's chains with status "queue"
// start=0, limit=0 — use defaults pagination params
// status="queue" — filter chains by status "queue" (also "processing" | "completed")
$chains = $factom->getChains(0, 0, "queue");

// Get user's chains in reverse sorting (from oldest to newest)
// start=0, limit=0 — use defaults pagination params
// status=NULL — not filter by status
// sort="asc" — sort results by createdAt ASC ("desc" is default sorting)
$chains = $factom->getChains(0, 0, NULL, "asc");

// Combine all filters and params
// start=40, limit=20, status="queue", sort="asc"
$chains = $factom->getChains(40, 20, "queue", "asc");
```

Create a chain
```php
// Creates chain on the Factom blockchain
$extIds[0] = "My new chain";
$extIds[1] = "Second ExtID";
$content = "Content of the first entry"; // optional
$chain = $factom->createChain($extIds, $content);
```

Get chain
```php
// Get Factom chain by Chain ID
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";
$chain = $factom->getChain($chainId);
```

Get chain entries
```php
// Get entries of Factom chain
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";
$entries = $factom->getChainEntries($chainId);

// Filters and params may be applied to results
// Example: start=40, limit=20, status="queue", sort="asc"
$entries = $factom->getChainEntries($chainId, 40, 20, "queue", "asc");
```

Get first/last entry of the chain
```php
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";

// Get first entry of Factom chain
$firstEntry = $factom->getChainFirstEntry($chainId);

// Get last entry of Factom chain
$firstEntry = $factom->getChainLastEntry($chainId);
```

Search entries of chain
```php
// Search entries into Factom chain by external id(s)
$chainId = "fb5ad150761da70e090cb2582445681e4c13107ca863f9037eaa2947cf7d225c";
```

