<?php

declare(strict_types=1);

namespace Seo\IndexNow;

enum Endpoint: string
{
    case Global = 'https://api.indexnow.org/indexnow';
    case Amazon = 'https://indexnow.amazonbot.amazon/indexnow';
    case Bing = 'https://www.bing.com/indexnow';
    case Naver = 'https://searchadvisor.naver.com/indexnow';
    case Seznam = 'https://search.seznam.cz/indexnow';
    case Yandex = 'https://yandex.com/indexnow';
    case Yep = 'https://indexnow.yep.com/indexnow';
}
