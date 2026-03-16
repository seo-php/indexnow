<?php

declare(strict_types=1);

use Seo\IndexNow\Endpoint;

it('each endpoint has the correct URL', function (Endpoint $endpoint, string $url): void {
    expect($endpoint->value)->toBe($url);
})->with([
    [Endpoint::Global, 'https://api.indexnow.org/indexnow'],
    [Endpoint::Amazon, 'https://indexnow.amazonbot.amazon/indexnow'],
    [Endpoint::Bing, 'https://www.bing.com/indexnow'],
    [Endpoint::Naver, 'https://searchadvisor.naver.com/indexnow'],
    [Endpoint::Seznam, 'https://search.seznam.cz/indexnow'],
    [Endpoint::Yandex, 'https://yandex.com/indexnow'],
    [Endpoint::Yep, 'https://indexnow.yep.com/indexnow'],
]);
