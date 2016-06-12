<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\PageParser\ActiveShows
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 */

namespace MKosolofski\HouseSeats\MonitorBundle\PageParser;

use MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow,
    MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface;

/**
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class ActiveShows
{
    /**
     * @var MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface
     */
    protected $config;

    /**
     * Instantiate object
     *
     * @param \MKosolofski\HouseSeats\MonitorBundle\Client\Config\ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Returns an array of active shows for the given page source.
     *
     * @param string $pageSource
     *
     * @return \Generator
     */
    public function getActiveShows(string $pageSource): \Generator
    { 
        $domDocument = new \DOMDocument;
        if (!(@$domDocument->loadHTML($pageSource))) return;
        
        $domXPath = new \DOMXpath($domDocument);

        foreach($this->getShows($domXPath) as $shows) {
            
            if (!($showDetails = @$domXPath->query('div', $shows))) continue;
            $activeShow  = new ActiveShow;
            
            yield $activeShow->setId($this->getShowId($showDetails, $domXPath))
				->setTitle($this->getShowTitle($showDetails, $domXPath))
				->setLocation($this->getShowLocation($showDetails, $domXPath))
				->setDescription($this->getShowDescription($showDetails, $domXPath))
				->setNextShow($this->getNextShowTime($showDetails, $domXPath))
				->setImageUrl($this->getShowImage($showDetails, $domXPath));
        }
    }

    /**
     * Returns a list of shows.
     *
     * @return \DOMNodeList
     */
    private function getShows(\DOMXpath $domXPath): \DOMNodeList
    {
        $containers = @$domXPath->query("//div[@class='list-view-info']/div[@class='well']/div[@class='row']");
        return $containers ?: new \DOMNodeList; 
    }        

    /**
     * Returns the show id.
     *
     * @param \DOMNodeList $showDetails
     * @param \DOMXpath    $domXPath
     *
     * @return string
     */
    private function getShowId(\DOMNodeList $showDetails, \DOMXpath $domXPath): string
    {
        return preg_replace(
            '/.*showid\=/',
            '', 
            @$domXPath->query('a', @$showDetails->item(0))->item(0)->getAttribute('href')
        );
    }    

    /**
     * Returns the show title.
     *
     * @param \DOMNodeList $showDetails
     * @param \DOMXpath    $domXPath
     *
     * @return string
     */
    private function getShowTitle(\DOMNodeList $showDetails, \DOMXpath $domXPath): string
    {
        return (string)@$domXPath->query('h1/a', @$showDetails->item(1))->item(0)->nodeValue;
    }        

    /**
     * Returns the show location.
     *
     * @param \DOMNodeList $showDetails
     * @param \DOMXpath    $domXPath
     *
     * @return string
     */
    private function getShowLocation(\DOMNodeList $showDetails, \DOMXpath $domXPath): string
    {
        return (string)@$domXPath->query('h3', @$showDetails->item(1))->item(0)->nodeValue;
    }        

    /**
     * Returns the show description.
     *
     * @param \DOMNodeList $showDetails
     * @param \DOMXpath    $domXPath
     *
     * @return string
     */
    private function getShowDescription(\DOMNodeList $showDetails, \DOMXpath $domXPath): string
    {
        return str_replace(
            '[...]',
            '....',
            @$domXPath->query('p[last()]', @$showDetails->item(1))->item(0)->nodeValue
        );
    }        

    /**
     * Returns the next show time.
     *
     * @param \DOMNodeList $showDetails
     * @param \DOMXpath    $domXPath
     *
     * @return string
     */
    private function getNextShowTime(\DOMNodeList $showDetails, \DOMXpath $domXPath): string
    {
        return trim(@$domXPath->query('div/div/div[2]', @$showDetails->item(2))->item(0)->nodeValue) . ' ' .
            trim(@$domXPath->query('div/div/div[3]', @$showDetails->item(2))->item(0)->nodeValue);
    }        

    /**
     * Returns the show image url.
     *
     * @param \DOMNodeList $showDetails
     * @param \DOMXpath    $domXPath
     *
     * @return string
     */
    private function getShowImage(\DOMNodeList $showDetails, \DOMXpath $domXPath): string
    {
        return $this->config->getDomain() .
            (string)@$domXPath->query('a/img', @$showDetails->item(0))->item(0)->getAttribute('src');
    }        
}    
