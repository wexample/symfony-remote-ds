<?php

namespace Wexample\SymfonyRemoteDs\Tests\Unit\Api\Controller;

use PHPUnit\Framework\TestCase;
use Wexample\PhpRemote\Class\RemoteRegistry;
use Wexample\PhpRemote\Class\RemoteStatus;
use Wexample\SymfonyRemoteDs\Api\Controller\RemoteController;
use Wexample\SymfonyRemoteDs\Tests\Fixtures\FixedRemote;

class RemoteControllerTest extends TestCase
{
    private RemoteRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new RemoteRegistry([
            new FixedRemote('billing', RemoteStatus::up()),
            new FixedRemote('mailer', RemoteStatus::unconfigured('Missing: base_url.')),
        ]);
    }

    public function testACheckAnswersTheStatusInTheEnvelope(): void
    {
        $envelope = $this->call('billing');

        $this->assertSame('success', $envelope['type']);
        $this->assertSame('billing', $envelope['data']['key']);
        $this->assertSame('Billing', $envelope['data']['label']);
        $this->assertSame('up', $envelope['data']['state']);
        $this->assertIsFloat($envelope['data']['latency']);
    }

    public function testAnUnconfiguredRemoteSaysWhatIsMissing(): void
    {
        $envelope = $this->call('mailer');

        $this->assertSame('unconfigured', $envelope['data']['state']);
        $this->assertSame('Missing: base_url.', $envelope['data']['message']);
    }

    public function testAnUnknownRemoteIsAnError(): void
    {
        $this->assertSame('error', $this->call('missing')['type']);
    }

    private function call(string $key): array
    {
        $response = (new RemoteController())->check($key, $this->registry);

        return json_decode((string) $response->toJsonResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
