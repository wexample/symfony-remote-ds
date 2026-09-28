## Architecture

Two controllers and one page, on top of `symfony-remote`'s `RemoteRegistry`.

src/Controller/Pages/RemoteController.php extends `symfony-loader`'s `AbstractPagesController` and renders `index` with `RemoteRegistry::all()` — labels and keys only, no check, so the page never waits on a remote.

src/Api/Controller/RemoteController.php extends `symfony-api`'s `AbstractApiController`. `check/{key}` runs `RemoteRegistry::check()` and answers the standard envelope, the data being `RemoteStatus::toArray()` plus the key and label — the same shape `remote:status --format=json` prints.

assets/pages/remote/index.html.twig renders the table and, once, a `<template>` per state holding the design system's `marker()` for it (`checking` → running, `up` → success, `down` → error, `unconfigured` → warning). assets/pages/remote/index.ts checks every row on `pageReady()` and again on "Test": it resolves `api_remote_check` through `RoutingService`, unwraps the envelope with `js-api-entity`'s `unwrapApiEnvelope()`, and clones the marker template of the state into the row. A failure of the endpoint itself (session expired, server down) is shown on the row as `down` with its message.

Both controllers carry `#[IsGranted(RoleHelper::ROLE_ADMIN)]`.

Tests: tests/Unit/Api/Controller/RemoteControllerTest.php calls the endpoint action with a registry of fixed remotes. Rendering the page needs the design system's full front stack; it is checked in the showcase through `symfony-remote-demo`.
