<?php


namespace App\Controller;

use App\Entity\Product;
use App\Entity\Service;
use App\Form\ProductForm;
use App\Form\ProductTypeForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\ServiceForm;

class ServiceController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/Service")
     */
    public function addServiceAction(Request $request)
    {
        // on crée un service
        $service = new Service();

        // ensuite on récupère le formulaire
        $form = $this->createForm(ServiceForm::class, $service);
        $form->add('submit', SubmitType::class, [
            'label' => 'ajouter',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($service);
            $reg->flush();


            return new Response('Produit Ajouter');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }

    /**
     * @Route("Service/read/all")
     */

    public function readServiceAction(){
        $posts=$this->getDoctrine()->getRepository("App:Service");
        $post=$posts->findAll();

        return $this->render('ProductAfficheList.html.twig',array("list"=>$post));
    }

    /**
     * @Route("Service/read/{id}")
     */
    public function readOneServiceAction($id){
        $posts=$this->getDoctrine()->getRepository("App:Service");
        $post=$posts->find($id);

        return $this->render('ProductAfficheOne.html.twig', array('product' =>$post));


    }
    /**
     * @Route("/Service/save/{id}")
     */

    public function SaveServiceAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("App:Service");
        $service=$posts->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ServiceForm::class, $service);
        $form->add('submit', SubmitType::class, [
            'label' => 'sauvegarder',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($service);
            $reg->flush();


            return new Response('Produit Ajouter');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }
    /**
     * @Route("/Service/delete/{id}")
     */

    public function DeleteServiceAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("App:Service");
        $service=$posts->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ServiceForm::class, $service);
        $form->add('submit', SubmitType::class, [
            'label' => 'supprimer',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->remove($service);
            $reg->flush();


            return new Response('Produit effacée');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }


}