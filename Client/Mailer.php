<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Client\Mailer
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Client;

use MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface;

/**
 * Client for sending emails about new shows.
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class Mailer
{
    /**
     * @var \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface
     */
    private $config;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Instantiate object,
     *
     * @param MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface $config
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(
        ConfigInterface   $config,
        \Swift_Mailer     $mailer,
        \Twig_Environment $twig
    ) {
        $this->config = $config;
        $this->mailer = $mailer;
        $this->twig   = $twig;
    }

    /**
     * Sends notifications for the given shows.
     *
     * @param array $shows
     * @param int   $totalNewShows
     */
    public function send(array $shows, int $totalNewShows)
    {
        $this->mailer->send(
            \Swift_Message::newInstance()
                ->setFrom($this->config->getAdminEmail(), 'Cron - House Seats!' )
                ->setTo($this->config->getNotifyEmails())
                ->setSubject('New Shows Posted (' . $totalNewShows . ')')
                ->setBody(
                    $this->twig->render(
                        '@MKosolofskiHouseSeatsMonitor/email/shows.html.twig',
                        array('shows' => $shows, 'config' => $this->config)
                    ),
                    'text/html'
                )
        );
    }
}
