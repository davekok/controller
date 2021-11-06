<?php

declare(strict_types=1);

namespace davekok\controller;

class KernelConfig
{
    public function __construct(
        public readonly string $name,
        public readonly string $httpUrl,
        public readonly string $smtpUrl,
    ) {}
}
