<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\LoginTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\Client;

use Phake,
    MKosolofski\HouseSeats\MonitorBundle\Client\Exception\MaxLoginAttempts;

/**
 * Exercise the login client.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\Client\Login
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class LoginTest extends \PHPUnit_Framework_TestCase
{
    const MAX_LOGIN_ATTEMPTS = 3;
    const RESPONSE_BODY = '<html><body>request response</body></html>';

    /**
     * @Mock
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface
     */
    private $config;
    
    /**
     * @Mock
     * @var \GuzzleHttp\Client
     */
    private $guzzleClient;
    
    /**
     * @Mock
     * @var \MKosolofski\HouseSeats\MonitorBundle\PageParser\Login
     */
    private $pageParser;
    
    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\Login
     */
    private $loginClient;

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
        Phake::initAnnotations($this);
        
        Phake::when($this->config)->getLoginMaxAttempts()->thenReturn(self::MAX_LOGIN_ATTEMPTS);

        Phake::when($this->config)->getDomain()->thenReturn('lv.houseseats.com');

        $this->loginClient = Phake::partialMock(
            'MKosolofski\HouseSeats\MonitorBundle\Client\Login',
            $this->config,
            $this->guzzleClient,
            $this->pageParser
        );
        
        Phake::when($this->loginClient)
            ->performRequest(Phake::anyParameters())
            ->thenReturn(self::RESPONSE_BODY);
    }

    /**
     * Test when max login attempts hit.
     *
     * @covers ::login
     * @covers ::isLoggedIn
     */
    public function testMaxLoginAttemptsHit()
    {
        Phake::when($this->pageParser)->isLoggedIn(self::RESPONSE_BODY)->thenReturn(false);

        try {
            $this->loginClient->login();
            $this->fail('Expected max login attempts to be hit');
        } catch (MaxLoginAttempts $e) {
            // Expected!!
        }

        Phake::verify($this->pageParser, Phake::times(self::MAX_LOGIN_ATTEMPTS))->isLoggedIn(self::RESPONSE_BODY);
        
        Phake::verify($this->loginClient, Phake::times(self::MAX_LOGIN_ATTEMPTS))
            ->performRequest(Phake::capture($request));
        
        $this->assertInstanceOf('\GuzzleHttp\Psr7\Request', $request);
    }

    /**
     * Test successful login.
     *
     * @covers ::login
     * @covers ::isLoggedIn
     */
    public function testSuccessfulLogin()
    {
        Phake::when($this->pageParser)->isLoggedIn(self::RESPONSE_BODY)->thenReturn(true);
        
        $this->loginClient->login();

        Phake::verify($this->pageParser, Phake::times(1))->isLoggedIn(self::RESPONSE_BODY);
        Phake::verify($this->loginClient, Phake::times(1))->performRequest(Phake::capture($request));

        $this->assertInstanceOf('\GuzzleHttp\Psr7\Request', $request);
    }
}
