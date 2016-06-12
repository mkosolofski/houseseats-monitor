<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\LoggedInRequestAbstractTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\Client;

use Phake,
    MKosolofski\HouseSeats\MonitorBundle\Client\Exception\MaxLoginAttempts;

/**
 * Exercise the logged in request abstract object.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\Client\LoggedInRequestAbstract
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class LoggedInRequestAbstractTest extends \PHPUnit_Framework_TestCase
{
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
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\Login
     */
    private $loginClient;
    
    /**
     * @Mock
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;
    
    /**
     * @Mock
     * @var \Psr\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Test\Client\loggedInRequestStub
     */
    private $loggedInRequestStub;

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
        Phake::initAnnotations($this);

        $this->loggedInRequestStub = new LoggedInRequestStub(
            $this->config,
            $this->guzzleClient,
            $this->loginClient
        );

        Phake::when($this->response)->getStatusCode()->thenReturn(200);
        Phake::when($this->response)->getBody()
            ->thenReturn('<html><body>Already logged in</body></html>');

        Phake::when($this->guzzleClient)->send(Phake::anyParameters())
            ->thenReturn($this->response);
    }

    /**
     * Test when an empty response is returned by the request.
     *
     * @covers ::performRequest
     */
    public function testEmptyResponse()
    {
        Phake::when($this->response)->getBody()->thenReturn('');
        $this->assertEquals('', $this->loggedInRequestStub->performRequestWrapper($this->request));
    }

    /**
     * Test performing a request when the client is already logged in.
     *
     * @covers ::performRequest
     */
    public function testLoggedIn()
    {
        $responseBody = $this->response->getBody();
        
        Phake::when($this->response)->getBody()->thenReturn($responseBody);
        Phake::when($this->loginClient)->isLoggedIn($responseBody)->thenReturn(true);

        $this->assertEquals($responseBody, $this->loggedInRequestStub->performRequestWrapper($this->request));
    }

    /**
     * Test performing a request when the max login attempts is reached.
     *
     * @covers ::performRequest
     */
    public function testMaxLoginAttempts()
    {
        $responseBody = $this->response->getBody();
        
        Phake::when($this->response)->getBody()->thenReturn($responseBody);
        Phake::when($this->loginClient)->isLoggedIn($responseBody)->thenReturn(false);
        Phake::when($this->loginClient)->logIn()->thenThrow(new MaxLoginAttempts);

        $this->assertEquals('', $this->loggedInRequestStub->performRequestWrapper($this->request));
    }    

    /**
     * Test performing a request when the client is not logged in.
     *
     * @covers ::performRequest
     */
    public function testNotLoggedIn()
    {
        $responseBody = $this->response->getBody();
        
        Phake::when($this->response)->getBody()->thenReturn($responseBody);
        Phake::when($this->loginClient)->isLoggedIn($responseBody)
            ->thenReturn(false)
            ->thenReturn(false)
            ->thenReturn(true);

        $this->assertEquals($responseBody, $this->loggedInRequestStub->performRequestWrapper($this->request));
        
        Phake::verify($this->loginClient, Phake::times(3))->isLoggedIn($responseBody);
        Phake::verify($this->loginClient, Phake::times(2))->logIn(Phake::anyParameters());
    }
}
