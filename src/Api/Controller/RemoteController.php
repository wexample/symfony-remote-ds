<?php

namespace Wexample\SymfonyRemoteDs\Api\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyHelpers\Controller\AbstractController;
use Wexample\SymfonyHelpers\Helper\RoleHelper;
use Wexample\SymfonyRemote\Service\RemoteRegistry;

/**
 * Checks one remote on demand, for the screen's "Test" button. Admin only:
 * every call reaches out to a service outside the app.
 */
#[Route(path: 'api/remote/', name: 'api_remote_')]
#[IsGranted(RoleHelper::ROLE_ADMIN)]
class RemoteController extends AbstractApiController
{
    final public const string ROUTE_CHECK = 'api_remote_check';

    #[Route(path: 'check/{key}', name: 'check', methods: AbstractController::ROUTE_OPTIONS_METHOD_ONLY_GET, options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE)]
    public function check(
        string $key,
        RemoteRegistry $registry,
    ): ApiResponse {
        if (! isset($registry->all()[$key])) {
            return self::apiResponseError('Unknown remote.');
        }

        return self::apiResponseSuccess(data: [
            'key' => $key,
            'label' => $registry->get($key)->getLabel(),
        ] + $registry->check($key)->toArray());
    }
}
