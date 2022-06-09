<?php

namespace http\api;

use app\core\Api;
use app\core\Defaults;
use app\core\Route;

class Ozon extends Api
{

    use Defaults;

    #[Route("/get/{marketplace}/{method}", methods: ["GET"])]
    public function get($arguments): object
    {
        // TODO: Implement get() method.
        return (object)$this->results;
    }
}