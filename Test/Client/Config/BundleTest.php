<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\Config\BundleTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\Client\Config;

use MKosolofski\HouseSeats\MonitorBundle\Client\Config\Bundle as BundleConfig;

/**
 * Exercise the Bundle config.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\Client\Config\Bundle
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class BundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * A valid configuration.
     */
    const VALID_CONFIG = [
        'admin_email' => 'matthew.kosolofski@gmail.com',
        'cookie_file' => '/tmp/houseseats_monitor_cookie',
        'domain' => 'lv.houseseats.com',
        'login' => [
            'email' => 'mylogin@test.com',
            'password' => 'mypassword',
            'max_attempts' => 1
        ],
        'page' => [
            'login' => '/login',
            'active_shows' => '/active_shows'
        ],
        'notify' => [
            'emails' => [ 'notify@test.com' ]
        ]
    ];

    /**
     * Test a valid configuration.
     *
     * @covers ::<public>
     */
    public function testValidConfiguration()
    {
        $config = new BundleConfig(self::VALID_CONFIG);
        
        $this->assertEquals(self::VALID_CONFIG['admin_email'], $config->getAdminEmail());
        $this->assertEquals(self::VALID_CONFIG['cookie_file'], $config->getCookieFile());
        $this->assertEquals(self::VALID_CONFIG['domain'], $config->getDomain());
        $this->assertEquals(self::VALID_CONFIG['login']['email'], $config->getLoginEmail());
        $this->assertEquals(self::VALID_CONFIG['login']['password'], $config->getLoginPassword());
        $this->assertEquals(self::VALID_CONFIG['login']['max_attempts'], $config->getLoginMaxAttempts());
        $this->assertEquals(self::VALID_CONFIG['page']['login'], $config->getPageLogin());
        $this->assertEquals(self::VALID_CONFIG['page']['active_shows'], $config->getPageActiveShows());
        $this->assertEquals(self::VALID_CONFIG['notify']['emails'], $config->getNotifyEmails());
    }    
    
    /**
     * Test when configurations are missing.
     *
     * @covers ::<public>
     */
    public function testMissingConfigurations()
    {
        $config = new BundleConfig([]);
        
        $this->assertEquals('', $config->getAdminEmail());
        $this->assertEquals('', $config->getCookieFile());
        $this->assertEquals('', $config->getDomain());
        $this->assertEquals('', $config->getLoginEmail());
        $this->assertEquals('', $config->getLoginPassword());
        $this->assertEquals(1, $config->getLoginMaxAttempts());
        $this->assertEquals('', $config->getPageLogin());
        $this->assertEquals('', $config->getPageActiveShows());
        $this->assertEquals([], $config->getNotifyEmails());
    }    
}
