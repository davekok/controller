<?php

declare(strict_types=1);

namespace davekok\controller;

use davekok\http\HttpFactory;
use davekok\stream\context\Options;
use davekok\stream\context\SocketOptions;
use davekok\stream\StreamKernel;
use davekok\stream\TimeOut;

class ControllerKernel implements TimeOut
{
    private StreamKernel $streamKernel;

    public function __construct(
        ControllerConfig           $config,
        StreamKernel|null          $streamKernel          = null,
        HttpControllerFactory|null $httpControllerFactory = null,
    ) {
        $this->streamKernel = $streamKernel ?? new StreamKernel(timeOut: $this);
        $this->streamKernel->addPassiveSocketStream(
            factory: $httpControllerFactory ?? new HttpControllerFactory(new RouteControllerContainer(config: $config)),
            url:     $config->httpUrl,
            context: new Options(
                socket: new SocketOptions(
                    backLog:   10,
                    reusePort: true
                )
            )
        );
    }

    public function run(): noreturn
    {
        $this->streamKernel->run();
    }

    /**
     * Return number of seconds from now to next timeout.
     */
    public function getNextTimeOut(): int|null
    {
        return null;
    }

    /**
     * Called when timeout is reached.
     */
    public function timeOut(): void
    {
    }
}
