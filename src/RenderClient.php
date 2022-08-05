<?php declare(strict_types=1);

namespace Orbito\RenderClient;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

class RenderClient {
    protected ClientInterface $http_client;
    protected RequestFactoryInterface $request_factory;
    protected StreamFactoryInterface $stream_factory;
    protected LoggerInterface $logger;

    protected array $render_backends;

    public function __construct(
        array                   $render_backends,
        ClientInterface         $http_client,
        RequestFactoryInterface $request_factory,
        StreamFactoryInterface  $stream_factory,
        LoggerInterface         $logger,
    ) {
        $this->render_backends = $render_backends;
        $this->http_client = $http_client;
        $this->request_factory = $request_factory;
        $this->stream_factory = $stream_factory;
        $this->logger = $logger;
    }

    protected function makeRequest(
        string          $method,
        string          $url,
        array|\stdClass $data,
        ?string         $trace = null,
    ): RequestInterface {
        $req = $this->request_factory->createRequest($method, $url)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('User-Agent', 'Orbito RenderClient; PHP')
            ->withBody($this->stream_factory->createStream(json_encode($data, JSON_THROW_ON_ERROR)));
        if($trace) {
            $req = $req->withHeader('X-Trace-Id', $trace);
        }
        return $req;
    }

    public function render(
        string           $renderer,
        string           $locale,
        string           $style,
        TemplateRef      $template,
        TemplateOptimize $optimize,
        array            $data,
        array            $styleVars,
        ?array           $options = [],
        ?string          $trace = null,
    ) {
        if(!isset($this->render_backends[$renderer])) {
            throw new \RuntimeException('Missing renderer backend for id `' . $renderer . '`');
        }
        $backend = $this->render_backends[$renderer];
        $request =
            $this->makeRequest(
                'POST', $backend . '/template/' . $template->getId() . '/render/' . $template->getFragment(),
                [
                    'style' => $style,
                    'styleVars' => empty($styleVars) ? new \stdClass() : $styleVars,
                    'data' => empty($data) ? new \stdClass() : $data,
                    'context' => [
                        'locale' => $locale,
                    ],
                    'optimize' => $optimize,
                    'renderText' => $options['renderText'] ?? false,
                ],
                $trace,
            );
        try {
            $res = $this->http_client->sendRequest($request);
        } catch(NetworkExceptionInterface $e) {
            $this->logger->error('Failed to connect to render service: ' . $e->getMessage(), [
                'trace' => $trace,
            ]);
            // todo: throw with client consumable error messages
            return null;
        }
        if($res->getStatusCode() === 200) {
            try {
                return json_decode($res->getBody()->__toString(), false, 512, JSON_THROW_ON_ERROR);
            } catch(\JsonException $e) {
                $this->logger->error('Failed to parse render result: ' . $e->getMessage(), [
                    'trace' => $trace,
                ]);
            }
        }
        $this->logger->error('RenderClient failed to render a template: ' . $res->getBody()->__toString(), [
            'trace' => $trace,
        ]);
        return null;
    }

    public function style(
        string                 $renderer,
        string                 $style,
        string                 $template,
        array                  $styleVars,
        array|TemplateOptimize $optimize,
        ?string                $trace = null,
    ) {
        if(!isset($this->render_backends[$renderer])) {
            throw new \RuntimeException('Missing renderer backend for id `' . $renderer . '`');
        }
        $backend = $this->render_backends[$renderer];
        $request =
            $this->makeRequest(
                'POST', $backend . '/template/' . $template . '/style/' . $style,
                [
                    'styleVars' => empty($styleVars) ? new \stdClass() : $styleVars,
                    'optimize' => [
                        'nanoCss' => is_array($optimize) ? $optimize['nanoCss'] : $optimize->nanoCss,
                        'cssAutoPrefix' => is_array($optimize) ? $optimize['cssAutoPrefix'] : $optimize->cssAutoPrefix,
                    ],
                ],
                $trace,
            );
        try {
            $res = $this->http_client->sendRequest($request);
        } catch(NetworkExceptionInterface $e) {
            $this->logger->error('Failed to connect to render service: ' . $e->getMessage(), [
                'trace' => $trace,
            ]);
            // todo: throw with client consumable error messages
            return null;
        }
        if($res->getStatusCode() === 200) {
            try {
                return json_decode($res->getBody()->__toString(), false, 512, JSON_THROW_ON_ERROR);
            } catch(\JsonException $e) {
                $this->logger->error('Failed to parse style result: ' . $e->getMessage(), [
                    'trace' => $trace,
                ]);
            }
        }
        $this->logger->error('RenderClient failed to generate style: ' . $res->getBody()->__toString(), [
            'trace' => $trace,
        ]);
        return null;
    }
}
