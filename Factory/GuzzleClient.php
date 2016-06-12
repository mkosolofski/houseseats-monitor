<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Factory\GuzzleClient
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Factory;

use MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface;

/**
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class GuzzleClient
{
    /**
     * Returns a guzzle client instance.
     * 
     * @return \GuzzleHttp\Client
     */
    public static function getClient(ConfigInterface $config): \GuzzleHttp\Client
    {
        return new \GuzzleHttp\Client(
            [
                'cookies' => new \GuzzleHttp\Cookie\FileCookieJar($config->getCookieFile(), true)
            ]
        );
    }
}
