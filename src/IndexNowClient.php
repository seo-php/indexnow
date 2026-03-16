<?php

declare(strict_types=1);

namespace Seo\IndexNow;

use Http\Discovery\Psr18Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class IndexNowClient
{
    private function __construct(
        private string $host,
        private IndexNowKey $key,
        private ?string $keyLocation,
        private Endpoint $endpoint,
        private Psr18Client $client,
    ) {}

    public static function create(
        string $host,
        string $key,
        ?string $keyLocation = null,
        ?Endpoint $endpoint = null,
        ?ClientInterface $client = null,
    ): self {
        return new self(
            $host,
            IndexNowKey::make($key),
            $keyLocation,
            $endpoint ?? Endpoint::Global,
            new Psr18Client($client),
        );
    }

    /**
     * @param array<int, string>|string $urls
     */
    public function submit(array | string $urls): ResponseInterface
    {
        $request = $this->client->createRequest('POST', $this->endpoint->value)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody($this->client->createStream($this->buildBody($urls)))
        ;

        return $this->client->sendRequest($request);
    }

    /**
     * @param array<int, string>|string $urls
     */
    private function buildBody(array | string $urls): string
    {
        $body = [
            'host' => $this->host,
            'key' => $this->key->value,
            'urlList' => is_array($urls) ? $urls : [$urls],
        ];

        if ($this->keyLocation) {
            $body['keyLocation'] = $this->keyLocation;
        }

        return json_encode($body, flags: JSON_THROW_ON_ERROR);
    }
}
