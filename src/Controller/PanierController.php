<?php
namespace App\Controller;
use App\Entity\Person;
use App\Entity\Sale;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Offer;
use Doctrine\Common\Collections\ArrayCollection;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
class PanierController extends Controller
{   private $serializer;
    /**
     * PanierController constructor.
     */
    public function __construct()
    {   $encoders = [new JsonEncoder()];
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = [$normalizer];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    private function getSale(Request $request)
    {   //version cooke mais fonctionne mal
//
//        $cookie = $request->cookies->get("bucket");
//        if ($cookie == null) {
//            $sale = new Sale();
//        } else {
//
//
//        $sale = $this->serializer->deserialize($cookie, Sale::class, "json");
//        $sale->setProducts(new ArrayCollection($sale->getProducts()));
//        $sale->setServices(new ArrayCollection($sale->getServices()));
//        $sale->setOffers(new ArrayCollection($sale->getOffers()));
//        }

        //version session
        $sale=$request->getSession()->get("sale");
        if($sale==null) {
            return new Sale();
        }

        //
        return $sale;

    }
    private function savePanier(Sale $sale,Request $request)
    {
        //    version cookie mais fonctionne mal
//        $response=new Response();
//        $sale->setProducts($sale->getProducts()->toArray());
//        $sale->setServices($sale->getServices()->toArray());
//        $sale->setOffers($sale->getOffers()->toArray());
//        $serialSale=$this->serializer->serialize($sale,"json");
//        $cookie=new Cookie("bucket",$serialSale);
//        $response->headers->setCookie($cookie);
//        $response->send();
//        $sale->setProducts(new ArrayCollection($sale->getProducts()));
//        $sale->setServices(new ArrayCollection($sale->getServices()));
//        $sale->setOffers(new ArrayCollection($sale->getOffers()));

        //version session
        $request->getSession()->set("sale",$sale);
    }
    /**
     * @Route("/panier/show",name="panier")
     */
    public function panierShowAction(Request $request)
    {   $sale=$this->getSale($request);
        if ($sale==null) {
            $sale = new Sale();
            $this->savePanier($sale,$request);
        }
        return $this->render("/panier/panier.html.twig",["sale"=>$sale]);
    }
    /**
     * @Route("/user/panier/save",name="savePanier")
     */
    public function savePanierAction(Request $request)
    {   $sale=$this->getSale($request);

        if($sale->empty())
        {
            $this->addFlash("failure","impossible de commander : panier vide");
            return $this->redirect("/panier/show");
        }
        $personDao=$this->getDoctrine()->getRepository(Person::class);
        $person=$personDao->find($request->getSession()->get("user"));
        $sale->setPerson($person);
        $sale->setValidated(false);
        $em=$this->getDoctrine()->getManager();
        $em->persist($sale);
        $em->flush();
        $this->addFlash("success","panier sauvegardé");
        return $this->redirect("/user/sale/edit");
    }

    /**
     * @Route("/panier/addProduct/{id}"))
     */
    public function addToProduct(Request $request,int $id)
    {  return $this->addProduct($request,$id,1);
    }
    /**
     * @Route("/panier/addService/{id}"))
     */
    public function addToService(Request $request,int $id)
    {  return $this->addService($request,$id,1);
    }
    /**
     * @Route("/panier/addOffer/{id}"))
     */
    public function addToOffer(Request $request,int $id)
    {  return $this->addOffer($request,$id,1);
    }
    /**
     * @Route("/panier/addProduct/{id}/{quantity}"))
     */
    public function addProduct(Request $request,int $id,int $quantity)
    {   $sale=$this->getSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $sale->addProduct($product,$quantity);
        $this->savePanier($sale,$request);
        return $this->redirect("/panier/show");
    }

    /**
     * @Route("/panier/addService/{id}/{quantity}"))
     */
    public function addService(Request $request,int $id,int $quantity)
    {   $sale=$this->getSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $sale->addService($service,$quantity);
        $this->savePanier($sale,$request);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/addOffer/{id}/{quantity}"))
     */
    public function addOffer(Request $request,int $id,int $quantity)
    {   $sale=$this->getSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $sale->addOffer($offer,$quantity);
        $this->savePanier($sale,$request);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/rmProduct/{id}"))
     */
    public function rmProduct(Request $request,int $id)
{   $sale=$this->getSale($request);
    foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
        if ($productContent->getProduct()->getId()==$id)
        {   $tmp=$productContent->getQuantity()-1;
            if($tmp<=0) return $this->delProduct($request,$id);
            $productContent->setQuantity($tmp);
            $this->savePanier($sale,$request);
            return $this->redirect("/panier/show");
        }
    }
    return $this->redirect("/panier/show");
}
    /**
     * @Route("/panier/rmService/{id}"))
     */
    public function rmService(Request $request,int $id)
    {   $sale=$this->getSale($request);
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            if ($serviceContent->getService()->getId()==$id)
            {   $tmp=$serviceContent->getQuantity()-1;
                if($tmp<=0) return $this->delService($request,$id);
                $serviceContent->setQuantity($tmp);
                $this->savePanier($sale,$request);
                return $this->redirect("/panier/show");
            }
        }
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/rmOffer/{id}"))
     */
    public function rmOffer(Request $request,int $id)
    {   $sale=$this->getSale($request);
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            if ($offerContent->getOffer()->getId()==$id)
            {   $tmp=$offerContent->getQuantity()-1;
                if($tmp<=0) return $this->delOffer($request,$id);
                $offerContent->setQuantity($tmp);
                $this->savePanier($sale,$request);
                return $this->redirect("/panier/show");
            }
        }
        return $this->redirect("/panier/show");
    }
    /**
     * @Route ("/panier/updateQuantity/{class}/{id}/{quantity}")
     */
    public function updateQuantity(string $class,int $id,int $quantity,Request $request){
        switch ($class) {
            case "product":
                $dao=$this->getDoctrine()->getRepository(Product::class);
                break;
            case "offer":
                $dao=$this->getDoctrine()->getRepository(Offer::class);
                break;
            case "service":
                $dao=$this->getDoctrine()->getRepository(Product::class);
                break;
            default :
                throw new RuntimeException('invalid class value in updateQuantity function');
        }
        $sale=$this->getSale($request);
        $object=$dao->find($id);
        $sale->updateQuantity($object,$quantity);
        $this->savePanier($sale,$request);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/delProduct/{id}"))
     */
    public function delProduct(Request $request,int $id)
    {   $sale=$this->getSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $sale->removeProduct($product);
        $this->savePanier($sale,$request);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/delService/{id}"))
     */
    public function delService(Request $request,int $id)
    {   $sale=$this->getSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $sale->removeService($service);
        $this->savePanier($sale,$request);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/delOffer/{id}"))
     */
    public function delOffer(Request $request,int $id)
    {   $sale=$this->getSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $sale->removeOffer($offer);
        $this->savePanier($sale,$request);
        return $this->redirect("/panier/show");
    }



    /**
     * @Route("/panier/clear")+
     *
     */
    public function clearPanier(Request $request)
    {   $this->savePanier(new Sale(),$request);
        return $this->redirect("/panier/show");
    }
}