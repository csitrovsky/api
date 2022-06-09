<?php

namespace app\core;

use app\src\Database;
use app\src\Error;
use Exception;
use JsonException;
use function array_key_exists;
use function array_shift;
use function array_splice;
use function explode;
use function file_get_contents;
use function is_array;
use function is_numeric;
use function str_replace;
use function strip_tags;
use function trim;

/**
 * @property              $http
 * @property string[]     $arguments
 * @property string|null  $endpoint
 * @property string|null  $verb
 * @property mixed        $method
 * @property object       $request
 * @property false|string $contents
 * @property array        $params
 * @property mixed        $response
 */
abstract class Api extends Database implements Controller
{

    /**
     * @var array|string[]
     */
    protected array $results = ['result' => 'Empty response'];

    /**
     * @throws JsonException
     */
    public function __construct($request)
    {
        $this->arguments = explode('/', trim(str_replace(
            '\\', '/', $request
        ), '/'));
        array_splice($this->arguments, 0, 2);
        $this->endpoint = array_shift($this->arguments);
        if (array_key_exists(0, $this->arguments)
            && !is_numeric($this->arguments[0])
        ) {
            $this->verb = array_shift($this->arguments);
        }
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method === 'POST' && array_key_exists(
                'HTTP_X_HTTP_METHOD', $_SERVER
            )) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] === 'DELETE') {
                $this->method = 'DELETE';
            } else {
                if ($_SERVER['HTTP_X_HTTP_METHOD'] === 'PUT') {
                    $this->method = "$this->method = \"PUT\";";
                } else {
                    $this->response('Unexpected Header', 500);
                }
            }
        }
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function response($message, int $code = 200): bool|string
    {
        if ($code !== 200) {
            (new Error())->throw($message, $code);
        }
        return (new Error())->output($message, $code);
    }

    /**
     * @return bool|string
     * @throws JsonException
     */
    public function process(): bool|string
    {
        switch ($this->method) {
            case 'GET':
                $this->request = (object)$this->clean_inputs($_GET);
                break;
            case 'DELETE':
            case 'POST':
                $this->request = (object)$this->clean_inputs($_POST);
                break;
            case 'PUT':
                $this->request = (object)$this->clean_inputs($_GET);
                $this->contents = file_get_contents('php://input');
                break;
            default:
                $this->response('Invalid Method', 405);
                break;
        }
        if (method_exists($this, $this->endpoint)) {
            return $this->response($this->{$this->endpoint}($this->arguments));
        }
        return $this->response("No Endpoint: $this->endpoint...", 404);
    }

    /**
     * @param $data
     *
     * @return array|string
     */
    private function clean_inputs($data): array|string
    {
        $clean_input = [];
        if (!is_array($data)) {
            $clean_input = trim(strip_tags((string)$data));
        } else {
            foreach ($data as $key => $value) {
                $clean_input[$key] = $this->clean_inputs($value);
            }
        }
        return $clean_input;
    }
}