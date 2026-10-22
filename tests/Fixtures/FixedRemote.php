<?php

namespace Wexample\SymfonyRemoteDs\Tests\Fixtures;

use Wexample\PhpRemote\Class\RemoteStatus;
use Wexample\PhpRemote\Interface\RemoteInterface;

/**
 * A remote answering whatever status it was built with.
 */
final readonly class FixedRemote implements RemoteInterface
{
    public function __construct(
        private string $key,
        private RemoteStatus $status,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLabel(): string
    {
        return ucfirst($this->key);
    }

    public function checkStatus(): RemoteStatus
    {
        return $this->status;
    }
}
