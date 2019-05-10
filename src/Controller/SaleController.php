<?php
namespace App\Controller;
use App\Entity\Address;
use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\Offer;
use App\Entity\Person;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Service;
use \DateTime;
use \DateTimeImmutable;
/**
 * @Route("/user/sale")
 */
class SaleController extends Controller
{
    private function getUserSale(Request $request):?Sale
    {   $user=$request->getSession()->get("user");
        $saleDao=$this->getDoctrine()->getRepository(Sale::class);
        $criteria = array('person' => $user,'validated'=>false);
        $sale=$saleDao->findOneBy($criteria);
        return $sale;
    }
    private function saveUserSale(Sale $sale)
    {   $em=$this->getDoctrine()->getManager();
        $em->persist($sale);
        $em->flush();
    }
    /**
    * @Route("/edit")
    */
    public function editSaleAction(Request $request)
    {   $sale=$this->getUserSale($request);
        if($sale!=null)
        {   $now=new \DateTimeImmutable();
            $max=$now->modify("+20 day");
            return $this->render("/sale/sale.html.twig",["sale"=>$sale,"minDate"=>$now->format("Y-m-d"),"maxDate"=>$max->format("Y-m-d")]);
        }
        else
        {   $this->addFlash("error","pas de commande non validée trouvée");
            return $this->redirect("/");

        }

    }

    /**
     * @Route("/addProduct/{id}")
     */
    public function addToProductAction(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $sale->addProduct($product,1);
        $this->saveUserSale($sale);
        return $this->redirect("/user/sale/edit");
    }
    /**
     * @Route("/addOffer/{id}")
     */
    public function addToOfferAction(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $sale->addOffer($offer,1);
        $this->saveUserSale($sale);
        return $this->redirect("/user/sale/edit");
    }

    /**
     * @Route("/addService/{id}")
     */
    public function addToServiceAction(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $sale->addService($service,1);
        $this->saveUserSale($sale);
        return $this->redirect("/user/sale/edit");
    }

