<?php

namespace Stuart\HashtagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SiteFilter
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SiteFilter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="siteId", type="integer")
     */
    private $siteId;

    /**
     * @var integer
     *
     * @ORM\Column(name="filterId", type="integer")
     */
    private $filterId;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set siteId
     *
     * @param integer $siteId
     * @return SiteFilter
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return integer 
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set filterId
     *
     * @param integer $filterId
     * @return SiteFilter
     */
    public function setFilterId($filterId)
    {
        $this->filterId = $filterId;

        return $this;
    }

    /**
     * Get filterId
     *
     * @return integer 
     */
    public function getFilterId()
    {
        return $this->filterId;
    }
}
