<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Command\CheckForNewShows
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Command;


use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    MKosolofski\HouseSeats\MonitorBundle\Client\Mailer as MailerClient,
    MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows as ShowsClient;

/**
 * Checks the active shows page and sends an email of new shows.
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class CheckForNewShows extends Command
{
    const COMMAND_NAME = 'mkosolofski:houseseats:monitor:check-for-new-shows';

    /**
     * @var MKosolofski\HouseSeats\MonitorBundle\Client\Mailer
     */
    private $mailerClient;
    
    /**
     * @var MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows
     */
    private $showsClient;

    /**
     * Instantiate command.
     *
     * @param MKosolofski\HouseSeats\MonitorBundle\Client\ActiveShows $showsClient
     * @param MKosolofski\HouseSeats\MonitorBundle\Client\Mailer       $mailerClient
     */
	public function __construct(ShowsClient $showsClient, MailerClient $mailerClient)
    {
        parent::__construct(self::COMMAND_NAME);
		$this->showsClient  = $showsClient;
		$this->mailerClient = $mailerClient;
	}

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription('Checks the active shows page and sends an email of new shows.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $totalNewShows = 0;
        foreach($this->showsClient->getShows() as $show) {
            $shows[] = $show;
            if ($show->isNew()) $totalNewShows++;
        }

        if ($totalNewShows > 0) $this->mailerClient->send($shows, $totalNewShows);
    }
}
