<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Entity\Post;
use Acme\DemoBundle\Form\PostType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\RouteResource("Post")
 */
class RestPostController extends FOSRestController
{
    /**
     *
     * @return array
     *
     * @Rest\View()
     */
    public function cgetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeDemoBundle:Post')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Get action
     * @var integer $id Id of the entity
     * @return array
     *
     * @Rest\View()
     */
    public function getAction($id)
    {
        $entity = $this->getEntity($id);

        return array(
            'post' => $entity,
        );
    }

    /**
     * Collection post action
     * @var Request $request
     * @return View|array
     */
    public function cpostAction(Request $request)
    {
        $entity = new Post();
        $form = $this->createForm(new PostType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectView(
                $this->generateUrl(
                    'get_post',
                    array('id' => $entity->getId())
                ),
                Codes::HTTP_CREATED
            );
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Get entity instance
     * @var integer $id Id of the entity
     * @return Post
     */
    protected function getEntity($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeDemoBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find post entity');
        }

        return $entity;
    }

} 