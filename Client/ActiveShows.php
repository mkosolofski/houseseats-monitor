<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Client;

use MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows as ParserActiveShows,
    Doctrine\ORM\EntityManager;

/**
 * Client for getting active shows.
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class ActiveShows extends LoggedInRequestAbstract
{
    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows
     */
    private $pageParser;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
	private $entityManager;

    /**
     * Instantiate object
     *
     * @param \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface $config
     * @param \GuzzleHttp\Client $guzzleClient
     * @param \MKosolofski\HouseSeats\MonitorBundle\Client\Login $loginClient
     * @param \MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows $pageParser
     * @param \Doctrine\ORM\EntityManager $EntityManager
     */
    public function __construct(
        Config\ConfigInterface $config,
        \GuzzleHttp\Client     $guzzleClient,
        Login                  $loginClient,
        ParserActiveShows      $pageParser,
		EntityManager          $entityManager
    ) {
        parent::__construct($config, $guzzleClient, $loginClient);

		$this->pageParser    = $pageParser;
		$this->entityManager = $entityManager;
    }

    /**
     * Returns a list of active shows.
     *
     * @return \Generator
     */
    public function getShows(): \Generator
    {
        $storedShowIds = $this->getStoredShowIds();
        $this->deleteStoredShows();
        
        foreach($this->getActiveShows() as $activeShow) {
            $activeShow->setNew(array_search($activeShow->getId(), $storedShowIds) === false);
            $this->entityManager->persist($activeShow);
            yield $activeShow;
        }

        if (isset($activeShow)) $this->entityManager->flush();
    }
    
    /**
     * Deletes stored shows.
     */
    private function deleteStoredShows()
    {
        $this->entityManager
            ->createQueryBuilder()
            ->delete('MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns an array of stored show ids.
     *
     * @return array
     */
    private function getStoredShowIds()
    {
        return array_column(
            $this->entityManager
                ->createQueryBuilder()
                ->select('ash.id')
                ->from(
                    'MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow',
                    'ash'
                )
                ->getQuery()
                ->getResult() ?? [],
            'id'
        );
    }

    /**
     * Returns an array of new active shows.
     * 
     * @return \Generator
     */
    private function getActiveShows(): \Generator
    {
        $response = $this->performRequest(
            new \GuzzleHttp\Psr7\Request(
                'GET',
                'https://' . $this->config->getDomain() . $this->config->getPageActiveShows()
            )
        );

        $response = json_decode($response, true);
        if (!isset($response['eventInfo'])) return;

        yield from $this->pageParser->getActiveShows($response['eventInfo']);
    }
}
