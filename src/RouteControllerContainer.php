<?php

declare(strict_types=1);

namespace davekok\controller;

use Exception;

class RouteControllerContainer
{
    public function __construct(
        private ControllerConfig $config,
        private array $controllers = [],
    ) {}

    public function getMainController(): MainController
    {
        return $this->controllers["main"] ??= new MainController($this->config);
    }
}
