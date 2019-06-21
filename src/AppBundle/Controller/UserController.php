<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Donation;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    public function newUserAPIAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        $nom=$request->get('nom');
        $prenom=$request->get('prenom');
        $date=$request->get('date');
        $adr=$request->get('adr');
        $mail=$request->get('mail');
        $pass=$request->get('pass');
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setDateNaissance($date);
        $user->setLieuRes($adr);
        $user->setEmail($mail);
        $user->setPwd($pass);



        $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object;
        });
        $serializer = new Serializer([$normalizer]);

        $jsonContent = $serializer->normalize($user);
        return new JsonResponse($jsonContent);


    }



    public function LoginUserAPIAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        $mail=$request->get('mail');
        $pass=$request->get('pass');

        $em = $this->getDoctrine()->getManager();
        $logedin=$em->getRepository(User::class)->findOneBy(array(
            'email' => $mail,
            'pwd' => $pass

        ));

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object;
        });
        $serializer = new Serializer([$normalizer]);

        $jsonContent = $serializer->normalize($logedin);
        return new JsonResponse($jsonContent);
    }


     function HistoriqueClickAction(Request $request){
            $userLoged=$request->get('id');

         $em = $this->getDoctrine()->getManager();
        // $user=$em->getRepository('AppBundle:User')->findOneBy(array(
         //    'id'=>$userLoged
         //));
         //$nbClick=$em->getRepository('AppBundle:Donation')->findBy(array(
             //'user'=>$user
        // ));

         $nbClick=$em->getRepository('AppBundle:User')->getNbClick($userLoged);
         //var_dump($nbClick);
         $normalizer = new ObjectNormalizer();
         $normalizer->setCircularReferenceLimit(2);
         // Add Circular reference handler
         $normalizer->setCircularReferenceHandler(function ($object) {
             return $object;
         });
         $serializer = new Serializer([$normalizer]);
            $click=['nb'=>sizeof($nbClick)];
         $jsonContent = $serializer->normalize($nbClick);
         return new JsonResponse($jsonContent);
     }
     function profileAction(Request $request){
         $userLoged=$request->get('id');

         $em = $this->getDoctrine()->getManager();

         $profile=$em->getRepository('AppBundle:User')->findOneBy(array(
             'id'=>$userLoged
         ));
         //var_dump($nbClick);
         $normalizer = new ObjectNormalizer();
         $normalizer->setCircularReferenceLimit(2);
         // Add Circular reference handler
         $normalizer->setCircularReferenceHandler(function ($object) {
             return $object;
         });
         $serializer = new Serializer([$normalizer]);

         $jsonContent = $serializer->normalize($profile);
         return new JsonResponse($jsonContent);
     }


}
