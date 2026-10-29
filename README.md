# symfony-remote-ds

Version: 4.0.3

Design-system screens to watch and test the remotes of a Symfony app

## Table of Contents

- [Installation](#installation)
- [Introduction](#introduction)

## Installation

```php
// config/bundles.php
Wexample\SymfonyRemote\WexampleSymfonyRemoteBundle::class => ['all' => true],
Wexample\SymfonyRemoteDs\WexampleSymfonyRemoteDsBundle::class => ['all' => true],
```

Who may open the screen is the application's call:

```yaml
# config/packages/wexample_symfony_remote_ds.yaml
wexample_symfony_remote_ds:
  access_role: ROLE_ADMIN   # the default
```

The controllers are routed by the application's `routing.controllers` import, like every package of the suite. The page is `/remote/` (route `remote_index`); the endpoint is exposed to the JS router, so it must appear in the routes dumped for FOSJsRouting.

`symfony-remote-ds` is the screen of `symfony-remote`, built on the design system: a page listing every remote of the application with its state, latency and message, and a "Test" button per row. The page renders the list at once and each row checks itself afterwards, so one slow service delays its own row and nothing else. Page and endpoint are restricted to a configurable role, `ROLE_ADMIN` by default: every check reaches out to a service outside the application.
