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
     * @param  int $id 
	 * @return $this
	 */
    public function setId($id): ActiveShow
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
	 * @return $this
	 */
    public function setTitle($title): ActiveShow
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
	 * @return $this
	 */
    public function setLocation($location): ActiveShow
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
	 * @return $this
	 */
    public function setDescription($description): ActiveShow
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
	 * @return $this
	 */
    public function setNextShow($nextShow): ActiveShow
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
	 * @return $this
	 */
    public function setImageUrl($imageUrl): ActiveShow
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
	 * @return $this
	 */
    public function setNew($isNew): ActiveShow
    {
        $this->new = $isNew;
        return $this;
    }
}
