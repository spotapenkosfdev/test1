<?php

namespace Acme\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\DemoBundle\Entity\Post;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeApiBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function json_testAction()
    {
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findAll();
        
        // create a simple Response with a 200 status code (the default)
    //    $response = new Response('', Response::HTTP_OK);
      //  $response->setStatusCode(200,'huj');
        // create a JSON-response with a 200 status code
        
         $serializedEntity = $this->get('jms_serializer')->serialize($entities, 'json');
        
        $response = new Response($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
       // return $this->render('AcmeApiBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function json_test2Action()
    {
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findAll();
        //$serializedEntity = $this->get('jms_serializer')->serialize($entities, 'json');
        $response = new JsonResponse();
        $response->setData(array('title' => $entities[0]->getTitle()));
    /*    $response->setData(array(
            'data' => 123
        ));*/
        return $response;
       // return $this->render('AcmeApiBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function json_test3Action()
    {
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findAll();
        
        $serializedEntity = $this->get('jms_serializer')->serialize($entities, 'json');

        return new Response($serializedEntity);
        
    }
    
    public function noAction()
    {
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findAll();
        
        $serializedEntity = $this->get('jms_serializer')->serialize($entities, 'json');

        return new Response($serializedEntity);
        
    }
    
    public function getAction()
    {
 /*    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
            var_dump($data);
        }*/
       $request = Request::createFromGlobals(); 
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findAll();
        
        $serializedEntity = $this->get('serializer')->serialize($entities, 'json');

        return new Response($serializedEntity);
        
    }
    
    public function showAction($id)
    {
       $request = Request::createFromGlobals(); 
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findById($id);
        
        $serializedEntity = $this->get('serializer')->serialize($entities, 'json');

        return new Response($serializedEntity);
        
    }
    
    public function postAction()
    {
        
        $request = Request::createFromGlobals();
       
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
       //     $request->request->replace(is_array($data) ? $data : array());
        } 
        
       
        $product = new Post();
        $product->setTitle($data['title']);
        $product->setBody($data['body']);
    
 
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($product);
        $em->flush();

        $serializedEntity = $this->get('jms_serializer')->serialize($product, 'json');

        return new Response($serializedEntity);
        
        
        
    }
    
    public function putAction(Request $request)
    {
        
        $request->setMethod('PATCH'); //Treat all PUTs as PATCH
         
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
      
        $id = $data['id'];

        $em = $this->getDoctrine()->getEntityManager();
        
        $product = $em->getRepository('AcmeDemoBundle:Post')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        $product->setTitle($data['title']);
        $product->setBody($data['body']);
        $em->flush();

            
        $serializedEntity = $this->get('jms_serializer')->serialize($product, 'json');

        return new Response($serializedEntity);    
        
    }
    /**
     * Delete a Post entity.
     *
     * 
     * */
    public function deleteAction($id)
    {

     /*   try {
            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);
            $em->remove($entity);
            $em->flush();

            return null;
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }*/
        
        
        
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository('AcmeDemoBundle:Post')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }
        
        $serializedEntity = $this->get('jms_serializer')->serialize($product, 'json');
        
        $em->remove($product);
        $em->flush();
     
         
            
      

        return new Response($serializedEntity);    
       // return new Response('');    

    }

}
