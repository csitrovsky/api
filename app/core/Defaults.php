<?php

namespace app\core;

use app\src\Error;
use Exception;
use JsonException;

trait Defaults
{

    /**
     * @param mixed $request
     * @param mixed $HTTP_ORIGIN
     *
     * @throws JsonException
     */
    public function __construct(mixed $request, mixed $HTTP_ORIGIN)
    {
        parent::__construct($request);
        $this->http = $HTTP_ORIGIN;
    }

    /**
     * @param $arguments
     *
     * @return object
     */
    public function get($arguments): object
    {
        // TODO: Implement get() method.
        try {
            (new Error())->throw('Data Not Found.', 400);
        } catch (Exception $exception) {
            $this->response = [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
        return (object)$this->results;
    }

    /**
     * @param $arguments
     *
     * @return object
     */
    public function post($arguments): object
    {
        // TODO: Implement post() method.
        try {
            (new Error())->throw('Error method post', 500);
        } catch (Exception $exception) {
            $this->response = [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
        return (object)$this->results;
    }

    /**
     * @param $arguments
     *
     * @return object
     */
    public function put($arguments): object
    {
        // TODO: Implement put() method.
        try {
            (new Error())->throw('Error method update', 400);
        } catch (Exception $exception) {
            $this->response = [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
        return (object)$this->results;
    }

    /**
     * @param $arguments
     *
     * @return object
     */
    public function delete($arguments): object
    {
        // TODO: Implement delete() method.
        try {
            (new Error())->throw('Error method delete', 500);
        } catch (Exception $exception) {
            $this->response = [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
        return (object)$this->results;
    }
}