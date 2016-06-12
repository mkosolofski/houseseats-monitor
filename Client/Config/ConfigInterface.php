<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Client\Config;

/**
 * Implementation for a client config.
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */
interface ConfigInterface
{   
    /**
     * Returns the HouseSeats login email.
     *
     * @return string
     */
    public function getLoginEmail(): string;

    /**
     * Returns the HouseSeats login password.
     *
     * @return string
     */
    public function getLoginPassword(): string;

    /**
     * Returns the HouseSeats login page.
     *
     * @return string
     */
    public function getPageLogin(): string;

    /**
     * Returns the HouseSeats domain.
     *
     * @return string
     */   
    public function getDomain(): string;

    /**
     * Returns the HouseSeats active shows page.
     *
     * @return string
     */
    public function getPageActiveShows(): string;

    /**
     * Returns max login attempts.
     *
     * @return integer
     */
    public function getLoginMaxAttempts(): int;

    /**
     * Returns where the login cookie is stored.
     *
     * @eturn string
     */
    public function getCookieFile(): string;

    /**
     * Returns an admin email.
     *
     * @return string
     */
    public function getAdminEmail(): string;
   
    /**
     * Returns a list of email addresses to send HouseSeats updates to.
     *
     * @return array
     */
    public function getNotifyEmails(): array;
}    
