<?php

declare(strict_types=1);

namespace AdgoalCommon\AffiliTest;

use AdgoalCommon\AffiliTest\Infrastructure\Exception\ResponseErrorException;
use AdgoalCommon\AffiliTest\Response\TestDTO;
use GuzzleHttp\Psr7\Request;
use JsonException;
use Psr\Http\Client\ClientInterface;

class Client
{
    private const BASE_URL = 'https://affilitest.com/';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param string $country
     * @param string $device
     *
     * @return TestDTO
     *
     * @throws JsonException
     * @throws ResponseErrorException
     */
    public function sendTest(string $url, string $country, string $device)
    {
        $result = $this->sendRequest('POST', 'api/v1/test?codes', [
            'url' => $url,
            'country' => $country,
            'device' => $device,
        ]);

        return TestDTO::fromRaw($result);
    }

    private function sendRequest(string $method, string $url, array $data = [])
    {
        $body = http_build_query($data);
        $headers = [
            'Authorization' => 'AT-API ' . $this->apiKey,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Content-Length' => strlen($body),
        ];
        $request = new Request($method, self::BASE_URL . $url, $headers, $body);
        $response = $this->client->sendRequest($request);
        $content = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        if (null !== $content['error']) {
            throw new ResponseErrorException($content['error']);
        }

        return $content;
    }
}
