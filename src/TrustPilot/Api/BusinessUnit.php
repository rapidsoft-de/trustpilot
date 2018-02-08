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

class BusinessUnit extends AbstractApi{

    /**
     * Get a list of business units
     *
     * @param string $country
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function fetchAll($country = '', $page = 1, $perPage = 1000)
    {
        return json_decode(
            $this->api->get('business-units',
                ['query' => 
                    [
                       'country' => $country, 
                       'page' => $page, 
                       'perPage' => $perPage
                    ]
                ]
            ));
    }

    /**
     * Get a list of business units
     *
     * @param  
     * @return array
     */
    public function find($name)
    {
        return json_decode(
            $this->api->get('business-units/find',
                ['query' => 
                    [
                       'name' => $name
                    ]
                ]
            ));
    }

    /**
     * Get a business unit
     *
     * @param string $businessUnitId
     * @return mixed
     */
    public function get($businessUnitId)
    {
        return json_decode(
            $this->api->get('business-units/'.$businessUnitId));
    }

    /**
     * Get a business unit's web links
     *
     * @param string $businessUnitId
     * @param string $locale
     * @return mixed
     */
    public function getWebLink($businessUnitId, $locale = 'en-US')
    {
        return json_decode(
            $this->api->get('business-units/' . $businessUnitId . '/web-links',
                ['query' => 
                    [
                       'locale' => $locale
                    ]
                ]
            ));
    }

    /**
     * List categories for business unit
     *
     * @param string $businessUnitId
     * @param string $locale
     * @return mixed
     */
    public function listCategories($businessUnitId, $locale = '')
    {
        return json_decode(
            $this->api->get('business-units/' . $businessUnitId . '/categories',
                ['query' => 
                    [
                       'locale' => $locale
                    ]
                ]
            ));
    }

    /**
     * Search for business units
     *
     * @param string $query
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function search($query, $page = 1, $perPage = 20)
    {
        return json_decode(
            $this->api->get('business-units/search',
                ['query' => 
                    [
                       'query' => $query,
                       'page' => $page,
                       'perPage' => $perPage
                    ]
                ]
            ));
    }

    /**
     * Get a business unit's reviews
     *
     * @param string $businessUnitId
     * @param $data
     * @return mixed
     */
    public function getReviews($businessUnitId, $data)
    {
        return json_decode(
            $this->api->get('business-units/' . $businessUnitId . '/reviews',
                ['query' => 
                    [
                       'stars' => $data['stars'],
                       'language' => $data['language'],
                       'page' => $data['page'],
                       'perPage' => $data['perPage'],
                       'orderBy' => $data['orderBy'],
                       'tagGroup' => $data['tagGroup'],
                       'tagValue' => $data['tagValue'],
                       'responded' => $data['responded'],
                       'includeReportedReviews' => $data['includeReportedReviews']
                    ]
                ]
            ));
    }

    /**
     * Get a business unit's reviews
     *
     * @param string $businessUnitId
     * @param array $data
     * @return \stdClass
     */
    public function getPrivateReviews($businessUnitId, $data)
    {
        return json_decode(
            $this->api->get('private/business-units/' . $businessUnitId . '/reviews',
                ['query' => 
                    [
                        'stars' => $data['stars'],
                        'language' => $data['language'],
                        'page' => $data['page'],
                        'perPage' => $data['perPage'],
                        'orderBy' => $data['orderBy'],
                        'tagGroup' => $data['tagGroup'],
                        'tagValue' => $data['tagValue'],
                        'responded' => $data['responded'],
                        'referenceId' => $data['referenceId'],
                        'referralEmail' => $data['referralEmail'],
                        'reported' => $data['responded'],
                        'startDateTime' => $data['startDateTime'],
                        'endDateTime' => $data['endDateTime'],
                        'source' => $data['source'],
                        'username' => $data['username']
                    ]
                ]
            ));
    }

    /**
     * Get all business unit private tags
     *
     * @param string $businessUnitId
     * @return mixed
     */
    public function getTags($businessUnitId)
    {
        return json_decode(
            $this->api->get('business-units/' . $businessUnitId . '/tags'));
    }

    /**
     * Get a business unit's images
     *
     * @param string
     * @return array
     */
    public function getImages($businessUnitId)
    {
        return json_decode($this->api->get('business-units/' . $businessUnitId . '/images'));
    }

}
