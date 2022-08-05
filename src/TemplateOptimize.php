<?php declare(strict_types=1);

namespace Orbito\RenderClient;

use JetBrains\PhpStorm\Pure;

class TemplateOptimize {
    /**
     * @var bool minify HTML
     */
    public bool $minify;
    /**
     * @var bool remove unused CSS/CSS-Classes
     */
    public bool $cleanCss;
    /**
     * @var bool css auto prefixer
     */
    public bool $cssAutoPrefix;
    /**
     * @var bool nanocss optimizer
     */
    public bool $nanoCss;
    /**
     * @var string[] css IDs and class patterns to ignore for HTML cleanup
     */
    public array $cleanCssWhitelist;
    /**
     * @var bool if the css rules should be inlined to hte HTML tags
     */
    public bool $inlineCss;
    /**
     * @var \stdClass extra options for the html-minifier
     */
    public \stdClass $minifyHtmlOptions;

    #[Pure] public function __construct() {
        $this->minifyHtmlOptions = new \stdClass();
    }

    protected static array $type_factories = [
        'email' => [self::class, 'makeForEmail'],
        'html' => [self::class, 'makeForHtml'],
    ];

    public static function makeFor(string $content_type) {
        return self::$type_factories[$content_type]();
    }

    #[Pure] public static function makeForHtml() {
        $opt = new self();
        $opt->minify = true;
        $opt->cssAutoPrefix = true;
        $opt->nanoCss = false;
        $opt->cleanCss = true;
        $opt->cleanCssWhitelist = [];
        $opt->inlineCss = false;
        return $opt;
    }

    #[Pure] public static function makeForEmail() {
        $opt = new self();
        $opt->minify = true;
        $opt->cssAutoPrefix = false;
        $opt->nanoCss = false;
        // CSS cleanup is unnecessary when using `inlineCss`
        $opt->cleanCss = false;
        $opt->cleanCssWhitelist = [];
        $opt->minifyHtmlOptions->html5 = false;
        $opt->minifyHtmlOptions->removeAttributeQuotes = false;
        $opt->inlineCss = true;
        return $opt;
    }
}

