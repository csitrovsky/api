<?php

namespace app\src;

use Exception;
use JsonException;
use function header;
use function json_encode;

class Error
{

    /**
     * @var int
     */
    private int $headers = JSON_PRETTY_PRINT;

    /**
     * @var array|string[]
     */
    private array $status = [
        200 => 'Ok',
        204 => 'No Content',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        409 => 'Conflict',
        500 => 'Internal Server Error'
    ];

    /**
     *
     */
    public function __construct()
    {
        $this->headers();
    }

    /**
     *
     */
    private function headers(): void
    {
        header('Content-Type: application/json');
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function throw($message, int $code = 200): void
    {
        if ($code !== 200) {
            $success = false;
        }
        throw new Exception($this->output([
            'success' => $success ?? true,
            'message' => $message
        ], $code), $code);
    }

    /**
     * @throws JsonException
     */
    public function output($message, int $code = 200): bool|string
    {
        header("HTTP/1.1 $code " . $this->request_status($code));
        return json_encode($message, JSON_THROW_ON_ERROR | $this->headers);
    }

    /**
     * @param int $code
     *
     * @return string
     */
    private function request_status(int $code): string
    {
        return ($this->status[$code]) ?: $this->status[500];
    }
}