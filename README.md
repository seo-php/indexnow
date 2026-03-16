# IndexNow

<p>
<a href="https://packagist.org/packages/seophp/indexnow"><img src="https://img.shields.io/packagist/dt/seophp/indexnow" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/seophp/indexnow"><img src="https://img.shields.io/packagist/v/seophp/indexnow" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/seophp/indexnow"><img src="https://img.shields.io/packagist/l/seophp/indexnow" alt="License"></a>
<a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.4+-777BB4?logo=php" alt="PHP Minimum Version"></a>
</p>


Lightweight, framework-agnostic `IndexNow` management for PHP.

## Requirements

- PHP 8.4 or higher

## Installation

Install via [Composer](https://getcomposer.org):

```bash
composer require seophp/indexnow
```

## Usage

### Creating a client

```php
use Seo\IndexNow\IndexNowClient;

$client = IndexNowClient::create(
    host: 'www.example.com',
    key: 'your-api-key',
);
```

> [!NOTE]
The `key` must be a string of 8–128 characters containing only letters (`a-z`, `A-Z`), digits (`0-9`), and dashes (`-`). You generate this key yourself and host a text file at `https://www.example.com/{your-key}.txt` containing the key so search engines can verify ownership.

### Specifying a custom key file location

If you host the key file somewhere other than the root of your site, pass its URL as `keyLocation`:

```php
$client = IndexNowClient::create(
    host: 'www.example.com',
    key: 'your-api-key',
    keyLocation: 'https://www.example.com/seo/my-key-file.txt',
);
```

### Choosing a search engine endpoint

By default submissions go to the global IndexNow API (`https://api.indexnow.org/indexnow`), which forwards your URLs to all participating search engines. You can target a specific engine instead using the `Endpoint` enum:

```php
use Seo\IndexNow\Endpoint;
use Seo\IndexNow\IndexNowClient;

$client = IndexNowClient::create(
    host: 'www.example.com',
    key: 'your-api-key',
    endpoint: Endpoint::Bing,
);
```

| Enum case | Search engine | URL |
|---|---|---|
| `Endpoint::Global` | IndexNow global endpoint | `https://api.indexnow.org/indexnow` |
| `Endpoint::Bing` | Bing | `https://www.bing.com/indexnow` |
| `Endpoint::Yandex` | Yandex | `https://yandex.com/indexnow` |
| `Endpoint::Naver` | Naver | `https://searchadvisor.naver.com/indexnow` |
| `Endpoint::Seznam` | Seznam.cz | `https://search.seznam.cz/indexnow` |
| `Endpoint::Yep` | Yep | `https://indexnow.yep.com/indexnow` |
| `Endpoint::Amazon` | Amazon | `https://indexnow.amazonbot.amazon/indexnow` |

### Submitting URLs

```php
// Single url
$client->submit('https://www.example.com/new-article');

// Multiple urls
$client->submit([
    'https://www.example.com/new-article',
    'https://www.example.com/updated-page',
    'https://www.example.com/deleted-page',
]);
```

## License

The MIT License (MIT). See the [license file](LICENSE) for more information.