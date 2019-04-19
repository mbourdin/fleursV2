<?php


namespace App\Controller;


use App\Entity\Product;
use App\Form\ProductForm;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends Controller
{


    /**
     * @Route("/admin/product/read/all")
     */

    public function readProductAction(){
        $posts=$this->getDoctrine()->getRepository("App:Product");
        $post=$posts->findAll();

        return $this->render('ProductAfficheList.html.twig',array("list"=>$post));
    }


    /**
     * @Route("Product/read/{id}")
     */
    public function readOneProductAction($id){
        $posts=$this->getDoctrine()->getRepository("App:Product");
        $post=$posts->find($id);

        return $this->render('ProductAfficheOne.html.twig', array('product' =>$post));


    }
    /**
     * @Route("/admin/product/edit/{id}")
     */

    public function SaveProductAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("App:Product");
        $product=$posts->find($id);

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


            return new Response('Produit Ajouter');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('product/ProductAffiche.html.twig', array('form' => $formView));

    }
    /**
     * @Route("/Product/delete/{id}")
     */

    public function DeleteProductAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository("App:Product");
        $product=$posts->find($id);

        // ensuite on récupère le formulaire
        $form = $this->createForm(ProductForm::class, $product);

        $form->add('submit', SubmitType::class, [
            'label' => 'supprimer',
            'attr' => ['class' => 'btn btn-default pull-right'],
        ]);
            $form->handleRequest($request);

        //si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->remove($product);
            $reg->flush();


            return new Response('Produit effacée');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('ProductAffiche.html.twig', array('form' => $formView));

    }



//V
//    /**
//     * Created by PhpStorm.
//     * User: JOEL
//     * Date: 27/03/2019
//     * Time: 14:14
//     */
//
//namespace AppBundle\Controller;
//
//
//    use AppBundle\Entity\Produit;
//    use AppBundle\Form\ProduitType;
//
//    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//    use Symfony\Component\HttpFoundation\Request;
//    use Symfony\Component\HttpFoundation\Response;
//    use Symfony\Component\Routing\Annotation\Route;
//
//class ProduitController extends Controller
//{
//    /**
//     * @Route("/add", name="ProductionAdd")
//     */
//
//    //methode qui ajouter un produit
//    public function addAction(Request $request)
//    {
//
//    }




//    /**
//     * @Route("/list", name="ProduitList")
//     *
//     * @return Response
//     */
//
//
//    public function list_proAction()
//    {
//        $depot = $this->getDoctrine()->getRepository('AppBundle:Produit');
//
//        //on recupère la liste des produit
//        $produits = $depot->findAll();
//
//        return $this->render('produitList.html.twig', array('produits' => $produits));
//    }
//
//
//    /**
//     * @Route("/edit/{id}", name="produitEdit")
//     *
//     * @return Response
//     */
//    public function editAction(Request $request, Produit $produit)
//    {
//
//        // ensuite on récupère le formulaire
//        $form = $this->createForm(ProduitType::class, $produit);
//
//        $form->handleRequest($request);
//
//        //si le formulaire a été soumi
//        if ($form->isSubmitted() && $form->isValid()) {
//            //on enregistre le produit dans la bdd
//
//            $reg = $this->getDoctrine()->getManager();
//
//
//            //Cela est unitil puisque l'objet provient de la bdd
//            //$reg->persist($produit);
//            $reg->flush();
//
//
//            return new Response('Produit modifier');
//
//        }
//
//
//        //on va générer le Html
//        $formView = $form->createView();
//
//        // on rend la vue
//        return $this->render('produitAdd.html.twig', array('form' => $formView));
//
//    }
//
//
//    /**
//     * @Route("/del/{id}", name="produitDel")
//     *
//     * @return Response
//     */
//
//    public function deleteAction(Produit $produit)
//    {
//        //on recupere un entity manager
//
//        $em = $this->getDoctrine()->getManager();
//        $em->remove($produit);
//        //pour mettre a jout la base de donner
//        $em->flush();
//
//        return new Response('Produit supprimer de la liste');
//
//
//    }

}
