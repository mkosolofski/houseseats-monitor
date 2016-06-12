<?php
/**
 * Contains MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle\Entity
 */

namespace MKosolofski\HouseSeats\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="active_show")
 *
 * @package MKosolofski
 * @subpackage HouseSeats\MonitorBundle\Entity
 * @author Matthew Kosolofski <matthew.Kosolofski@gmail.com>
 */
class ActiveShow
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @var integer
     */
    private $id;

	/**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $title;

	/**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $location;

	/**
     * @ORM\Column(type="string", length=300)
     * @var string
     */
    private $description;

	/**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $nextShow;

	/**
     * @ORM\Column(type="string", length=300)
     * @var string
     */
    private $imageUrl;

	/**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $new;

	/**
	 * Returns the id
	 *  
	 * @return int
	 */
    public function getId()
    {
        return $this->id;
    }
    
    /**
	 * Sets the id
	 *  
	 * @return int
	 */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

	/**
	 * Returns the show title.
	 *  
	 * @return string
	 */
    public function getTitle()
    {
        return $this->title;
    }

	/**
	 * Sets the show title.
     *
	 * @param string $title
     *  
	 * @return MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
	 */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

	/**
	 * Returns the show location.
	 *  
	 * @return string
	 */
    public function getLocation()
    {
        return $this->location;
    }

	/**
	 * Sets the show location.
     *
	 * @param string $location
     *  
	 * @return MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
	 */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

	/**
	 * Returns the show description.
	 *  
	 * @return string
	 */
    public function getDescription()
    {
        return $this->description;
    }

	/**
	 * Sets the show description.
     *
	 * @param string $description
     *  
	 * @return MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
	 */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

	/**
	 * Returns the next show time.
	 *  
	 * @return string
	 */
    public function getNextShow()
    {
        return $this->nextShow;
    }

	/**
	 * Sets the next show time
     *
	 * @param string $nextShow
     *  
	 * @return MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
	 */
    public function setNextShow($nextShow)
    {
        $this->nextShow = $nextShow;
        return $this;
    }

	/**
	 * Returns the show image url.
	 *  
	 * @return string
	 */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

	/**
	 * Sets the show image url.
     *
	 * @param string $imageUrl
     *  
	 * @return MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
	 */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

	/**
	 * Returns if the show is new.
	 *  
	 * @return bool
	 */
    public function isNew()
    {
        return $this->new;
    }

	/**
	 * Sets if the show is new
     *
	 * @param bool $isNew
     *  
	 * @return MKosolofski\HouseSeats\MonitorBundle\Entity\ActiveShow
	 */
    public function setNew($isNew)
    {
        $this->new = $isNew;
        return $this;
    }
}
