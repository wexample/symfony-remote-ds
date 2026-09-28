<?php

namespace Wexample\SymfonyRemoteDs\Traits;

use Wexample\SymfonyHelpers\Traits\BundleClassTrait;
use Wexample\SymfonyRemoteDs\WexampleSymfonyRemoteDsBundle;

trait SymfonyRemoteDsBundleClassTrait
{
    use BundleClassTrait;

    public static function getBundleClassName(): string
    {
        return WexampleSymfonyRemoteDsBundle::class;
    }
}
