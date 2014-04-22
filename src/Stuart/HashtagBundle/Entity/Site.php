<?php

namespace Stuart\HashtagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Site
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Site implements UserInterface, \Serializable
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
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="subdomain", type="string", length=45)
     */
    private $subdomain;

    /**
     * @var string
     *
     * @ORM\Column(name="backgroundImage", type="string", length=255)
     */
    private $backgroundImage;

    /**
     * @var string
     *
     * @ORM\Column(name="hashtag", type="string", length=255)
     */
    private $hashtag;
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=160)
     */
    private $password;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="themeId", type="integer")
     */
    private $themeId;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    
    
    
    
    
    
    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }
    
    
    
    
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

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
     * Set name
     *
     * @param string $name
     * @return Site
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set subdomain
     *
     * @param string $subdomain
     * @return Site
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return string 
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Set backgroundImage
     *
     * @param string $backgroundImage
     * @return Site
     */
    public function setBackgroundImage($backgroundImage)
    {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    /**
     * Get backgroundImage
     *
     * @return string 
     */
    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }

    /**
     * Set hashtag
     *
     * @param string $hashtag
     * @return Site
     */
    public function setHashtag($hashtag)
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    /**
     * Get hashtag
     *
     * @return string 
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }
    
    /**
     * Set password
     *
     * @param string $password
     * @return Site
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Site
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Site
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set themeId
     *
     * @param integer $themeId
     * @return Site
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;

        return $this;
    }

    /**
     * Get themeId
     *
     * @return integer 
     */
    public function getThemeId()
    {
        return $this->themeId;
    }
    
    
    public function getAbsolutePath()
    {
        return null === $this->backgroundImage
            ? null
            : $this->getUploadRootDir().'/'.$this->backgroundImage;
    }

    public function getWebPath()
    {
        return null === $this->backgroundImage
            ? null
            : $this->getUploadDir().'/'.$this->backgroundImage;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/documents';
    }
    
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->backgroundImage = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
    
    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->subdomain,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function sha1Pass() {
        $this->password = sha1($this->password);
        return true;
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->subdomain,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    
    public function getSalt() {
        
    }
    public function getUsername() {
        
    }
}
