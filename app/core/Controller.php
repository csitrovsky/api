<?php

namespace app\core;

interface Controller
{

    /**
     * @param $arguments
     *
     * @return mixed
     */
    public function get($arguments): mixed;

    /**
     * @param $arguments
     *
     * @return mixed
     */
    public function post($arguments): mixed;

    /**
     * @param $arguments
     *
     * @return mixed
     */
    public function put($arguments): mixed;

    /**
     * @param $arguments
     *
     * @return mixed
     */
    public function delete($arguments): mixed;
}