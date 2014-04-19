<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CreateConroller
 *
 * @author stuartfeldt
 */

namespace Stuart\HashtagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Stuart\HashtagBundle\Entity\Site;
use Stuart\HashtagBundle\Entity\Background;

class CreateController extends Controller {
    
    public $debug = 0;
    
    public function createAction(Request $request)
    {
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Hashtag your event",
            "description" => 'Create'
            );
        
        return $this->render('StuartHashtagBundle:Default:create.html.twig', array('page' => $page, 'themes' => $this->getThemes()));
    }
    
    public function standardAction(Request $request)
    {
        $logger = $this->get('logger');
        
        
        $messages = 0;
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Hashtag your event",
            "description" => 'Standard'
            );
       
        
        //Gen theme choices
        $themes = $this->getThemes();
        $themeList = array();
        foreach($themes as $theme) {
            $themeList[$theme["id"]] = $theme["themeName"];
        }
        $themeChoices = array('choices' => $themeList);
        
        $site = new Site();
        $form = $this->createFormBuilder($site)
                ->add('name')
                ->add('subdomain')
                ->add('hashtag')
                ->add('startDate', 'date')
                ->add('themeId', 'choice', $themeChoices)
                ->add('file')
                ->add('create', 'submit')
                ->getForm();
        
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $site = $form->getData();
            
            if($this->verifySubdomain($site->getSubdomain())) {
                $messages = array("error" => "Subdomain taken.  Please choose another.");
                return $this->render('StuartHashtagBundle:Default:standard.html.twig', array('page' => $page, 'messages' => $messages, 'debug' => $this->debug, 'form' => $form->createView()));
            }
            
            $endDate = new \DateTime($site->getStartDate()->format('Y-m-d H:i:s'));
            $endDate->add(new \DateInterval("P14D"));        
            $site->setEndDate($endDate);
            
            $em = $this->getDoctrine()->getManager();
            
            $site->upload();

            $em->persist($site);
            $em->flush();

            return $this->redirect($this->generateUrl('stuart_hashtag_view', array(
                'name'  => $site->getSubdomain()
            ), true));
        }
         
        return $this->render('StuartHashtagBundle:Default:standard.html.twig', array('page' => $page, 'themes' => $this->getThemes(), 'messages' => $messages, 'request' => $request, 'form' => $form->createView(), 'debug' => $this->debug));
    }
    
    public function plusAction(Request $request)
    {
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Hashtag your event",
            "description" => 'Standard+'
            );
        
        return $this->render('StuartHashtagBundle:Default:plus.html.twig', array('page' => $page, 'themes' => $this->getThemes()));
    }
    
    public function professionalAction(Request $request)
    {
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Hashtag your event",
            "description" => 'Premium'
            );
        
        return $this->render('StuartHashtagBundle:Default:professional.html.twig', array('page' => $page, 'themes' => $this->getThemes()));
    }
    
    function getThemes() {
        $themes = $this->getDoctrine()->getRepository('StuartHashtagBundle:Theme')->findAll();
        
        $themeResults = array();
        foreach($themes as $theme) {
            array_push($themeResults, array(
                'themeName' => $theme->getThemeName(),
                'themeClass' => $theme->getClass(),
                'id' => $theme->getId()
            ));
        }
        
        return $themeResults;
    }
    
    function verifySubdomain($subdomain) {
        return $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->findOneBySubdomain($subdomain);    
    }
    
}

?>
