<?php

declare(strict_types=1);

namespace davekok\controller;

include "vendor/autoload.php";

(new ControllerKernel(config: include "config.php"))->run();
