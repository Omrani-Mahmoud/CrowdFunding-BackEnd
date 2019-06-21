<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Association;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Association controller.
 *
 */
class AssociationController extends Controller
{
    /**
     * Lists all association entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $associations = $em->getRepository('AppBundle:Association')->findAll();

        return $this->render('association/index.html.twig', array(
            'associations' => $associations,
        ));
    }

    /**
     * Creates a new association entity.
     *
     */
    public function newAction(Request $request)
    {
        $association = new Association();
        $form = $this->createForm('AppBundle\Form\AssociationType', $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($association);
            $em->flush();

            return $this->redirectToRoute('association_show', array('id' => $association->getId()));
        }

        return $this->render('association/new.html.twig', array(
            'association' => $association,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a association entity.
     *
     */
    public function showAction(Association $association)
    {
        $deleteForm = $this->createDeleteForm($association);

        return $this->render('association/show.html.twig', array(
            'association' => $association,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing association entity.
     *
     */
    public function editAction(Request $request, Association $association)
    {
        $deleteForm = $this->createDeleteForm($association);
        $editForm = $this->createForm('AppBundle\Form\AssociationType', $association);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('association_edit', array('id' => $association->getId()));
        }

        return $this->render('association/edit.html.twig', array(
            'association' => $association,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a association entity.
     *
     */
    public function deleteAction(Request $request, Association $association)
    {
        $form = $this->createDeleteForm($association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($association);
            $em->flush();
        }

        return $this->redirectToRoute('association_index');
    }

    /**
     * Creates a form to delete a association entity.
     *
     * @param Association $association The association entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Association $association)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('association_delete', array('id' => $association->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function ShowAssociationAction(Request $request)
    {

        $thm=$request->get('theme');
        $encoders = new JsonEncode();
        $normalizers = new GetSetMethodNormalizer();
        $serializer = new Serializer([$normalizers], [$encoders]);
        $projet = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Association')
            ->getByTheme($thm);

        $result = $serializer->normalize($projet, 'json');
        return $this->json($result);

    }


    public function SearchAssociationAction(Request $request){

        $text=$request->get('text');
        $tasks = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:Association')
                ->getByName($text);
        $encoders = new JsonEncode();
        $normalizers = new GetSetMethodNormalizer();
        $serializer = new Serializer([$normalizers], [$encoders]);
        $result = $serializer->normalize($tasks, 'json');
        return $this->json($result);


    }
}
