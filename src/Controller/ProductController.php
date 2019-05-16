<?php
namespace App\Controller;
use App\Entity\Offer;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Form\OfferForm;
use App\Form\ProductForm;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ProductController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/admin/product/add")
     */
    public function addProductAction(Request $request)
    {
        // on crée un produit
        $product = new Product();

        // ensuite on récupère le formulaire
        $form = $this->createForm(ProductForm::class, $product);
        $form->add('submit', SubmitType::class, [
            'label' => 'ajouter',
            'attr' => ['class' => 'btn btn-primary pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($product);
            $reg->flush();

            $this->addFlash("success","produit sauvegardé :".$product->getName());
            return $this->redirect("/admin/product/list");

        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('product/form.html.twig', array('form' => $formView));

    }

    /**
     * @Route("/admin/product/list")
     */

    public function readProductAction(){
        $dao=$this->getDoctrine()->getRepository(Product::class);
        $products=$dao->findAll();

        return $this->render('product/list.html.twig',array("products"=>$products));
    }


    /**
     * @Route("/product/read/{id}")
     */
    public function readOneProductAction($id){
        $dao=$this->getDoctrine()->getRepository(Product::class);
        $product=$dao->find($id);

        return $this->render('product/detail.html.twig', array('product' =>$product));


    }
    /**
     * @Route("/admin/product/edit/{id}")
     */

    public function SaveProductAction(Request $request,$id){
        // on crée un produit
        $dao=$this->getDoctrine()->getRepository(Product::class);
        $product=$dao->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ProductForm::class, $product);
        $form->add('submit', SubmitType::class, [
            'label' => 'sauvegarder',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
        $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($product);
            $reg->flush();


            $this->addFlash("success","produit sauvegardé :".$product->getName());

            return $this->redirect("/admin/product/list");

        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        $productTypes=$this->getDoctrine()->getRepository(ProductType::class)->findAll();
        return $this->render('product/form.html.twig', array('form' => $formView,"edit"=>true,"productTypes"=>$productTypes,"product"=>$product));

    }
}