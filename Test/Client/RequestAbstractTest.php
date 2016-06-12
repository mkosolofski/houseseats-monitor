<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\RequestAbstractTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\Client;

use Phake,
    GuzzleHttp\Exception\TransferException;

/**
 * Exercise the request abstract object.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\Client\RequestAbstract
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class RequestAbstractTest extends \PHPUnit_Framework_TestCase
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
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;
    
    /**
     * @Mock
     * @var \Psr\Http\Message\RequestInterface
     */
    private $request;
    
    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Test\Client\RequestStub
     */
    private $requestStub;

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
        Phake::initAnnotations($this);

        $this->requestStub = new RequestStub($this->config, $this->guzzleClient);
        
        Phake::when($this->response)->getBody()
            ->thenReturn('<html><body>Body contents</body></html>');

        Phake::when($this->guzzleClient)
            ->send(
                $this->request,
                [
                    \GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 5,
                    \GuzzleHttp\RequestOptions::TIMEOUT => 5
                ]
            )
            ->thenReturn($this->response);
    }

    /**
     * Test when a guzzle transfer exception is thrown.
     *
     * @covers ::performRequest
     */
    public function testTransferException()
    {
        Phake::when($this->guzzleClient)
            ->send(Phake::anyParameters())
            ->thenThrow(new TransferException);

        $this->assertEquals('', $this->requestStub->performRequestWrapper($this->request));
    }

    /**
     * Test when the response status code is less than 200.
     *
     * @covers ::performRequest
     */
    public function testResponseStatusCodeLessThan200()
    {
        Phake::when($this->response)->getStatusCode()->thenReturn(199);
        $this->assertEquals('', $this->requestStub->performRequestWrapper($this->request));
    }
    
    /**
     * Test success response status code.
     *
     * @covers ::performRequest
     */
    public function testResponseStatusCode200()
    {
        Phake::when($this->response)->getStatusCode()->thenReturn(200);
        $this->assertEquals($this->response->getBody(), $this->requestStub->performRequestWrapper($this->request));
    }
    
    /**
     * Test redirect response status code.
     *
     * @covers ::performRequest
     */
    public function testResponseStatusCode300()
    {
        Phake::when($this->response)->getStatusCode()->thenReturn(300);
        $this->assertEquals($this->response->getBody(), $this->requestStub->performRequestWrapper($this->request));
    }

    /**
     * Test when the response status code is greater than 399.
     *
     * @covers ::performRequest
     */
    public function testResponseStatusCodeGreaterThan399()
    {
        Phake::when($this->response)->getStatusCode()->thenReturn(400);
        $this->assertEquals('', $this->requestStub->performRequestWrapper($this->request));
    }
}
