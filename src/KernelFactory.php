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
    private KernelConfig $config;
    private TimeOut|null $timeOut;
    private StreamFactory $streamFactory;
    private LoggerInterface $log;

    public function __construct(
        KernelConfig $config,
        TimeOut|null $timeOut = null,
        StreamFactory $streamFactory = null,
        LoggerInterface $log = new Logger(level: LogLevel::DEBUG),
    ) {
        $this->config          = $config;
        $this->timeOut         = $timeOut;
        $this->log             = $log;
        $this->streamFactory   = $streamFactory ?? new StreamFactory(log: $this->log);
    }

    public function createKernel(): StreamKernel
    {
        return $this->streamFactory
            ->createStreamKernel($this->timeOut)
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
                factory: new HttpControllerFactory(new HttpFactory(log: $this->log))
            );
    }
}
