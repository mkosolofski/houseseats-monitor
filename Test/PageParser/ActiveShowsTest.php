<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\ActiveShowsTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\PageParser;

use Phake,
    MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows;

/**
 * Exercise the login client.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class ActiveShowsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @Mock
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface
     */
    private $config;

    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
        Phake::initAnnotations($this);

        Phake::when($this->config)->getDomain()->thenReturn('lv.houseseats.com');

        $this->parser = new ActiveShows($this->config);
    }

    /**
     * Data provider of invalid page source.
     *
     * @return array The data to test against.
     */
    public static function  dataProviderInvalidPageSource(): array
    {
        return [
            'Empty' => [''],
            'Invalid HTML' => ['<html><body></html>'],
            'Not HTML' => ['This is not HTML']
        ];
    }

    /**
     * Test invalid page source.
     *
     * @covers ::getActiveShows
     * @dataProvider dataProviderInvalidPageSource
     */
    public function testInvalidPageSource($pageSource)
    {
        $this->assertEmpty(iterator_to_array($this->parser->getActiveShows($pageSource)));
    }

    /**
     * Test parsing source code that has active shows.
     *
     * @covers ::getActiveShows
     */
    public function testActiveShowsFound()
    {
        $this->assertEquals(
            unserialize(file_get_contents(__DIR__ . '/parsed_active_shows.txt')),
            iterator_to_array($this->parser->getActiveShows(file_get_contents(__DIR__ . '/source_active_shows.txt')))
        );
    }
}
