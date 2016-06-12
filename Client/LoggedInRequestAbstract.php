<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Client\LoggedInRequestAbstract
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Client;

use Psr\Http\Message\RequestInterface;

/**
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
abstract class LoggedInRequestAbstract extends RequestAbstract
{
    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\Login
     */
    protected $loginClient;

    /**
     * Instantiate object
     *
     * @param \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface $config
     * @param \GuzzleHttp\Client $guzzleClient
     * @param \MKosolofski\HouseSeats\MonitorBundle\Client\Login $loginClient
     */
    public function __construct(
        Config\ConfigInterface $config,
        \GuzzleHttp\Client     $guzzleClient,
        Login                  $loginClient
    ) {
        parent::__construct($config, $guzzleClient);
        $this->loginClient = $loginClient;
    }

    /**
     * {@inheritdoc}
     */
    protected function performRequest(RequestInterface $request): string
    {
        if (!($responseBody = parent::performRequest($request))) return '';

        if ($this->loginClient->isLoggedIn($responseBody)) return $responseBody;
        
        try {
            $this->loginClient->logIn();
        } catch (Exception\MaxLoginAttempts $e) {
            // Return nothing, max login attempts reached.
            return '';
        }

        return $this->performRequest($request);
    }
}
