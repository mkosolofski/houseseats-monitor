<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\LoginTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\PageParser;

use MKosolofski\HouseSeats\MonitorBundle\PageParser\Login;

/**
 * Exercise the login page parser.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\PageParser\Login
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class LoginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test not logged in.
     *
     * @covers ::isLoggedIn
     */
    public function testNotLoggedIn()
    {
        $this->assertFalse((new Login)->isLoggedIn('<input type="password"/>'));
    }
    
    /**
     * Test logged in.
     *
     * @covers ::isLoggedIn
     */
    public function testLoggedIn()
    {
        $this->assertTrue((new Login)->isLoggedIn('No password input'));
    }
}
