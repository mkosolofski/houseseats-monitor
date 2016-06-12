<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\PageParser\Login
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\PageParser;

/**
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class Login
{
    /**
     * Returns if the use is logged in based on the given page source.
     *
     * @param string $pageSource
     * 
     * @return bool True if logged in, false otherwise.
     */
    public function isLoggedIn(string $pageSource): bool
    {  
        preg_match('/type="password"/', $pageSource, $matches);
        return empty($matches);
    }
}    
