<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Client\ActiveShowsTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\Client;

use Phake,
    MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow,
    GuzzleHttp\Psr7\Request;

/**
 * Exercise the active shows client.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class ActiveShowsTest extends \PHPUnit_Framework_TestCase
{
    const NEW_SHOW_ID = 1;
    const OLD_SHOW_ID = 2;

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
     * @var \MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows
     */
    private $pageParser;
    
    /**
     * @Mock
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * @Mock
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;
    
    /**
     * @Mock
     * @var \Doctrine\ORM\AbstractQuery
     */
    private $query;

    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows
     */
    private $activeShowsClient;

    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
     */
    private $newShow;
    
    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
     */
    private $oldShow;

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
        $this->newShow = (new ActiveShow)->setId(self::NEW_SHOW_ID);
        $this->oldShow = (new ActiveShow)->setId(self::OLD_SHOW_ID);
        $this->guzzleResponse = ['eventInfo' => 'page source'];

        Phake::initAnnotations($this);

        Phake::when($this->entityManager)
            ->createQueryBuilder()
               ->thenReturn($this->queryBuilder);
        
        Phake::when($this->queryBuilder)
            ->delete('MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow')
            ->thenReturn($this->queryBuilder);

        Phake::when($this->queryBuilder)
            ->select('ash.id')
            ->thenReturn($this->queryBuilder);

        Phake::when($this->queryBuilder)
            ->from('MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow', 'ash')
            ->thenReturn($this->queryBuilder);
        
        Phake::when($this->queryBuilder)
            ->getQuery()
            ->thenReturn($this->query);
        
        Phake::when($this->query)
            ->getResult()
            ->thenReturn([['id' => self::OLD_SHOW_ID]]);

        Phake::when($this->pageParser)
            ->getActiveShows($this->guzzleResponse['eventInfo'])
            ->thenReturn($this->pageParserGenerator([$this->newShow, $this->oldShow]));

        Phake::when($this->config)
            ->getDomain()
            ->thenReturn('lv.houseseats.com');
        
        Phake::when($this->config)
            ->getPageActiveShows()
            ->thenReturn('/active-shows');
        
        $this->activeShowsClient = Phake::partialMock(
            'MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows',
            $this->config,
            $this->guzzleClient,
            $this->loginClient,
            $this->pageParser,
	        $this->entityManager
        );
        
        Phake::when($this->activeShowsClient)
            ->performRequest($this->getGuzzleRequest())
            ->thenReturn(json_encode($this->guzzleResponse));
    }

    /**
     * Test when there are no active shows.
     *
     * @covers ::getShows
     * @covers ::<private>
     */
    public function testGetShowsNoShows()
    {
        Phake::when($this->pageParser)
            ->getActiveShows($this->guzzleResponse['eventInfo'])
            ->thenReturn($this->pageParserGenerator([]));
        
        $this->assertEmpty(iterator_to_array($this->activeShowsClient->getShows()));

        Phake::verify($this->queryBuilder)->delete('MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow');
        Phake::verify($this->entityManager, Phake::never())->persist(Phake::anyParameters());
        Phake::verify($this->entityManager, Phake::never())->flush(Phake::anyParameters());
    }
    
    /**
     * Test when there are active shows.
     *
     * @covers ::getShows
     * @covers ::<private>
     */
    public function testGetShows()
    {
        $shows = iterator_to_array($this->activeShowsClient->getShows());
        
        $this->assertTrue($shows[0]->isNew());
        $this->assertEquals(self::NEW_SHOW_ID, $shows[0]->getId());
        
        $this->assertFalse($shows[1]->isNew());
        $this->assertEquals(self::OLD_SHOW_ID, $shows[1]->getId());

        Phake::verify($this->queryBuilder)->delete(Phake::anyParameters());
        Phake::verify($this->entityManager)->persist($this->oldShow);
        Phake::verify($this->entityManager)->persist($this->newShow);
        Phake::verify($this->entityManager)->flush();
    }
    
    /**
     * Test when the request to HouseSeats returns an empty response.
     *
     * @covers ::getActiveShows
     */
    public function testResponseEmpty()
    {
        Phake::when($this->activeShowsClient)
            ->performRequest(
                new \GuzzleHttp\Psr7\Request(
                    'GET',
                    'https://' . $this->config->getDomain() . $this->config->getPageActiveShows()
                )
            )
            ->thenReturn('');

        $this->assertEmpty(iterator_to_array($this->activeShowsClient->getShows()));

        Phake::verify($this->queryBuilder)->delete(Phake::anyParameters());
        Phake::verify($this->entityManager, Phake::never())->persist(Phake::anyParameters());
        Phake::verify($this->entityManager, Phake::never())->flush(Phake::anyParameters());
    }
    
    /**
     * Test when the request to HouseSeats returns a response missing "eventInfo".
     *
     * @covers ::getActiveShows
     */
    public function testResponseMissingEventInfo()
    {
        Phake::when($this->activeShowsClient)
            ->performRequest($this->getGuzzleRequest())
            ->thenReturn(json_encode(['missing_event_info' => 'value']));

        $this->assertEmpty(iterator_to_array($this->activeShowsClient->getShows()));

        Phake::verify($this->queryBuilder)->delete(Phake::anyParameters());
        Phake::verify($this->entityManager, Phake::never())->persist(Phake::anyParameters());
        Phake::verify($this->entityManager, Phake::never())->flush(Phake::anyParameters());
    }

    /**
     * Returns a pager parser generator.
     *
     * @return \Generatot
     */
    private function pageParserGenerator(array $shows): \Generator
    {
        foreach($shows as $show) yield $show;
    }

    /**
     * Returns a new guzzle request.
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    private function getGuzzleRequest(): Request
    {
        return new Request(
            'GET',
            'https://' . $this->config->getDomain() . $this->config->getPageActiveShows()
        );
    }
}
