# Orbito\RenderClient

[![Latest Stable Version](https://poser.pugx.org/orbito/render-client/version.svg)](https://packagist.org/packages/orbito/render-client)
[![Latest Unstable Version](https://poser.pugx.org/orbito/render-client/v/unstable.svg)](https://packagist.org/packages/orbito/render-client)
[![codecov](https://codecov.io/gh/orbiter-cloud/render-client-php/branch/master/graph/badge.svg?token=1bWW7plF1C)](https://codecov.io/gh/orbiter-cloud/render-client-php)
[![Total Downloads](https://poser.pugx.org/orbito/render-client/downloads.svg)](https://packagist.org/packages/orbito/render-client)
[![Github actions Build](https://github.com/orbiter-cloud/render-client-php/actions/workflows/blank.yml/badge.svg)](https://github.com/orbiter-cloud/render-client-php/actions)
[![PHP Version Require](http://poser.pugx.org/orbito/render-client/require/php)](https://packagist.org/packages/orbito/render-client)

HTTP client to use with [Orbito Render](https://github.com/orbiter-cloud/render-service).

```shell
composer require orbito/render-client
```

```php
$render_client = return new \Orbito\RenderClient\RenderClient(
    [
        'default' => 'http://localhost:4250',
    ],
    new \GuzzleHttp\Client(),
    new \GuzzleHttp\Psr7\HttpFactory(),
    new \GuzzleHttp\Psr7\HttpFactory(),
    $logger,
);
$content = $this->renderer->render(
    'default', 'en', 'main',
    new TemplateRef('my-tpl', 'pages/default'),
    TemplateOptimize::makeFor('html'),
    [],// data
    [],// styleVars
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
error_log('Generated style in ' . $content->styleTime . 'ms');
$css = $content->style;
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
