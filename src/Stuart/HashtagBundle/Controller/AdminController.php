<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @author stuartfeldt
 */

namespace Stuart\HashtagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Stuart\HashtagBundle\Entity\Site;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller {
    
    public $page = array(
        "title" => 'Admin',
        "hashtag" => 'hashtag',
        "background" => 'background',
        "site" => 'site',
        "theme" => 'theme',
        "description" => 'Adminster',
        "heading" => 'Admin Panel'
    );
    
    public function indexAction($page) {
        
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('count(site.id)');
        $qb->from('StuartHashtagBundle:Site','site');

        $count = $qb->getQuery()->getSingleScalarResult();
        $pages = ceil($count/10);
        
        $sites =  $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->findBy(array(), null, 10, ($page-1)*10);
        
        return $this->render('StuartHashtagBundle:Admin:index.html.twig', array('page' => $this->page, 'sites' => $sites, 'pages' => $pages, 'active' => $page));
    }
    
    public function subdomainAction($sub, $page) {
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('count(tweet.id)');
        $qb->add('where', 'tweet.siteId = ?1');
        $qb->from('StuartHashtagBundle:Tweet','tweet');
        $qb->setParameter(1, $sub);

        $count = $qb->getQuery()->getSingleScalarResult();
        $pages = ceil($count/10);
        
        $tweets =  $this->getDoctrine()->getRepository('StuartHashtagBundle:Tweet')->findBy(array("siteId" => $sub), null, 10, ($page-1)*10);
        
        return $this->render('StuartHashtagBundle:Admin:subdomain.html.twig', array('sub' => $sub, 'page' => $this->page, 'tweets' => $tweets, 'pages' => $pages, 'active' => $page));
    }
    
    public function modifySiteAction($site, Request $request) {
        
        $site = $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->find($site);
        $form = $this->createFormBuilder($site)
            ->add('name', 'text')
            ->add('subdomain', 'text')
            ->add('backgroundImage', 'text')
            ->add('hashtag', 'text')
            ->add('themeId', 'integer')
            ->add('startDate', 'datetime')
            ->add('endDate', 'datetime')
            ->add('save', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $site = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();        
            
            return $this->indexAction(1);
        }

        
        
        return $this->render('StuartHashtagBundle:Admin:modifySite.html.twig', array(
            'page' => $this->page,
            'form' => $form->createView(),
            'request' => $request
        ));
    }
    
    public function modifyTweetAction($tweet, Request $request) {
        
        $tweet = $this->getDoctrine()->getRepository('StuartHashtagBundle:Tweet')->find($tweet);
        $form = $this->createFormBuilder($tweet)
            ->add('tweetBody', 'text')
            ->add('tweetAuthor', 'text')
            ->add('tweetAuthorPic', 'text')
            ->add('tweetId', 'text')
            ->add('siteId', 'integer')
            ->add('tweetTime', 'text')
            ->add('tweetPic', 'text')
            ->add('tweetType', 'text')
            ->add('save', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $tweet = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($tweet);
            $em->flush();        
            
            return $this->indexAction(1);
        }

        return $this->render('StuartHashtagBundle:Admin:modifyTweet.html.twig', array(
            'page' => $this->page,
            'form' => $form->createView(),
            'request' => $request
        ));
    }
    
    public function deleteTweetAction($tweet) {
        $tweet =  $this->getDoctrine()->getRepository('StuartHashtagBundle:Tweet')->find($tweet);
        $this->getDoctrine()->getManager()->remove($tweet);
        $this->getDoctrine()->getManager()->flush();
        return $this->indexAction(1);
    }
    
    public function deleteSiteAction($site) {
        $site =  $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->find($site);
        $this->getDoctrine()->getManager()->remove($site);
        $this->getDoctrine()->getManager()->flush();
        return $this->indexAction(1);
    }
    
}

?>
