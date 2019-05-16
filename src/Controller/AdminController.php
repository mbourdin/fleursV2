<?php
namespace App\Controller;
use App\Entity\Offer;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\Sale;
use App\Form\ProductForm;
use App\Form\ProductTypeForm;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Person;
use App\Entity\Service;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends Controller
{


    // Section Produits et Types produits



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
        $dao = $this->getDoctrine()->getRepository(ProductType::class);
        $types = $dao->findAll();

        return $this->render('product/typeList.html.twig', array("types" => $types));

    }
    /**
     * @Route("/productType/read/{id}")
     */
    public function readOneProductTypeAction($id)
    {
        $dao = $this->getDoctrine()->getRepository(ProductType::class);
        $pt = $dao->find($id);

        return $this->render('product/typeDetail.twig', array('productType' => $pt));


    }


    /**
     * @Route("/productType/edit/{id}")
     */

    public function SaveProductTypeAction(Request $request,$id){
        // on crée un produit
        $posts=$this->getDoctrine()->getRepository(ProductType::class);
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


    /**
     * @Route("/dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('admin/dashboard.html.twig');
    }


    /**
     * @Route("/product//list")
     */

    public function productListAction(){
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render("product/List.html.twig",["products"=>$products]);
    }
    /**
     * @Route("/service/list")
     */
    public function serviceListAction()
    {
        $dao = $this->getDoctrine()->getRepository(Service::class);
        $result = $dao->findAll();
        return $this->render('service/list.html.twig', array('services' => $result));
    }
    /**
     * @Route("/offer/list")
     */
    public function offerListAction()
    {
        $dao = $this->getDoctrine()->getRepository(Offer::class);
        $result = $dao->findAll();
        return $this->render('offer/list.html.twig', array('offers' => $result));
    }

    /**
     * @Route("/sale/list")
     * @param int $day
     * @throws \Exception
     */
    public function getSales()
    {   $dao=$this->getDoctrine()->getRepository(Sale::class);
        $now=new DateTimeImmutable();
        $sales=$dao->findAllByDaysFromNow(-1,5);

        //$sales=$dao->findAll();
        return $this->render("sale/list.html.twig",["sales"=>$sales]);
    }

}