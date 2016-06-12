<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Client\Config\Bundle
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Client\Config;

/**
 * Config implementation for bootstrapping this application as a bundle.
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class Bundle implements ConfigInterface
{
    /**
     * The configuration of this bundle.
     *
     * @var array
     */
    private $bundleConfig;

    /**
     * Instantiates object.
     *
     * @param array $bundleConfig
     */
    public function __construct(array $bundleConfig)
    {
        $this->bundleConfig = $bundleConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginEmail(): string
    {
        return $this->bundleConfig['login']['email'] ?? '';
    }    

    /**
     * {@inheritdoc}
     */
    public function getLoginPassword(): string
    {
        return $this->bundleConfig['login']['password'] ?? '';
    }    

    /**
     * {@inheritdoc}
     */
    public function getPageLogin(): string
    {
        return $this->bundleConfig['page']['login'] ?? '';
    }    

    /**
     * {@inheritdoc}
     */
    public function getDomain(): string
    {
        return $this->bundleConfig['domain'] ?? '';
    }    

    /**
     * {@inheritdoc}
     */
    public function getPageActiveShows(): string
    {
        return $this->bundleConfig['page']['active_shows'] ?? '';
    }    

    /**
     * {@inheritdoc}
     */
    public function getLoginMaxAttempts(): int
    {
        return $this->bundleConfig['login']['max_attempts'] ?? 1;
    }    

    /**
     * {@inheritdoc}
     */
    public function getCookieFile(): string
    {
        return $this->bundleConfig['cookie_file'] ?? '';
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getAdminEmail(): string
    {
        return $this->bundleConfig['admin_email'] ?? '';
    }    

    /**
     * {@inheritdoc}
     */
    public function getNotifyEmails(): array
    {
        return $this->bundleConfig['notify']['emails'] ?? [];
    }    
}    
