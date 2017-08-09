<?php

/*
 * This file is part of the TrustPilot library.
 *
 * (c) Graphem Solutions <info@graphem.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TrustPilot\Api;

/**
 * @author Graphem Solutions <info@graphem.ca>
 */
use TrustPilot\TrustPilot;
use TrustPilot\Adapter\AdapterInterface;

abstract class AbstractApi
{
    
    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $return;

    /**
     * @var AdapterInterface
     */
    protected $api;

    /**
     * @var string
     */
    protected $client;

    /**
     * AbstractApi constructor.
     *
     * @param TrustPilot $client
     */
    public function __construct(TrustPilot $client)
    {
        $this->client = $client;
        $this->api = $client->getClient();
    }

}