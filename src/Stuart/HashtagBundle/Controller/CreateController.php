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
        if($request->get('hashtag')) {
            $site = new Site();
            $site->setName($request->get('name'));
            $site->setHashtag($request->get('hashtag'));
            $site->setSubdomain($request->get('subdomain'));
            $site->setThemeId($request->get('theme'));
            
            $files = $request->files;
            $directory = "/assets/img/";

            foreach ($files as $uploadedFile) {
                $name = $site->getName();
                $file = $uploadedFile->move($directory, $name);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();
            
            /*return $this->redirect($this->generateUrl('stuart_hashtag_view', array(
                'name'  => $site->getSubdomain()
            ), true));*/

        }
        
        //set page vars
        $page = array(
            "title" => 'Create a hashtag', 
            "heading" => "Create a Hashtag Thing",
            "description" => 'Standard'
            );
        
        return $this->render('StuartHashtagBundle:Default:standard.html.twig', array('page' => $page, 'themes' => $this->getThemes(), 'request' => $request));
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
    
}

?>
