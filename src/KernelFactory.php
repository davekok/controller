<?php

declare(strict_types=1);

namespace davekok\controller;

use davekok\http\HttpFactory;
use davekok\log\Logger;
use davekok\stream\context\Options;
use davekok\stream\context\SocketOptions;
use davekok\stream\StreamFactory;
use davekok\stream\StreamKernel;
use davekok\stream\TimeOut;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class KernelFactory
{
    private StreamFactory         $streamFactory;
    private HttpControllerFactory $httpControllerFactory;

    public function __construct(
        private ControllerConfig   $config,
        TimeOut|null               $timeOut               = null,
        StreamFactory|null         $streamFactory         = null,
        HttpControllerFactory|null $httpControllerFactory = null,
        LoggerInterface            $log                   = new Logger(level: LogLevel::DEBUG),
    ) {
        $this->config                = $config;
        $this->streamFactory         = $streamFactory ?? new StreamFactory($timeOut, $log);
        $this->httpControllerFactory = $httpControllerFactory ?? new HttpControllerFactory(
            new RouteControllerFactory($config),
            new HttpFactory($log),
        );
    }

    public function createKernel(): StreamKernel
    {
        return $this->streamFactory
            ->createStreamKernel()
            ->addStream(
                stream: $this->streamFactory->createPassiveSocketStream(
                    url: $this->config->httpUrl,
                    context: new Options(
                        socket: new SocketOptions(
                            backLog: 10,
                            reusePort: true
                        )
                    )
                ),
                factory: $this->httpControllerFactory
            );
    }
}
