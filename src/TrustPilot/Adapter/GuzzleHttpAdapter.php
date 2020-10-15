<?php
/*
 * This file is part of the TrustPilot library.
 *
 * (c) Graphem Solutions <info@graphem.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TrustPilot\Adapter;

/**
 * @author Graphem Solutions <info@graphem.ca>
 */

use TrustPilot\Exception\HttpException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class GuzzleHttpAdapter implements AdapterInterface
{
   /**
     * @var Client|ClientInterface
     */
    protected $client;

    /**
     * @var
     */
    protected $response;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * guzzle_http_adapter constructor.
     *
     * @param array $headers
     * @param string $endpoint
     * @param ClientInterface|NULL $client
     */
    public function __construct(array $headers, string $endpoint = '', ?ClientInterface $client = null)
    {

        $this->endpoint = $endpoint;
        $this->client = $client ?: new Client($headers);  
        
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $url, array $options = [])
    {
        try {
            $this->response = $this->client->get(
                $this->endpoint . $url,
                $options
            );
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $url, array $content = [])
    {
        try {
            $this->response = $this->client->post(
                $this->endpoint . $url,
                $content
            );
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $this->response) {
            return NULL;
        }

        return [
            'reset' => (int) (string) $this->response->getHeader('RateLimit-Reset'),
            'remaining' => (int) (string) $this->response->getHeader('RateLimit-Remaining'),
            'limit' => (int) (string) $this->response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @throws HttpException
     */
    protected function handleError()
    {
        $body = (string) $this->response->getBody();

        $code = (int) $this->response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content) ? print_r($content,true) : 'Request not processed.', $code);
    }
}
