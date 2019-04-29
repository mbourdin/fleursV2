<?php
namespace App\Controller;
use App\Entity\Person;
use App\Entity\Sale;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Offer;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class PanierController extends Controller
{
    /**
     * @Route("/panier/show",name="panier")
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

        if($sale->isEmpty())
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
    {   $sale=$this->sanitizeSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $sale->addProduct($product,$quantity);
        $this->sendCookie($sale);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/addService/{id}/{quantity}"))
     */
    public function addService(Request $request,int $id,int $quantity)
    {   $sale=$this->sanitizeSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $sale->addService($service,$quantity);
        $this->sendCookie($sale);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/addOffer/{id}/{quantity}"))
     */
    public function addOffer(Request $request,int $id,int $quantity)
    {   $sale=$this->sanitizeSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $sale->addOffer($offer,$quantity);
        $this->sendCookie($sale);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/rmProduct/{id}"))
     */
    public function rmProduct(Request $request,int $id)
{   $sale=$this->sanitizeSale($request);
    foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
        if ($productContent->getProduct()->getId()==$id)
        {   $tmp=$productContent->getQuantity()-1;
            if($tmp<=0) return $this->delProduct($request,$id);
            $productContent->setQuantity($tmp);
            $this->sendCookie($sale);
            return $this->redirect("/panier/show");
        }
    }
    return $this->redirect("/panier/show");
}
    /**
     * @Route("/panier/rmService/{id}"))
     */
    public function rmService(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            if ($serviceContent->getService()->getId()==$id)
            {   $tmp=$serviceContent->getQuantity()-1;
                if($tmp<=0) return $this->delService($request,$id);
                $serviceContent->setQuantity($tmp);
                $this->sendCookie($sale);
                return $this->redirect("/panier/show");
            }
        }
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/rmOffer/{id}"))
     */
    public function rmOffer(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            if ($offerContent->getOffer()->getId()==$id)
            {   $tmp=$offerContent->getQuantity()-1;
                if($tmp<=0) return $this->delOffer($request,$id);
                $offerContent->setQuantity($tmp);
                $this->sendCookie($sale);
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
        $sale=$this->sanitizeSale($request);
        $object=$dao->find($id);
        $sale->updateQuantity($object,$quantity);
        $this->sendCookie($sale);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/delProduct/{id}"))
     */
    public function delProduct(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $sale->removeProduct($product);
        $this->sendCookie($sale);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/delService/{id}"))
     */
    public function delService(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $sale->removeService($service);
        $this->sendCookie($sale);
        return $this->redirect("/panier/show");
    }
    /**
     * @Route("/panier/delOffer/{id}"))
     */
    public function delOffer(Request $request,int $id)
    {   $sale=$this->sanitizeSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $sale->removeOffer($offer);
        $this->sendCookie($sale);
        return $this->redirect("/panier/show");
    }

    private function sanitizeSale(Request $request) : Sale
    {
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $cookie=$request->cookies->get("bucket");
        $sale=unserialize($cookie);
        if(!$sale instanceof Sale){throw new \RuntimeException("class invalide");}
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
    private function sendCookie($sale)
    {   $response=new Response();
        $serialSale=serialize($sale);
        $cookie=new Cookie("bucket",$serialSale);
        $response->headers->setCookie($cookie);
        $response->send();

    }
    /**
     * @Route("/panier/clear")+
     *
     */
    public function clearPanier()
    {   $this->sendCookie(new Sale());
        return $this->redirect("/panier/show");
    }
}