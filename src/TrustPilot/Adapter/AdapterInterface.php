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
interface AdapterInterface
{
    /**
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function get($url, $options = []);

    /**
     * @param string $url
     * @return mixed
     */
    public function delete($url);

    /**
     * @param string $url
     * @param string $content
     * @return mixed
     */
    public function put($url, $content = '');
    
    /**
     * @param string $url
     * @param string $content
     * @return mixed
     */
    public function post($url, $content = '');

    /**
     * @return string[]|null
     */
    public function getLatestResponseHeaders();
}