<?php

declare(strict_types=1);

namespace davekok\controller;

use davekok\stream\Url;

class ControllerConfig
{
    public function __construct(
        public readonly string $domainName,
        public readonly string $title,
        public readonly Url    $httpUrl,
        public readonly Url    $smtpUrl,
        public readonly string $body,
    ) {}
}
