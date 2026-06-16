<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Http\Client\Exception\TransferException;
use Http\Mock\Client as MockClient;
use Psr\Http\Message\RequestInterface;
use Seo\IndexNow\Endpoint;
use Seo\IndexNow\IndexNowClient;
use Seo\IndexNow\InvalidIndexNowKeyException;

it('creates a client with minimal arguments', function (): void {
    $client = IndexNowClient::create('example.com', 'abcd1234');

    expect($client)->toBeInstanceOf(IndexNowClient::class);
});

it('creates a client with all arguments', function (): void {
    $client = IndexNowClient::create(
        host: 'example.com',
        key: 'abcd1234',
        keyLocation: 'https://example.com/my-key.txt',
        endpoint: Endpoint::Bing,
        client: new MockClient(),
    );

    expect($client)->toBeInstanceOf(IndexNowClient::class);
});

it('throws InvalidIndexNowKeyException when key is invalid', function (): void {
    IndexNowClient::create('example.com', 'bad');
})->throws(InvalidIndexNowKeyException::class);

it('sends a POST request to the default endpoint for a single URL', function (): void {
    $mock = new MockClient();
    $client = IndexNowClient::create('example.com', 'abcd1234', client: $mock);

    $client->submit('https://example.com/page');

    $request = $mock->getLastRequest();
    assert($request instanceof RequestInterface);
    expect($request->getMethod())->toBe('POST')
        ->and((string) $request->getUri())->toBe('https://api.indexnow.org/indexnow')
        ->and($request->getHeaderLine('Content-Type'))->toBe('application/json; charset=utf-8')
    ;
});

it('wraps a single URL string in urlList and sets correct body fields', function (): void {
    $mock = new MockClient();
    $client = IndexNowClient::create('example.com', 'abcd1234', client: $mock);

    $client->submit('https://example.com/page');

    $lastRequest = $mock->getLastRequest();
    assert($lastRequest instanceof RequestInterface);
    /** @var array<string, mixed> $body */
    $body = json_decode((string) $lastRequest->getBody(), true);
    expect($body['host'])->toBe('example.com')
        ->and($body['key'])->toBe('abcd1234')
        ->and($body['urlList'])->toBe(['https://example.com/page'])
        ->and($body)->not->toHaveKey('keyLocation')
    ;
});

it('places all URLs in urlList when an array is submitted', function (): void {
    $mock = new MockClient();
    $client = IndexNowClient::create('example.com', 'abcd1234', client: $mock);
    $urls = ['https://example.com/p1', 'https://example.com/p2', 'https://example.com/p3'];

    $client->submit($urls);

    $lastRequest = $mock->getLastRequest();
    assert($lastRequest instanceof RequestInterface);
    /** @var array<string, mixed> $body */
    $body = json_decode((string) $lastRequest->getBody(), true);
    expect($body['urlList'])->toBe($urls);
});

it('includes keyLocation in the body when provided', function (): void {
    $mock = new MockClient();
    $keyFile = 'https://example.com/mykey.txt';
    $client = IndexNowClient::create('example.com', 'abcd1234', $keyFile, client: $mock);

    $client->submit('https://example.com/page');

    $lastRequest = $mock->getLastRequest();
    assert($lastRequest instanceof RequestInterface);
    /** @var array<string, mixed> $body */
    $body = json_decode((string) $lastRequest->getBody(), true);
    expect($body['keyLocation'])->toBe($keyFile);
});

it('omits keyLocation from the body when not provided', function (): void {
    $mock = new MockClient();
    $client = IndexNowClient::create('example.com', 'abcd1234', client: $mock);

    $client->submit('https://example.com/page');

    $lastRequest = $mock->getLastRequest();
    assert($lastRequest instanceof RequestInterface);
    /** @var array<string, mixed> $body */
    $body = json_decode((string) $lastRequest->getBody(), true);
    expect($body)->not->toHaveKey('keyLocation');
});

it('sends the request to a custom endpoint', function (): void {
    $mock = new MockClient();
    $client = IndexNowClient::create('example.com', 'abcd1234', endpoint: Endpoint::Bing, client: $mock);

    $client->submit('https://example.com/page');

    $lastRequest = $mock->getLastRequest();
    assert($lastRequest instanceof RequestInterface);
    expect((string) $lastRequest->getUri())->toBe('https://www.bing.com/indexnow');
});

it('returns the HTTP response from submit', function (): void {
    $mock = new MockClient();
    $mock->addResponse(new Response(200));
    $client = IndexNowClient::create('example.com', 'abcd1234', client: $mock);

    $response = $client->submit('https://example.com/page');

    expect($response->getStatusCode())->toBe(200);
});

it('passes through non-2xx responses without throwing', function (): void {
    $mock = new MockClient();
    $mock->addResponse(new Response(429));
    $client = IndexNowClient::create('example.com', 'abcd1234', client: $mock);

    $response = $client->submit('https://example.com/page');

    expect($response->getStatusCode())->toBe(429);
});

it('propagates HTTP client exceptions from the underlying client', function (): void {
    $mock = new MockClient();
    $mock->addException(new TransferException('Connection failed'));
    $client = IndexNowClient::create('example.com', 'abcd1234', client: $mock);

    $client->submit('https://example.com/page');
})->throws(TransferException::class, 'Connection failed');
