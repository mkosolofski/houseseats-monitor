<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Test\Command\CheckForNewShowsTest
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Test\Command;

use Phake,
    MKosolofski\HouseSeats\MonitorBundle\Command\CheckForNewShows,
    MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow;

/**
 * Exercise the check for new shows command.
 *
 * @coversDefaultClass MKosolofski\HouseSeats\MonitorBundle\Command\CheckForNewShows
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class CheckForNewShowsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @Mock
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows
     */
    private $showsClient;
    
    /**
     * @Mock
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\Mailer
     */
    private $mailerClient;
    
    /**
     * @Mock
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $inputInterface;
    
    /**
     * @Mock
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $outputInterface;
    
    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Command\CheckForNewShows
     */
    private $command;
    
    /**
     * {@inheritdoc}
     */
    public function setup()
    {
        Phake::initAnnotations($this);

        $this->command = new CheckForNewShows(
            $this->showsClient,
            $this->mailerClient
        );
    }

    /**
     * Test when HouseSeats has no active shows.
     *
     * @covers ::execute
     */
    public function testNoActiveShows()
    {
        Phake::when($this->showsClient)->getShows()->thenReturn($this->showsGenerator([]));
        
        $this->command->run($this->inputInterface, $this->outputInterface);

        Phake::verify($this->mailerClient, Phake::never())->send(Phake::anyParameters());
    }
    
    /**
     * Test when HouseSeats has active shows but are all old.
     *
     * @covers ::execute
     */
    public function testOldActiveShows()
    {
        Phake::when($this->showsClient)
            ->getShows()
            ->thenReturn(
                $this->showsGenerator(
                    [
                        (new ActiveShow)->setNew(false),
                        (new ActiveShow)->setNew(false),
                        (new ActiveShow)->setNew(false)
                    ]
                )
            );
        
        $this->command->run($this->inputInterface, $this->outputInterface);

        Phake::verify($this->mailerClient, Phake::never())->send(Phake::anyParameters());
    }
    
    /**
     * Test when HouseSeats has new active shows.
     *
     * @covers ::execute
     */
    public function testNewActiveShows()
    {
        $shows = [
            (new ActiveShow)->setNew(false),
            (new ActiveShow)->setNew(true),
            (new ActiveShow)->setNew(false),
            (new ActiveShow)->setNew(false),
            (new ActiveShow)->setNew(true)
        ];

        Phake::when($this->showsClient)
            ->getShows()
            ->thenReturn($this->showsGenerator($shows));
        
        $this->command->run($this->inputInterface, $this->outputInterface);

        Phake::verify($this->mailerClient)->send($shows, 2);
    }

    /**
     * Returns a shows generator.
     *
     * @return \Generator
     */
    private function showsGenerator(array $shows): \Generator
    {
        foreach($shows as $show) yield $show;
    }
}
