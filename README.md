# Orbito\RenderClient

[![Latest Stable Version](https://poser.pugx.org/orbito/render-client/version)](https://packagist.org/packages/orbito/render-client)
[![Latest Unstable Version](https://poser.pugx.org/orbito/render-client/v/unstable)](https://packagist.org/packages/orbito/render-client)
[![codecov](https://codecov.io/gh/orbiter-cloud/render-client-php/branch/main/graph/badge.svg)](https://codecov.io/gh/orbiter-cloud/render-client-php)
[![Total Downloads](https://poser.pugx.org/orbito/render-client/downloads.svg)](https://packagist.org/packages/orbito/render-client)
[![Github actions Build](https://github.com/orbiter-cloud/render-client-php/actions/workflows/blank.yml/badge.svg)](https://github.com/orbiter-cloud/render-client-php/actions)
[![PHP Version Require](http://poser.pugx.org/orbito/render-client/require/php)](https://packagist.org/packages/orbito/render-client)

HTTP client to use with [Orbito Render](https://github.com/orbiter-cloud/render-service).

```shell
composer require orbito/render-client
```

```php
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Orbito\RenderClient\RenderClient;
use Orbito\RenderClient\TemplateRef;
use Orbito\RenderClient\TemplateOptimize;

$render_client = new RenderClient(
    [
        'default' => 'http://localhost:4250',
    ],
    ClientInterface         $http_client,
    RequestFactoryInterface $request_factory,
    StreamFactoryInterface  $stream_factory,
    LoggerInterface         $logger,
);
$content = $this->renderer->render(
    'default', 'en', 'main',
    new TemplateRef('my-tpl', 'pages/default'),
    TemplateOptimize::makeFor('html'),
    [],// data
    [],// styleVars
    ['renderText' => true],// optional, options
    null|string,// optional, trace for logging
);
if(!$content) {
    return;
}
error_log('Rendered in ' . $content->renderTime . 'ms');
$html = $content->rendered;

$style = $this->renderer->style(
    'default', 'main', 'my-tpl',
    ['nanoCss' => true, 'cssAutoPrefix' => false],
    [],// styleVars
);
if(!$style) {
    return;
}
error_log('Generated style in ' . $style->styleTime . 'ms');
$css = $style->style;
```

## Dev Notices

Commands to set up and run e.g. tests:

```bash
# on windows:
docker run -it --rm -v %cd%:/app composer install

docker run -it --rm -v %cd%:/var/www/html php:8-cli-alpine sh

docker run --rm -v %cd%:/var/www/html php:8-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"

# on unix:
docker run -it --rm -v `pwd`:/app composer install

docker run -it --rm -v `pwd`:/var/www/html php:8-cli-alpine sh

docker run --rm -v `pwd`:/var/www/html php:8-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"
```

## Versions

This project adheres to [semver](https://semver.org/), **until `1.0.0`** and beginning with `0.1.0`: all `0.x.0` releases are like MAJOR releases and all `0.0.x` like MINOR or PATCH, modules below `0.1.0` should be considered experimental.

## License

This project is free software distributed under the [**MIT LICENSE**](LICENSE).

[MIT License](https://github.com/orbiter-cloud/render-client-php/blob/main/LICENSE)

© 2022 [bemit](https://bemit.codes)

### Contributors

By committing your code to the code repository you agree to release the code under the MIT License attached to the repository.

***
