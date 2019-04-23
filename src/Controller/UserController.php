<?php
namespace App\Controller;
use App\Entity\Person;
use App\Entity\Sale;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Offer;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends Controller
{
    /**
     * @Route("/formUser", name="saveuser")
     */
    public function saveUserAction(Request $request)
    {
        $user =new Person();
        $form=$this->get('form.factory')->create(UtilisateurType::class,$user,['action_origin_is_admin'=>true]);
        //Si POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirect("/");
        }
        //Si GET
        return $this->render("testing/formUser.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route ("/loadUser", name="loaduser")
     */
    public function loadUserAction()
    {   $users =$this->getDoctrine()->getRepository(Person::class)->findAll();
        return $this->render('testing/readUser.html.twig',["users"=>$users]);
    }
    /**
     * @Route("/user/panier/show",name="panier")
     */
    public function panierShowAction(Request $request)
    {   $cookie=$request->cookies->get("bucket");
        if ($cookie==null) {
            $sale = new Sale();
            $serialSale=serialize($sale);
            $cookie=new Cookie("bucket",$serialSale);
            $response=new Response();
            $response->headers->setCookie($cookie);
            $response->send();
        }
        else {
            $sale = unserialize($cookie);
        }

        return $this->render("/panier/panier.html.twig",["sale"=>$sale]);
    }
    /**
     * @Route("/user/panier/save",name="savePanier")
     */
    public function savePanierAction(Request $request)
    {   $sale=$this->sanitizeSale($request);

        $em=$this->getDoctrine()->getManager();
        $em->persist($sale);
        $em->flush();
        $this->addFlash("success","panier sauvegardé");
        return $this->redirect("/test/panier/list");
    }

    /**
     * @Route("/user/panier/addProduct/{id}"))
     */
    public function addProduct(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        //$productDao=$this->getDoctrine()->getRepository(Product::class);
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            if ($productContent->getProduct()->getId()==$id)
            {   $productContent->setQuantity($productContent->setQuantity()+1);
                return redirect("/user/panier/show");;
            }
        }
        return $this->redirect("/user/panier/show");
    }

    /**
     * @Route("/user/panier/rmProduct/{id}"))
     */
    public function rmProduct(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        //$productDao=$this->getDoctrine()->getRepository(Product::class);
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            if ($productContent->getProduct()->getId()==$id)
            {   $tmp=$productContent->getQuantity()-1;
                if($tmp<=0) return delProduct($request,$id);
                $productContent->setQuantity($tmp);
                return $this->redirect("/user/panier/show");
            }
        }
        return $this->redirect("/user/panier/show");
    }

    /**
     * @Route("/user/panier/delProduct/{id}"))
     */
    public function delProduct(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $sale->removeProduct($product);
        return $this->redirect("/user/panier/show");
    }

    private function sanitizeSale(Request $request) : Sale
    {
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $cookie=$request->cookies->get("bucket");
        $sale=unserialize($cookie);


        //On empeche l'utilisateur de modifier la BDD en rechargeant les éléments
        $sale->setId(null);
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            $productContent->setProduct($productDao->find($productContent->getProduct()->getId()));
        }
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            $serviceContent->setService($serviceDao->find($serviceContent->getService()->getId()));
        }
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            $offerContent->setOffer($offerDao->find($offerContent->getOffer()->getId()));
        }
        return $sale;
    }
}