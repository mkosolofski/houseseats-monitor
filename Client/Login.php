<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Client\Login
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Client;

use MKosolofski\HouseSeats\MonitorBundle\PageParser\Login as LoginParser;

/**
 * Client for logging into HouseSeats.
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class Login extends RequestAbstract
{
    /**
     * @var int
     */
    private $loginAttempts = 0;

    /**
     * @var MKosolofski\HouseSeats\Monitor\PageParser\Login
     */
    private $pageParser;

    /**
     * Instantiate object
     *
     * @param \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface $config
     * @param \GuzzleHttp\Client $guzzleClient
     * @param \MKosolofski\HouseSeats\MonitorBundle\PageParser\Login $PageParser
     */
    public function __construct(
        Config\ConfigInterface $config,
        \GuzzleHttp\Client     $guzzleClient,
        LoginParser            $pageParser
    ) {
        parent::__construct($config, $guzzleClient);
        $this->pageParser = $pageParser;
    }

    /**
     * Logs into HouseSeats.
     *
     * @throws MKosolofski\HouseSeats\Monitor\Exception\MaxLoginAttempts Max login attempts hit.
     */
    public function logIn()
    {
        if ($this->loginAttempts >= $this->config->getLoginMaxAttempts()) {
            throw new Exception\MaxLoginAttempts('Login attemps hit!');
        }

        $this->loginAttempts ++;
        
        $responseBody = $this->performRequest(
            new \GuzzleHttp\Psr7\Request(
                'POST',
                'http://' . $this->config->getDomain() . $this->config->getPageLogin(),
                [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                http_build_query(
                    [
                        'submit' => 'login',
                        'email' => $this->config->getLoginEmail(),
                        'password' => $this->config->getLoginPassword(),
                        'lastplace' => ''
                    ]
               )
            )
        );

        if (!$this->isLoggedIn($responseBody)) {
            sleep(1);
            $this->login();
        }
    }

    /**
     * Returns if user is logged into HouseSeats base on this given page source code.
     *
     * @param string $pageSource The page source code.
     * @return bool True if logged in, false otherwise.
     */
    public function isLoggedIn(string $pageSource): bool
    {
        return $this->pageParser->isLoggedIn($pageSource);
    }
}
