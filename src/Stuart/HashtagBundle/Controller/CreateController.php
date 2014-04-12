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

class CreateController extends Controller {
    
    public $debug = 0;
    
    public function createAction(Request $request)
    {
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Create a Hashtag Thing",
            "description" => 'Create'
            );
        
        return $this->render('StuartHashtagBundle:Default:create.html.twig', array('page' => $page, 'themes' => $this->getThemes()));
    }
    
    public function standardAction(Request $request)
    {
        $messages = 0;
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Create a Hashtag Thing",
            "description" => 'Standard'
            );
        
        if($request->get('hashtag')) {
            
            if($this->verifySubdomain($request->get('subdomain'))) {
                $messages = array("error" => "Subdomain taken.  Please choose another.");
                return $this->render('StuartHashtagBundle:Default:standard.html.twig', array('page' => $page, 'themes' => $this->getThemes(), 'messages' => $messages, 'debug' => $this->debug));
            }
            
            $site = new Site();
            $site->setName($request->get('name'));
            $site->setHashtag($request->get('hashtag'));
            $site->setSubdomain($request->get('subdomain'));
            $site->setThemeId($request->get('theme'));
            
            $startDate = new \DateTime($request->get('start'));
            
            $endDate = new \DateTime($request->get('start'));
            $endDate->add(new \DateInterval("P14D"));
            
            $site->setStartDate($startDate);
            $site->setEndDate($endDate);
            
           // $site->setEndDate(date('Y-m-d', strtotime($site->getStartDate(). ' + 14 days')));
            //$this->debug = array('startdate' => date( 'Y-m-d H:i:s', strtotime( $request->get('start'))));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();
            
           /* return $this->redirect($this->generateUrl('stuart_hashtag_view', array(
                'name'  => $site->getSubdomain()
            ), true));*/

        }
        
        
        
        return $this->render('StuartHashtagBundle:Default:standard.html.twig', array('page' => $page, 'themes' => $this->getThemes(), 'messages' => $messages, 'request' => $request, 'debug' => $this->debug));
    }
    
    public function plusAction(Request $request)
    {
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Create a Hashtag Thing",
            "description" => 'Standard+'
            );
        
        return $this->render('StuartHashtagBundle:Default:plus.html.twig', array('page' => $page, 'themes' => $this->getThemes()));
    }
    
    public function professionalAction(Request $request)
    {
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Create a Hashtag Thing",
            "description" => 'Professional'
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
