## Installation

```php
// config/bundles.php
Wexample\SymfonyRemote\WexampleSymfonyRemoteBundle::class => ['all' => true],
Wexample\SymfonyRemoteDs\WexampleSymfonyRemoteDsBundle::class => ['all' => true],
```

The controllers are routed by the application's `routing.controllers` import, like every package of the suite. The page is `/remote/` (route `remote_index`); the endpoint is exposed to the JS router, so it must appear in the routes dumped for FOSJsRouting.
