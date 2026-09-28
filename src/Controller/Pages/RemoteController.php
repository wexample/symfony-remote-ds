<?php

namespace Wexample\SymfonyRemoteDs\Controller\Pages;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Wexample\SymfonyHelpers\Helper\RoleHelper;
use Wexample\SymfonyLoader\Controller\AbstractPagesController;
use Wexample\SymfonyRemote\Service\RemoteRegistry;
use Wexample\SymfonyRemoteDs\Traits\SymfonyRemoteDsBundleClassTrait;

/**
 * The app's remotes at a glance. The page renders the list only: each row
 * checks itself once displayed, so one slow remote never holds the page.
 */
#[Route(path: '/remote/', name: 'remote_')]
#[IsGranted(RoleHelper::ROLE_ADMIN)]
final class RemoteController extends AbstractPagesController
{
    use SymfonyRemoteDsBundleClassTrait;

    public const string ROUTE_INDEX = 'remote_index';

    #[Route(path: '', name: 'index')]
    public function index(RemoteRegistry $registry): Response
    {
        return $this->renderPage('index', [
            'remotes' => $registry->all(),
        ]);
    }
}
