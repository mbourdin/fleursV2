<?php
/**
 * Created by PhpStorm.
 * User: Frédéric
 * Date: 19/04/2019
 * Time: 14:49
 */

namespace App\Controller;


use App\Entity\MessageAdmin;
use App\Entity\Person;
use App\Form\MessageAdminFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageAdminController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/contactAdmin")
     */
    public function addMessageAdminAction(Request $request)
    {   $session=$request->getSession();
        if($session->get("connected"))
        {   $user=$this->getDoctrine()->getRepository(Person::class)->find($session->get("user")->getId());
            $email = $user->getEmail();
        }
        $msg = new MessageAdmin();

        $form = $this->createForm(MessageAdminFormType::class, $msg,["email"=>$email]);
        $form->add('submit', SubmitType::class, [
            'label' => 'envoyer',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($msg);
            $reg->flush();
            $this->addFlash("success","message admin envoyé");

            return $this->redirect("/");
        }
        $formView= $form->createView();

        return $this->render('msgadmin/contact.html.twig', array('form' => $formView));

    }

    /**
     * @Route("/admin/message/list")
     */

    public function msgAdminListAction(){
        $msgAdminDao=$this->getDoctrine()->getRepository(MessageAdmin::class);
        $msgAdmins=$msgAdminDao->findAll();

        return $this->render('admin/messageList.html.twig',array("msgadmins"=>$msgAdmins));
    }


    /**
     * @Route("/admin/message/read/{id}")
     */
    public function msgAdminAdminAction($id){
        $msgAdminDao=$this->getDoctrine()->getRepository("App:MessageAdmin");
        $msgAdmin=$msgAdminDao->find($id);

        return $this->render('admin/message.html.twig', array('msgAdmin' =>$msgAdmin));


    }


//    A QUOI CA SERT?
//    /**
//     * @Route("/MessageAdmin/save/{id}")
//     */
//
//    public function SaveMessageAdminAction(Request $request,$id){
//        // on crée un produit
//        $posts=$this->getDoctrine()->getRepository("App:MessageAdmin");
//        $product=$posts->find($id);
//
//        // ensuite on récupère le formulaire
//        $form = $this->createForm(MessageAdminFormType::class, $product);
//        $form->add('submit', SubmitType::class, [
//            'label' => 'sauvegarder',
//            'attr' => ['class' => 'btn btn-default pull-right'],
//        ]);
//        $form->handleRequest($request);
//
//        //si le formulaire a été soumi
//        if ($form->isSubmitted() && $form->isValid()) {
//            //on enregistre le produit dans la bdd
//
//            $reg = $this->getDoctrine()->getManager();
//
//            $reg->persist($product);
//            $reg->flush();
//
//
//            return new Response('Message Ajoutée');
//        }
//        //on va générer le Html
//        $formView= $form->createView();
//
//        // on rend la vue
//        return $this->render('ProductAffiche.html.twig', array('form' => $formView));
//
//    }

    /**
     * @Route("/admin/message/setRead/{id}")
     */
    public function setReadMessageAdminAction(int $id)
    {   $msgDao=$this->getDoctrine()->getRepository(MessageAdmin::class);
        $msgAdmin=$msgDao->find($id);
        if($msgAdmin!=null)
        {   $msgAdmin->setIsread(true);
            $this->addFlash("succes","message marque comme lu");
            $reg = $this->getDoctrine()->getManager();
            $reg->persist($msgAdmin);
            $reg->flush();
        }
        else
        {
            $this->addFlash("error","message inexistant");
            return $this->redirect("/admin/message/list");
        }
        return $this->redirect("/admin/message/list");
    }
    /**
     * @Route("/admin/message/delete/{id}")
     */

    public function DeleteMessageAdminAction(int $id){
        $msgAdminDao=$this->getDoctrine()->getRepository(MessageAdmin::class);
        $msgAdmin=$msgAdminDao->find($id);
        if($msgAdmin!=null) {
            $reg = $this->getDoctrine()->getManager();
            $reg->remove($msgAdmin);
            $reg->flush();
            $this->addFlash("success","message supprimé");

        }
        else{
            $this->addFlash("error","ce message n'existe pas");
        }
        return $this->redirect("/admin/message/list");
    }



}