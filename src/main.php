<?php

declare(strict_types=1);

namespace davekok\controller;

include "vendor/autoload.php";

(new KernelFactory(config: include "config.php"))
    ->createKernel()
    ->run();
