<?php

namespace Wexample\SymfonyRemoteDs\Controller\Pages;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Wexample\SymfonyLoader\Controller\AbstractPagesController;
use Wexample\SymfonyRemote\Service\RemoteRegistry;
use Wexample\SymfonyRemoteDs\Security\RemoteAccessVoter;
use Wexample\SymfonyRemoteDs\Traits\SymfonyRemoteDsBundleClassTrait;

/**
 * The app's remotes at a glance. The page renders the list only: each row
 * checks itself once displayed, so one slow remote never holds the page.
 * Access follows wexample_symfony_remote_ds.access_role (ROLE_ADMIN by default).
 */
#[Route(path: '/remote/', name: 'remote_')]
#[IsGranted(RemoteAccessVoter::ATTRIBUTE)]
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
