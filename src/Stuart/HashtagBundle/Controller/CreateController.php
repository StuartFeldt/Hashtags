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
use Symfony\Component\HttpFoundation\JsonResponse;
use Stuart\HashtagBundle\Entity\Filter;
use Stuart\HashtagBundle\Entity\SiteFilter;

class CreateController extends Controller {

    public $debug = 0;
    public $page = array(
        "title" => 'title',
        "hashtag" => 'hashtag',
        "background" => 'background',
        "site" => 'site',
        "theme" => 'theme',
        "description" => 'description',
        "heading" => 'heading'
    );
    
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
        
        $form = $this->generateForm();
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
            
            $site->sha1Pass();
            $site->upload();

            $em->persist($site);
            $em->flush();

            return $this->redirect($this->generateUrl('stuart_hashtag_view_sub', array(
                'name'  => $site->getSubdomain()
            ), true));
        }
         
        return $this->render('StuartHashtagBundle:Default:standard.html.twig', array('page' => $page, 'themes' => $this->getThemes(), 'messages' => $messages, 'request' => $request, 'form' => $form->createView(), 'debug' => $this->debug));
    }
    
    public function plusAction(Request $request)
    {
        $messages = 0;
        $params = 0;
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Hashtag your event",
            "description" => 'Standard+'
            );
        
        $form = $this->generateForm();
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $site = $form->getData();
            
            if($this->verifySubdomain($site->getSubdomain())) {
                $messages = array("error" => "Subdomain taken.  Please choose another.");
                return $this->render('StuartHashtagBundle:Default:plus.html.twig', array('page' => $page, 'messages' => $messages, 'debug' => $this->debug, 'form' => $form->createView()));
            }
            
            $endDate = new \DateTime($site->getStartDate()->format('Y-m-d H:i:s'));
            $endDate->add(new \DateInterval("P1M"));        
            $site->setEndDate($endDate);
            
            $em = $this->getDoctrine()->getManager();
            
            $site->sha1Pass();
            $site->upload();

            $em->persist($site);
            $em->flush();
            
            $bu[0] = $request->get("blockedUsers1");
            $bu[1] = $request->get("blockedUsers2");
            $bu[2] = $request->get("blockedUsers3");
            $bu[3] = $request->get("blockedUsers4");
            $bu[4] = $request->get("blockedUsers5");
            
            foreach($bu as $blockedUser) {
                if($blockedUser != "") {
                    $filter = new Filter();
                    $filter->setType("BU");
                    $filter->setFilter($blockedUser);
                    $em->persist($filter);
                    $em->flush();

                    $siteFilter = new SiteFilter();
                    $siteFilter->setSiteId($site->getId());
                    $siteFilter->setFilterId($filter->getId());
                    $em->persist($siteFilter);
                    $em->flush();
                }
            }
            return $this->redirect($this->generateUrl('stuart_hashtag_view_sub', array(
               'name'  => $site->getSubdomain()
            ), true));
        }
        
        return $this->render('StuartHashtagBundle:Default:plus.html.twig', array('request' => $params, 'form' => $form->createView(),'messages' => $messages, 'page' => $page, 'themes' => $this->getThemes()));
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
    
    public function checkSubAction($sub) {
        return $this->verifySubdomain($sub) ? new JsonResponse(array("exists" => true)) : new JsonResponse(array("exists" => false));
    }
    
    public function adminSiteAction($name, Request $request) {
        
        if($name == "dev") {
            return $this->adminHomeAction($request);
        }

        //get the specified site
        $site = $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->findOneBySubdomain($name);
        
        //check authed
        if($this->getRequest()->getSession()->get("auth") != sha1($site->getId().$site->getPassword())) {
            return $this->adminHomeAction($request);
        }
        
        //Gen theme choices
        $themes = $this->getThemes();
        $themeList = array();
        foreach($themes as $theme) {
            $themeList[$theme["id"]] = $theme["themeName"];
        }
        $themeChoices = array('choices' => $themeList);
        
        $form = $this->createFormBuilder($site)
            ->add('name')
            ->add('hashtag')
            ->add('themeId', 'choice', $themeChoices)
            ->add('file')
            ->add('update', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $site = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();        
            
            return $this->redirect($this->generateUrl('stuart_hashtag_view_sub', array(
                'name'  => $site->getSubdomain()
            ), true));
        }
        
        $this->page["heading"] = $site->getName();
        $this->page["title"] = $site->getName()." Admin Panel";
        $this->page["sub"] = $site->getSubdomain();
        $this->page["startDate"] = $site->getStartDate()->format('Y-m-d');
        $this->page["endDate"] = $site->getEndDate()->format('Y-m-d');
        $this->page["description"] = "Admin Panel";
        $this->page["bgimg"] = "/assets/img/".$site->getBackgroundImage();
        
        return $this->render('StuartHashtagBundle:Default:adminSite.html.twig', array(
            'page' => $this->page,
            'form' => $form->createView(),
            'request' => $request
        ));
    }
    
    public function createThemeAction() {
        $this->page["heading"] = "Create a Theme";
        $this->page["title"] = "Create a Theme";
        $this->page["description"] = "";
        
        return $this->render('StuartHashtagBundle:Default:createTheme.html.twig', array(
            'page' => $this->page
        ));
    }
    
    public function adminHomeAction(Request $request) {
        
        $this->page["heading"] = "Admin Panel";
        $this->page["title"] = "Admin Panel";
        $this->page["description"] = "Enter a subdomain and password to continue.";
        $logger = $this->get('logger');
        
        //check both subdomain and password have been filled
        if($request->get("sub") != "" && $request->get("pass") != "") {
            
            //get site by subdomain
            $site = $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->findOneBySubdomain($request->get("sub"));
            
            //sha1 password to see if it matches
            if(!$site) {
                
                //subdomain is incorrect
                $message = array("error" => "Password or subdomain is incorrect");
                return $this->render('StuartHashtagBundle:Default:adminHome.html.twig', array('page' => $this->page, 'messages' => $message));
                
            } else if(sha1($request->get("pass")) == $site->getPassword()) {
                
                //password is correct
                $session = $this->getRequest()->getSession();
                $session->set("auth", sha1($site->getId().$site->getPassword()));
                
                //DEV ONLY
                if($this->container->get( 'kernel' )->getEnvironment() == "dev") {
                    return $this->adminSiteAction($request->get("sub"), $request);
                }
                
                //PRODUCTION
                return $this->adminSiteAction($request->get("sub"), $request);
                        
            } else {
                
                //password is incorrect
                $message = array("error" => "Password or subdomain is incorrect");
                return $this->render('StuartHashtagBundle:Default:adminHome.html.twig', array('page' => $this->page, 'messages' => $message));
                
            }
        } 
        
        return $this->render('StuartHashtagBundle:Default:adminHome.html.twig', array('page' => $this->page, 'messages' => 0));
    }
    
    function getThemes() {
        $themes = $this->getDoctrine()->getRepository('StuartHashtagBundle:Theme')->findAll();
        
        $themeResults = array();
        foreach($themes as $theme) {
            if(!$theme->getPrivate()){
                array_push($themeResults, array(
                    'themeName' => $theme->getThemeName(),
                    'themeClass' => $theme->getClass(),
                    'id' => $theme->getId()
                ));
            }
        }
        
        return $themeResults;
    }
    
    function verifySubdomain($subdomain) {
        return $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->findOneBySubdomain($subdomain);    
    }
    
    function generateForm() {
        //Gen theme choices
        $themes = $this->getThemes();
        $themeList = array();
        foreach($themes as $theme) {
            $themeList[$theme["id"]] = $theme["themeName"];
        }
        $themeChoices = array('choices' => $themeList);
        
        $site = new Site();
        $site->setStartDate(new \DateTime());
        $form = $this->createFormBuilder($site)
                ->add('name')
                ->add('subdomain')
                ->add('hashtag')
                ->add('startDate', 'date')
                ->add('themeId', 'choice', $themeChoices)
                ->add('file')
                ->add('password', 'password')
                ->add('create', 'submit')
                ->getForm();
        
        return $form;
    }
    
    
}

?>
