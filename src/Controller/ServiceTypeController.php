<?php


namespace App\Controller;


use App\Entity\ServiceType;
use App\Form\ProductForm;
use App\Form\ServiceTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceTypeController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/ServiceType")
     */
    public function addServiceTypeAction(Request $request)
    {
        // on crée un type de service
        $serviceType = new ServiceType();

        // ensuite on récupère le formulaire
        $form = $this->createForm(ServiceTypeForm::class, $serviceType);
        $form->add('submit', SubmitType::class, [
            'label' => 'ajouter',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($serviceType);
            $reg->flush();


            return new Response('Produit Ajouter');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }
    /**
     * @Route("/ServiceType/read/all")
     */
    public function readTypeServiceAction(){
        $posts=$this->getDoctrine()->getRepository("App:ServiceType");
        $post=$posts->findAll();

        return $this->render('TypeProductAfficheList.html.twig',array("list"=>$post));

    }

    /**
     * @Route("ServiceType/read/{id}")
     */
    public function readOneServiceTypeAction($id)
    {
        $posts = $this->getDoctrine()->getRepository("App:ServiceType");
        $post = $posts->find($id);

        return $this->render('ProductAfficheOneType.html.twig', array('productType' => $post));


    }
    /**
     * @Route("/ServiceType/save/{id}")
     */

    public function SaveServiceTypeAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("App:ServiceType");
        $serviceType=$posts->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ServiceTypeForm::class, $serviceType);
        $form->add('submit', SubmitType::class, [
            'label' => 'sauvegarder',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($serviceType);
            $reg->flush();


            return new Response('Produit Ajouter');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }
    /**
     * @Route("/ServiceType/delete/{id}")
     */

    public function DeleteServiceTypeAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("App:ServiceType");
        $serviceType=$posts->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ServiceTypeForm::class, $serviceType);
        $form->add('submit', SubmitType::class, [
            'label' => 'supprimer',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->remove($serviceType);
            $reg->flush();


            return new Response('Produit effacée');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }



}