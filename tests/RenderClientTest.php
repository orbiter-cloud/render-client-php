<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Monolog\Logger;

final class RenderClientTest extends TestCase {

    protected function makeRenderClient() {
        $log = new Logger('name');
        $log->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', Logger::DEBUG));
        return new \Orbito\RenderClient\RenderClient(
            [
                'default' => 'http://localhost:4250',
            ],
            new \GuzzleHttp\Client(),
            new \GuzzleHttp\Psr7\HttpFactory(),
            new \GuzzleHttp\Psr7\HttpFactory(),
            $log,
        );
    }

    public function testCanBeCreated(): void {
        $render_client = $this->makeRenderClient();
        self::assertInstanceOf(
            \Orbito\RenderClient\RenderClient::class,
            $render_client,
        );
    }
}
