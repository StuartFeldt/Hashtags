<?php

namespace Stuart\HashtagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tweet
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tweet
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
     * @ORM\Column(name="tweetBody", type="string", length=300)
     */
    private $tweetBody;

    /**
     * @var string
     *
     * @ORM\Column(name="tweetAuthor", type="string", length=45)
     */
    private $tweetAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="tweetAuthorPic", type="string", length=45)
     */
    private $tweetAuthorPic;

    /**
     * @var string
     *
     * @ORM\Column(name="tweetId", type="string", length=45)
     */
    private $tweetId;

    /**
     * @var integer
     *
     * @ORM\Column(name="siteId", type="integer")
     */
    private $siteId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tweetTime",  type="string", length=45)
     */
    private $tweetTime;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tweetPic",  type="string", length=450)
     */
    private $tweetPic;


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
     * Set tweetBody
     *
     * @param string $tweetBody
     * @return Tweet
     */
    public function setTweetBody($tweetBody)
    {
        $this->tweetBody = $tweetBody;

        return $this;
    }

    /**
     * Get tweetBody
     *
     * @return string 
     */
    public function getTweetBody()
    {
        return $this->tweetBody;
    }

    /**
     * Set tweetAuthor
     *
     * @param string $tweetAuthor
     * @return Tweet
     */
    public function setTweetAuthor($tweetAuthor)
    {
        $this->tweetAuthor = $tweetAuthor;

        return $this;
    }

    /**
     * Get tweetAuthor
     *
     * @return string 
     */
    public function getTweetAuthor()
    {
        return $this->tweetAuthor;
    }

    /**
     * Set tweetAuthorPic
     *
     * @param string $tweetAuthorPic
     * @return Tweet
     */
    public function setTweetAuthorPic($tweetAuthorPic)
    {
        $this->tweetAuthorPic = $tweetAuthorPic;

        return $this;
    }

    /**
     * Get tweetAuthorPic
     *
     * @return string 
     */
    public function getTweetAuthorPic()
    {
        return $this->tweetAuthorPic;
    }

    /**
     * Set tweetId
     *
     * @param string $tweetId
     * @return Tweet
     */
    public function setTweetId($tweetId)
    {
        $this->tweetId = $tweetId;

        return $this;
    }

    /**
     * Get tweetId
     *
     * @return string 
     */
    public function getTweetId()
    {
        return $this->tweetId;
    }

    /**
     * Set siteId
     *
     * @param integer $siteId
     * @return Tweet
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
     * Set tweetTime
     *
     * @param string $tweetTime
     * @return Tweet
     */
    public function setTweetTime($tweetTime)
    {
        $this->tweetTime = $tweetTime;

        return $this;
    }

    /**
     * Get tweetTime
     *
     * @return string 
     */
    public function getTweetTime()
    {
        return $this->tweetTime;
    }
    
    /**
     * Set tweetPic
     *
     * @param string $tweetPic
     * @return Tweet
     */
    public function settweetPic($tweetPic)
    {
        $this->tweetPic = $tweetPic;

        return $this;
    }

    /**
     * Get tweetPic
     *
     * @return string 
     */
    public function gettweetPic()
    {
        return $this->tweetPic;
    }
}