    /**
     * @Route("/rmProduct/{id}"))
     */
    public function rmProduct(Request $request,int $id)
    {
        $sale = $this->getUserSale($request);
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            if ($productContent->getProduct()->getId() == $id) {
                $tmp = $productContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delProduct($request, $id);
                $productContent->setQuantity($tmp);
                $this->saveUserSale($sale);
                return $this->redirect("/user/sale/edit");
            }
        }
        return $this->redirect("/user/sale/edit");
    }
    /**
     * @Route("/rmService/{id}"))
     */
    public function rmService(Request $request,int $id)
    {
        $sale = $this->getUserSale($request);
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            if ($serviceContent->getService()->getId() == $id) {
                $tmp = $serviceContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delService($request, $id);
                $serviceContent->setQuantity($tmp);
                $this->saveUserSale($sale);
                return $this->redirect("/user/sale/edit");
            }
        }
        return $this->redirect("/user/sale/edit");
    }

    /**
     * @Route("/rmOffer/{id}"))
     */
    public function rmOffer(Request $request,int $id)
    {
        $sale = $this->getUserSale($request);
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            if ($offerContent->getOffer()->getId() == $id) {
                $tmp = $offerContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delOffer($request, $id);
                $offerContent->setQuantity($tmp);
                $this->saveUserSale($sale);
                return $this->redirect("/user/sale/edit");
            }
        }
        return $this->redirect("/user/sale/edit");
    }
    /**
     * @Route("/delProduct/{id}"))
     */
    public function delProduct(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $em=$this->getDoctrine()->getManager();
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            if($productContent->getProduct()->equals($product)) {
                $em->remove($productContent);
                break;
            }
        }
        $sale->removeProduct($product);
        if($sale->isEmpty())
        {   $em->remove($sale);
            $em->flush();
        }
        else{
            $this->saveUserSale($sale);
        }
        return $this->redirect("/user/sale/edit");
    }
    /**
     * @Route("/delService/{id}"))
     */
    public function delService(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $em=$this->getDoctrine()->getManager();
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            if($serviceContent->getService()->equals($service)) {
                $em->remove($serviceContent);
                break;
            }
        }
        $sale->removeService($service);
        if($sale->isEmpty())
        {   $em->remove($sale);
            $em->flush();
        }
        else{
            $this->saveUserSale($sale);
        }
        return $this->redirect("/user/sale/edit");
    }
    /**
     * @Route("/delOffer/{id}"))
     */
    public function delOffer(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $em=$this->getDoctrine()->getManager();
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            if($offerContent->getOffer()->equals($offer)) {
                $em->remove($offerContent);
                break;
            }
        }
        $sale->removeOffer($offer);
        if($sale->isEmpty())
        {   $em->remove($sale);
            $em->flush();
        }
        else{
            $this->saveUserSale($sale);
        }
        return $this->redirect("/user/sale/edit");
    }
    /**
     * @Route("/validate")
     */
    public function validateAction(Request $request)
    {   $sale=$this->getUserSale($request);
        $addressChoice=$request->request->get("addressChoice");
        $this->addFlash("error",$addressChoice."");
        switch($addressChoice)
        {
            case "own":   //own
                $this->addFlash("error","own");
                $address=$sale->getPerson()->getAddress();
                if($address==null)
                {   $this->addFlash("error","vous n'avez pas enregistré votre propre adresse");
                    return $this->redirect("/user/sale/edit");

                }
                break;
            case "used":   //used
                $this->addFlash("error","used");
                $addressId=$request->request->get("addressId");
                $address=$this->getDoctrine()->getRepository(Address::class)->find($addressId);
                if($address==null)
                {   $this->addFlash("error","vous n'avez pas choisi une adresse correcte");
                    return $this->redirect("/user/sale/edit");
                }
                break;
            case "new":   //new
               $this->addFlash("error","new");
                $address = new Address;
                $address->setNumber($request->request->get("number"));
                $address->setRoadname($request->request->get("roadname"));
                $address->setRoadtype($request->request->get("roadtype"));
                $address->setAdditionaladdress($request->request->get("additionaladress"));
                $address->setPostalcode($request->request->get("postalcode"));
                $address->setCityId($request->request->get("inseeid"));


                $em=$this->getDoctrine()->getManager();
                $em->persist($address);
                $em->flush();
                $user=$request->getSession()->get("user");
                $user=$this->getDoctrine()->getRepository(Person::class)->find($user->getId());
                $user->addAddress($address);
                $em->merge($user);
                break;
            default :
                throw new \InvalidArgumentException("choix d'addresse invalide");
        }
        $sale->setContact($request->request->get("contact"));
        $sale->setAddress($address);
        $sale->setValidated(true);
        $sale->setRecipient($request->request->get("recipient"));
        $date=new DateTime($request->request->get("date"));
        $now=new DateTime();
        if($date<$now->modify("+1 day"))
        {   throw new \InvalidArgumentException("date de livraison passée ou trop proche");
        }
        $sale->setDate($date);
        $this->saveUserSale($sale);
        $this->addFlash("success","commande validée");
        return $this->redirect("/");
    }
    /**
     * @Route("/delete")
     */
    public function deleteAction(Request $request)
    {   $sale=$this->getUserSale($request);
        $em=$this->getDoctrine()->getManager();
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            $em->remove($productContent);
        }
        foreach ($sale->getOffers()->getIterator() as $i => $productContent) {
            $em->remove($productContent);
        }
        foreach ($sale->getServices()->getIterator() as $i => $productContent) {
            $em->remove($productContent);
        }
        $em->remove($sale);
        $em->flush();
        $this->addFlash("success","commande annulée");
        return $this->redirect("/");
    }

    /**
     * @Route ("/updateQuantity/{class}/{id}")
     */
    public function updateQuantity(string $class,int $id,Request $request){
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
        $quantity=$request->request->get("quantity");
        $sale=$this->getUserSale($request);
        $object=$dao->find($id);
        $sale->updateQuantity($object,$quantity);
        $this->saveUserSale($sale);
        return $this->redirect("/user/sale/edit");
    }
}