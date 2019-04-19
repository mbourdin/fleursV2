<?php
namespace App\Controller;
use App\Entity\Product;
use App\Form\ProductForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;

class AdminController extends Controller
{   // Section City


    /**
     * @Route("/admin/city", name="city")
     */
    public function cityAction(Request $request)
    {   $dao=$this->getDoctrine()->getRepository('App:City');
        $city =new City();

        $cities =$dao->findAll();
        /*
        $form=$this->get('form.factory')->create(CityType::class,$city,['action_origin_is_admin'=>true]);
        //Si POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();
            return $this->redirect("/");

        }
        */
        //Si GET
        return $this->render("admin/city.html.twig",["cities"=>$cities]);
    }

    // Section Produits et Types produits

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
        $form = $this->createForm(ProductForm::class, $product,["create"=>true]);
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


            return new Response('votre produit a bien été ajouté');
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('product/ProductAffiche.html.twig', array('form' => $formView));

    }
}