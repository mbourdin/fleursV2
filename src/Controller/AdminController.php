<?php
namespace App\Controller;
use App\Entity\Product;
use App\Entity\Producttype;
use App\Form\ProductForm;
use App\Form\ProductTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends Controller
{


    // Section Produits et Types produits

    /**
     * @param Request $request
     * @return Response
     * @Route("/product/add")
     */
    public function addProductAction(Request $request)
    {
        // on crée un produit
        $product = new Product();

        // ensuite on récupère le formulaire
        $form = $this->createForm(ProductForm::class, $product);
        $form->add('submit', SubmitType::class, [
            'label' => 'ajouter',
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
        return $this->render('product/form.html.twig', array('form' => $formView));

    }

    /**
     * @Route("/product/list")
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
     * @Route("/product/edit/{id}")
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
        return $this->render('product/form.html.twig', array('form' => $formView));

    }
//      Cette fonction n'est pas utile à l'application
//    /**
//     * @Route("/product/delete/{id}")
//     */
//
//    public function DeleteProductAction(Request $request,$id){
//        // on crée un produit
//        $posts=$this->getDoctrine()->getRepository("App:Product");
//        $product=$posts->find($id);
//
//        // ensuite on récupère le formulaire
//        $form = $this->createForm(ProductForm::class, $product);
//
//        $form->add('submit', SubmitType::class, [
//            'label' => 'supprimer',
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
//            $reg->remove($product);
//            $reg->flush();
//
//
//            return new Response('Produit effacée');
//        }
//        //on va générer le Html
//        $formView= $form->createView();
//
//        // on rend la vue
//        return $this->render('product/ProductAffiche.html.twig', array('form' => $formView));
//
//    }

    //SECTION TYPE PRODUIT
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/productType/add")
     */
    public function addProductTypeAction(Request $request)
    {
        // on crée un Type produit
        $productType = new Producttype();

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

            $this->addFlash("success","type produit ajouté !".$productType->getName());
            return $this->redirect("/admin/productType/list");
        }
        //on va générer le Html
        $formView = $form->createView();

        // on rend la vue
        return $this->render('product/typeform.html.twig', array('form' => $formView));


    }

    /**
     * @Route("/productType/list")
     */
    public function readProductTypeAction()
    {
        $dao = $this->getDoctrine()->getRepository(Producttype::class);
        $types = $dao->findAll();

        return $this->render('product/typeList.html.twig', array("types" => $types));

    }
    /**
     * @Route("/productType/read/{id}")
     */
    public function readOneProductTypeAction($id)
    {
        $dao = $this->getDoctrine()->getRepository(Producttype::class);
        $pt = $dao->find($id);

        return $this->render('product/typeDetail.twig', array('productType' => $pt));


    }


    /**
     * @Route("/productType/edit/{id}")
     */

    public function SaveProductTypeAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository(Producttype::class);
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

            $this->addFlash("success","type de produit ajouté : ".$productType->getName());
            return $this->redirect("/admin/productType/list");
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('product/typeform.html.twig', array('form' => $formView));

    }

//      Cette fonction n'est pas utilie à l'application
//
//    /**
//     * @Route("/admin/productType/delete/{id}")
//     */
//
//    public function DeleteProductTypeAction(Request $request,$id){
//        // on crée un produit
//        $posts=$this->getDoctrine()->getRepository(Producttype::class);
//        $productType=$posts->find($id);
//
//        // ensuite on récupère le formulaire
//        $form = $this->createForm(ProductTypeForm::class, $productType);
//        $form->add('submit', SubmitType::class, [
//            'label' => 'supprimer',
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
//            $reg->remove($productType);
//            $reg->flush();
//
//
//            return new Response('Produit effacée');
//        }
//        //on va générer le Html
//        $formView= $form->createView();
//
//        // on rend la vue
//        return $this->render('product/typeList.html.twig', array('form' => $formView));
//
//    }


}