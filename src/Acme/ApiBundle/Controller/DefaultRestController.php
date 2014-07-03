<?php

namespace Acme\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Acme\DemoBundle\Entity\Post;

class DefaultRestController extends FOSRestController
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findByID(1);
        
     //   return $this->container->get('doctrine.entity_manager')->$entities;   
      
    }
}
