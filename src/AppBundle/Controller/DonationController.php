<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Donation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Donation controller.
 *
 */
class DonationController extends Controller
{
    /**
     * Lists all donation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $donations = $em->getRepository('AppBundle:Donation')->findAll();

        return $this->render('donation/index.html.twig', array(
            'donations' => $donations,
        ));
    }

    /**
     * Creates a new donation entity.
     *
     */
    public function newAction(Request $request)
    {
        $donation = new Donation();
        $form = $this->createForm('AppBundle\Form\DonationType', $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($donation);
            $em->flush();

            return $this->redirectToRoute('donation_show', array('id' => $donation->getId()));
        }

        return $this->render('donation/new.html.twig', array(
            'donation' => $donation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a donation entity.
     *
     */
    public function showAction(Donation $donation)
    {
        $deleteForm = $this->createDeleteForm($donation);

        return $this->render('donation/show.html.twig', array(
            'donation' => $donation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing donation entity.
     *
     */
    public function editAction(Request $request, Donation $donation)
    {
        $deleteForm = $this->createDeleteForm($donation);
        $editForm = $this->createForm('AppBundle\Form\DonationType', $donation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('donation_edit', array('id' => $donation->getId()));
        }

        return $this->render('donation/edit.html.twig', array(
            'donation' => $donation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a donation entity.
     *
     */
    public function deleteAction(Request $request, Donation $donation)
    {
        $form = $this->createDeleteForm($donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($donation);
            $em->flush();
        }

        return $this->redirectToRoute('donation_index');
    }

    /**
     * Creates a form to delete a donation entity.
     *
     * @param Donation $donation The donation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Donation $donation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('donation_delete', array('id' => $donation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    function VerifDonationStatusAction(Request $request){
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object;
        });
        $serializer = new Serializer([$normalizer]);

        $idUser=$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $tab=$em->getRepository('AppBundle:Donation')->getUserDonation($idUser);

        $userLoged=$em->getRepository('AppBundle:User')->findOneBy(array(
            'id'=>$idUser
        ));
        $nbclick=$userLoged->getNbClick();

        $date1 = strtotime($tab[0]["datedonation"]);
        $date2 = strtotime(date("Y-m-d"));
        $def=$date2-$date1;
        echo $def/86400;
        if($def>1){
            $userLoged->setNbClick(0);
            $em->persist($userLoged);
            $em->flush();
            echo "nbr click rested";
            $status=['status'=>1];
            $jsonContent = $serializer->normalize($status);
            //return new JsonResponse($jsonContent);
        }
        else if($nbclick<3){
            echo "low";
            $status=['status'=>1];
            $jsonContent = $serializer->normalize($status);
            //return new JsonResponse($jsonContent);
        }
        else{
            $status=['status'=>0];
            $jsonContent = $serializer->normalize($status);
            //return new JsonResponse($jsonContent);
        }
        return new JsonResponse($jsonContent);


    }

    function AddDonationAction(Request$request){
        $tab=['complete'=>0];
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object;
        });
        $serializer = new Serializer([$normalizer]);

        $em=$this->getDoctrine()->getManager();
        $donation = new Donation();
        $done=$request->get('done');
        if($done==1){
        $idProjet=$request->get('idProjet');
        $user=$request->get('user');
        $project=$this->getDoctrine()->getManager()->getRepository('AppBundle:Projet')->findOneBy(array(
                'id'=>$idProjet));
        $userLoged=$this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findOneBy(array(
                'id'=>$user));
        $oldPoints=$project->getPoints();
        $project->setPoints($oldPoints+1);
        $donation->setProjet($project);
        $donation->setUser($userLoged);
        $donation->setDatedonation((new \DateTime()));
        $donation->setHeuredonation(new \DateTime());
        $em->persist($donation);

        $nb=$userLoged->getNbClick();
        $userLoged->setNbClick($nb+1);
        $em->persist($userLoged);

        $em->flush();
        $tab=['complete'=>1];
        }
        if($tab['complete']==1){
            $jsonContent = $serializer->normalize($tab);
            //return new JsonResponse($jsonContent);
            return new JsonResponse($jsonContent);
        }

}

}
