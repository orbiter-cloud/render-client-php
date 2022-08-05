<?php declare(strict_types=1);

namespace Orbito\RenderClient;

class TemplateRef {
    protected string $id;
    protected string $fragment;

    public function __construct(string $id, string $fragment) {
        $this->id = $id;
        $this->fragment = $fragment;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getFragment(): string {
        return $this->fragment;
    }
}

