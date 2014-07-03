<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Acme\DemoBundle\Entity\Post;
use Acme\DemoBundle\Form\PostType;

use FOS\RestBundle\View\View as FOSView;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Voryx\RESTGeneratorBundle\Controller\VoryxController;

//MANUAL_EXAMPLE SEE http://voryx.net/rest-apis-with-symfony2-the-easy-way/


/**
 * Post controller.
 * @RouteResource("Post")
 */
class PostRESTController extends VoryxController
{
    /**
     * Get a Post entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function getAction(Post $entity)
    {
        return $entity;
    }

    /**
     * Get all Post entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param \FOS\RestBundle\Request\ParamFetcherInterface $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="order_by", nullable=true, array=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
     * @QueryParam(name="filters", nullable=true, array=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
     *
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $order_by = $paramFetcher->get('order_by');
            $filters = !is_null($paramFetcher->get('filters')) ? $paramFetcher->get('filters') : array();

            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('AcmeDemoBundle:Post')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            } else {
                return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
            }
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a Post entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function postAction(Request $request)
    {

        $entity = new Post();
        $form = $this->createForm(new PostType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update a Post entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     * @return \Symfony\Component\HttpFoundation\Response
     *
     *
     */
    public function putAction(Request $request, Post $entity)
    {

        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new PostType(), $entity, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();

                return $entity;
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

/**
    * Partial Update to a Post entity.
    *
    * @View(serializerEnableMaxDepthChecks=true)
    *
    * @param Request $request
    * @param $entity
    * @return \Symfony\Component\HttpFoundation\Response
    *
*
    */
    public function patchAction(Request $request, Post $entity)
    {

        return $this->putAction($request, $entity);



    }

    /**
     * Delete a Post entity.
     *
     * @View(statusCode=204)
     *
     * @param Request $request
     * @param $entity
     * @internal param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, Post $entity)
    {

        try {
            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);
            $em->remove($entity);
            $em->flush();

            return null;
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }

    }




}
