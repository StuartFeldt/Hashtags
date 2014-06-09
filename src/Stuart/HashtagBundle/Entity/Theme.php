<?php

namespace Stuart\HashtagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Theme
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Theme
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
     * @var string
     *
     * @ORM\Column(name="themeName", type="string", length=45)
     */
    private $themeName;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=45)
     */
    private $class;
   
    /**
     * @var string
     *
     * @ORM\Column(name="css", type="string", length=5000)
     */
    private $css;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="privateForSite", type="integer")
     */
    private $privateForSite;

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
     * Get id
     *
     * @return integer 
     */
    public function getPrivateForSite()
    {
        return $this->privateForSite;
    }
    
    /**
     * Set privateForSite
     *
     * @param string $site
     * @return Theme
     */
    public function setPrivateForSite($site)
    {
        $this->privateForSite = $site;

        return $this;
    }

    /**
     * Set themeName
     *
     * @param string $themeName
     * @return Theme
     */
    public function setThemeName($themeName)
    {
        $this->themeName = $themeName;

        return $this;
    }

    /**
     * Get themeName
     *
     * @return string 
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return Theme
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }
    
    /**
     * Set css
     *
     * @param string $css
     * @return Theme
     */
    public function setCss($css)
    {
        $this->css = $css;

        return $this;
    }

    /**
     * Get css
     *
     * @return string 
     */
    public function getCss()
    {
        return $this->css;
    }
    
    /**
     * Set private
     *
     * @param string $private
     * @return Theme
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean 
     */
    public function getPrivate()
    {
        return $this->private;
    }
}
