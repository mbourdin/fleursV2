<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\ProductType;
use App\Form\ProductForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductTypeForm;

class ProductTypeController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/ProductType")
     */
    public function addProductTypeAction(Request $request)
    {
        // on crée un Type produit
        $productType = new ProductType();

        // ensuite on récupère le formulaire
        $form = $this->createForm(ProductTypeForm::class, $productType);
        $form->add('submit', SubmitType::class, [
            'label' => 'ajouter',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($productType);
            $reg->flush();


            return new Response('Produit Ajouter');
        }
        //on va générer le Html
        $formView = $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));


    }

    /**
     * @Route("/ProductType/read/all")
     */
    public function readTypeProductAction()
    {
        $posts = $this->getDoctrine()->getRepository("ProductType.php");
        $post = $posts->findAll();

        return $this->render('TypeProductAfficheList.html.twig', array("list" => $post));

    }

    /**
     * @Route("ProductType/read/{id}")
     */
    public function readOneProductTypeAction($id)
    {
        $posts = $this->getDoctrine()->getRepository("ProductType.php");
        $post = $posts->find($id);

        return $this->render('ProductAfficheOneType.html.twig', array('productType' => $post));


    }
    /**
     * @Route("/ProductType/save/{id}")
     */

    public function SaveProductAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("ProductType.php");
        $productType=$posts->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ProductTypeForm::class, $productType);
        $form->add('submit', SubmitType::class, [
            'label' => 'sauvegarder',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($productType);
            $reg->flush();


            return new Response('Produit Ajouter');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }
    /**
     * @Route("/ProductType/delete/{id}")
     */

    public function DeleteProductTypeAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("ProductType.php");
        $productType=$posts->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ProductTypeForm::class, $productType);
        $form->add('submit', SubmitType::class, [
            'label' => 'supprimer',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->remove($productType);
            $reg->flush();


            return new Response('Produit effacée');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }


}

