<?php

namespace app;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function spl_autoload_register;

class System extends Autoload
{

    /**
     *
     */
    public function __construct()
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }

    /**
     * @param false $prepend
     */
    public function register(bool $prepend = false): void
    {
        spl_autoload_register(function ($namespace) {
            $this->includes($namespace);
        }, true, $prepend);
    }
}