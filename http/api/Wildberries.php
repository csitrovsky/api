<?php

namespace http\api;

use app\core\Api;
use app\core\Defaults;
use app\core\Route;
use app\src\Error;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use function date;
use function in_array;
use function json_decode;
use function strtotime;

class Wildberries extends Api
{

    use Defaults;

    /**
     * @var string
     */
    private string $url = '';

    /**
     * @var string
     */
    private string $key = '';

    /**
     * @var array|string[]
     */
    private array $methods = ['stocks', 'orders', 'sales'];

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws Exception
     */
    #[Route("/get/{method}?from=<date>", methods: ["GET"])]
    public function get($arguments): object
    {
        // TODO: Implement get() method.
        $this->params["key"] = $this->key;
        if (empty($method = $this->verb ?? null)) {
            (new Error())->throw('Method not found', 400);
        }
        if (!in_array($method, $this->methods, true)) {
            (new Error())->throw('Unknown command', 400);
        }
        $this->params['dateFrom'] = $this->request->from ?? date('Y-m-d');
        if (in_array($method, ["orders", "sales"], true)) {
            $this->params['dateFrom'] = $this->request->from ??
                date('Y-m-d', strtotime('-1 days'));
            $this->params['flag'] = 1;
        }
        $response = (new Client())->get($this->url . $method,
            [
                'query' => $this->params,
            ]
        );
        $contents = $response->getBody()->getContents();
        $result = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        if (empty($result)) {
            (new Error())->throw('No data available', 204);
        }
        $this->results = $result;
        return (object)$this->results;
    }
}
