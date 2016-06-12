<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Client\RequestAbstract
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Client;

use GuzzleHttp\Exception\TransferException,
    Psr\Http\Message\RequestInterface;

/**
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
abstract class RequestAbstract
{
    /**
     * @var MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface
     */
    protected $config;

    /**
     * @var GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * Instantiate object
     *
     * @param \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface $config
     * @param \GuzzleHttp\Client $guzzleClient
     */
    public function __construct(
        Config\ConfigInterface $config,
        \GuzzleHttp\Client     $guzzleClient
    ) {
        $this->config       = $config;
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * Performs a request.
     *
     * @param  \Psr\Http\Message\RequestInterface $request
     * @return string The response body.
     */ 
    protected function performRequest(RequestInterface $request): string
    {
        try {
            $response = $this->guzzleClient->send(
                $request,
                [
                    \GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 5,
                    \GuzzleHttp\RequestOptions::TIMEOUT => 5
                ]
            );
        } catch (TransferException $e) {
            return '';
        }
        
        $responseBody       = (string)$response->getBody();
        $responseStatusCode = $response->getStatusCode();
        
        return ($responseStatusCode > 199 && $responseStatusCode < 400) ? $responseBody : '';
    }
}
