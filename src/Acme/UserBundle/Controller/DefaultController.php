<?php

namespace Acme\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    protected function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }

    public function indexAction($name)
    {
//        return $this->render('AcmeUserBundle:Default:index.html.twig', array('name' => $name));
        $um = $this->getUserManager();
        $users = $um->findUsers();
//        return new JsonResponse($users);
        $serializedEntity = $this->get('jms_serializer')->serialize($users, 'json');
        return new Response($serializedEntity);
    }
}
