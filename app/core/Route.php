<?php

namespace app\core;

use app\src\Error;
use Attribute;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use function array_key_exists;
use function array_shift;
use function class_exists;
use function count;
use function explode;
use function str_replace;
use function trim;

/**
 * @property string            $url
 * @property array             $methods
 * @property false|string[]    $argument
 * @property mixed|string|null $type
 * @property mixed|string|null $class
 */
#[Attribute] class Route
{

    /**
     * @param string $url
     * @param array  $methods
     */
    public function __construct(string $url, array $methods = [])
    {
        $this->url = $url;
        $this->methods = $methods;
    }

    /**
     * @throws JsonException
     */
    public function engine(): void
    {
        if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
        }
        $this->argument = explode('/', trim(str_replace(
            '\\', '/', $this->url
        ), '/'));
        $this->type = array_shift($this->argument);
        try {
            switch ($this->type) {
                case 'admin':
                    (new Error())->throw('Forbidden', 403);
                    break;
                case 'api':
                    if (!($this->class = array_shift($this->argument))) {
                        (new Error())->throw('There is no api method', 400);
                    }
                    if (class_exists(($this->class = "\http\\$this->type\\" . $this->class))) {
                        if (!count($this->argument)) {
                            (new Error())->throw('Request is empty', 400);
                        }
                        die((new $this->class($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']))->process());
                    }
                    (new Error())->throw('Method not found', 400);
                    break;
                default:
                    (new Error())->throw('Not Found', 404);
                    break;
            }
        } catch (GuzzleException) {
            die((new Error())->output([
                'success' => false,
                'message' => 'Request limit exceeded',
            ], 500));
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
}