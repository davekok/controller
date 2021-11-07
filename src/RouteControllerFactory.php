<?php

declare(strict_types=1);

namespace davekok\controller;

class RouteControllerFactory
{
    public function __construct(private ControllerConfig $config, private array $controllers = []) {}

    public function createMainController(): MainController
    {
        return $this->controllers["main"] ?? new MainController($this->config);
    }
}
