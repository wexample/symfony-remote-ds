# symfony-remote-ds

Version: 3.0.6

Open `/remote/` with the configured access role (`ROLE_ADMIN` unless the app says otherwise). Each row shows a remote declared through `symfony-remote` — a configured php-api client or any service implementing `RemoteInterface` — and checks it as soon as the page is displayed; "Test" checks it again.

The states follow `symfony-remote`: `up`, `down` with the reason given by the remote, `unconfigured` with the settings that are missing. The screen checks live and stores nothing.

The check is also reachable on its own, for another screen or a script holding that role:

```
GET /api/remote/check/{key}
→ {"type": "success", "data": {"key", "label", "state", "message", "latency", "checkedAt"}}
```

An unknown key answers an error envelope.

## Table of Contents

- [Architecture](#architecture)
- [Integration in the Suite](#integration-in-the-suite)
- [Dependencies](#dependencies)
- [Versioning & Compatibility Policy](#versioning--compatibility-policy)
- [License](#license)
- [About us](#about-us)
- [Migration Notes](#migration-notes)

## Architecture

Two controllers and one page, on top of `php-remote`'s `RemoteRegistry`, as `symfony-remote` wires it.

src/Controller/Pages/RemoteController.php extends `symfony-loader`'s `AbstractPagesController` and renders `index` with `RemoteRegistry::all()` — labels and keys only, no check, so the page never waits on a remote.

src/Api/Controller/RemoteController.php extends `symfony-api`'s `AbstractApiController`. `check/{key}` runs `RemoteRegistry::check()` and answers the standard envelope, the data being `RemoteStatus::toArray()` plus the key and label — the same shape `remote:status --format=json` prints.

assets/pages/remote/index.html.twig renders the table and, once, a `<template>` per state holding the design system's `marker()` for it (`checking` → running, `up` → success, `down` → error, `unconfigured` → warning). assets/pages/remote/index.ts checks every row on `pageReady()` and again on "Test": it resolves `api_remote_check` through `RoutingService`, unwraps the envelope with `js-api-entity`'s `unwrapApiEnvelope()`, and clones the marker template of the state into the row. A failure of the endpoint itself (session expired, server down) is shown on the row as `down` with its message.

Both controllers carry `#[IsGranted(RemoteAccessVoter::ATTRIBUTE)]`. src/Security/RemoteAccessVoter.php answers that attribute with the role set in `wexample_symfony_remote_ds.access_role` (`ROLE_ADMIN` by default), so each app decides who may run checks without the controllers knowing a role.

Tests: tests/Unit/Api/Controller/RemoteControllerTest.php calls the endpoint action with a registry of fixed remotes. Rendering the page needs the design system's full front stack; it is checked in the showcase through `symfony-remote-demo`.

## Integration in the Suite

This package is part of the Wexample Suite — a collection of high-quality, modular tools designed to work seamlessly together across multiple languages and environments.

### Related Packages

The suite includes packages for configuration management, file handling, prompts, and more. Each package can be used independently or as part of the integrated suite.

Visit the [Wexample Suite documentation](https://docs.wexample.com) for the complete package ecosystem.

## Dependencies

- php: >=8.5
- wexample/symfony-api: >=8.0.0
- wexample/symfony-design-system: >=29.0.0
- wexample/symfony-helpers: >=14.0.0
- wexample/symfony-loader: >=19.0.0
- wexample/symfony-remote: >=2.0.0

## Versioning & Compatibility Policy

Wexample packages follow **Semantic Versioning** (SemVer):

- **MAJOR**: Breaking changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

We maintain backward compatibility within major versions and provide clear migration guides for breaking changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Free to use in both personal and commercial projects.

## About us

[Wexample](https://wexample.com) stands as a cornerstone of the digital ecosystem — a collective of seasoned engineers, researchers, and creators driven by a relentless pursuit of technological excellence. More than a media platform, it has grown into a vibrant community where innovation meets craftsmanship, and where every line of code reflects a commitment to clarity, durability, and shared intelligence.

This packages suite embodies this spirit. Trusted by professionals and enthusiasts alike, it delivers a consistent, high-quality foundation for modern development — open, elegant, and battle-tested. Its reputation is built on years of collaboration, refinement, and rigorous attention to detail, making it a natural choice for those who demand both robustness and beauty in their tools.

Wexample cultivates a culture of mastery. Each package, each contribution carries the mark of a community that values precision, ethics, and innovation — a community proud to shape the future of digital craftsmanship.

## Migration Notes

When upgrading between major versions, refer to the migration guides in the documentation.

Breaking changes are clearly documented with upgrade paths and examples.
