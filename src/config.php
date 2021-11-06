<?php

declare(strict_types=1);

namespace davekok\controller;

return new KernelConfig(
    name: "davekok.nl",
    smtpUrl: "tcp://0.0.0.0:8025",
    httpUrl: "tcp://0.0.0.0:8080",
);
