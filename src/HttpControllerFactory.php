<?php

declare(strict_types=1);

namespace davekok\controller;

use davekok\http\HttpFactory;
use davekok\stream\Activity;
use davekok\stream\ControllerFactory;

class HttpControllerFactory implements ControllerFactory
{
    public function __construct(
        private RouteControllerFactory $routeControllerFactory,
        private HttpFactory $httpFactory = new HttpFactory(),
    ) {}

    public function createController(Activity $activity): HttpController
    {
        return new HttpController(
            $this->routeControllerFactory,
            $activity,
            $this->httpFactory->createReader($activity),
            $this->httpFactory->createWriter($activity)
        );
    }
}
