<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\RequestStub
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\Client;

use Psr\Http\Message\RequestInterface,
    MKosolofski\HouseSeats\MonitorBundle\Client\RequestAbstract;

/**
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class RequestStub extends RequestAbstract
{
    /**
     * Wrapper for performRequest method.
     *
     * @param  \Psr\Http\Message\RequestInterface $request
     * @return string The response body.
     */ 
    public function performRequestWrapper($request)
    {
        return parent::performRequest($request);
    }
}
