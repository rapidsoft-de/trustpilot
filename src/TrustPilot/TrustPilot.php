<?php

/*
 * This file is part of the TrustPilot library.
 *
 * (c) Graphem Solutions <info@graphem.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TrustPilot;

/**
 * Trustpilot API module
 *
 * This code is based on the Trustpilot API code provided by
 * Graphem Solutions <info@graphem.ca>.
 *
 * @see https://packagist.org/packages/graphem/trustpilot
 * @filesource https://github.com/graphem/trustpilot
 *
 * Sadly the base code had too many errors restricting a stable
 * usage with all our needs, so a modified and optimized version
 * was built.
 * Some modules were not finished and still remain unimplemented yet.
 * This API module work is still in progress and may be updated to future needs.
 *
 * @version    0.1
 * @author     Graphem Solutions <info@graphem.ca>
 * @author     Martin Sobotta <msobotta@rapidsoft.de>
 * @copyright  2017 rapidsoft operating GmbH <http://www.rapidsoft.de>
 */

use TrustPilot\Adapter\AdapterInterface;
use TrustPilot\Adapter\GuzzleHttpAdapter;
use TrustPilot\Api\Authorize;
use TrustPilot\Api\Categories;
use TrustPilot\Api\Consumer;
use TrustPilot\Api\Invitation;
use TrustPilot\Api\Resources;
use TrustPilot\Api\BusinessUnit;
use TrustPilot\Api\ProductReviews;
use TrustPilot\Api\ServiceReviews;

class TrustPilot
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://api.trustpilot.com/v1/';

    /**
     * @var string
     */
    protected $endpoint;
    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $adapter;

    /**
     * @var Object
     */
    protected $token;

    /**
     * @var AdapterInterface
     */
    protected $client;

    /**
     * TrustPilot constructor.
     *
     * @param string $apiKey
     * @param string $secret
     * @param null|string $endpoint
     */
    public function __construct($apiKey, $secret, $endpoint = null)
    {
        $this->apiKey = $apiKey;
        $this->secret = $secret;
        $this->endpoint = $endpoint ?: static::ENDPOINT;
    }

    /**
     * Set the access token
     *
     * @param object $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        $auth = $this->authorize();
        $auth->setToken($this->token);

        if($auth->isRefreshedToken()){
            $this->token = $auth->getToken();
        }

    }

    /**
     * Get the access token
     *
     * @return \stdClass
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Initiate the client for API transaction
     *
     * @param AdapterInterface|NULL $adapter
     * @param array $headers
     * @return TrustPilot
     */
    protected function setAdapter(AdapterInterface $adapter = null, $headers = [])
    {
        if(is_null($adapter)){
            $this->client = new GuzzleHttpAdapter($headers,$this->endpoint);
            return $this;
        }
        $this->client = new $adapter($headers,$this->endpoint);
        return $this;
    }

    /**
     * Set adapter to use token from oauth(2)
     */
    protected function setAdapterWithToken()
    {
        $headers = ['headers' =>
                        ['Authorization' => 'Bearer '. $this->token->access_token]
                   ];
        $this->setAdapter($this->adapter,$headers);
    }

    /**
     * Set adapter to use API key
     */
    protected function setAdapterWithApikey()
    {
        $headers = ['headers' =>
                        ['apikey' => $this->apiKey]
                   ];
        $this->setAdapter($this->adapter,$headers);
    }

    /**
     * Get the client
     *
     * @return AdapterInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Authorize
     */
    public function authorize()
    {
        $headers = ['headers' =>
                        ['Authorization' => base64_encode($this->apiKey . ':' . $this->secret) ]
                   ];
        $this->setAdapter($this->adapter,$headers);
        return new Authorize($this);
    }

    /**
     * @return BusinessUnit
     */
    public function businessUnit()
    {
        $this->setAdapterWithToken();
        return new BusinessUnit($this);
    }

    /**
     * @return Categories
     */
    public function categories()
    {
        $this->setAdapterWithApikey();
        return new Categories($this);
    }

    /**
     * @return Consumer
     */
    public function consumer()
    {
        $this->setAdapterWithApikey();
        return new Consumer($this);
    }

    /**
     * @return Resources
     */
    public function resources()
    {
        $this->setAdapterWithApikey();
        return new Resources($this);
    }

    /**
     * @return Invitation API
     */
    public function invitation()
    {
        $this->endpoint = 'https://invitations-api.trustpilot.com/v1/';
        $this->setAdapterWithToken();
        return new Invitation($this);
    }

    /**
     * @return ProductReviews
     */
    public function productReviews()
    {
        $this->setAdapterWithToken();
        return new ProductReviews($this);
    }

    /**
     * @return ServiceReviews
     */
    public function serviceReviews()
    {
        $this->setAdapterWithToken();
        return new ServiceReviews($this);
    }

}