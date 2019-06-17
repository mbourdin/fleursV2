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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Service;
use \DateTime;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/user/sale")
 */
class SaleController extends Controller
{    private $serializer;
    /**
     * SaleController constructor.
     */
    public function __construct()
    {   $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = [$normalizer];
        $encoders = [new JsonEncoder()];
        $this->serializer = new Serializer($normalizers, $encoders);

    }
    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/show/{id}")
     */
    public function showAction(Request $request,int $id)
    {   $sale=$this->getDoctrine()->getRepository(Sale::class)->find($id);
        if($sale==null)
        {   $this->addFlash("error","Id de commande inexistant");
            return $this->redirect("/sale/list");
        }
        if($sale->getPerson()->getId()!=$request->getSession()->get("user")->getId())
        {   $this->addFlash("error","Cette commande de vous appartient pas!");
            return $this->redirect("/sale/list");
        }
        return $this->render("sale/show.html.twig",["sale"=>$sale,"title"=>"Detail de la commande".$sale->getId()]);
    }
    /**
     * @param Request $request
     * @return Sale|null
     */
    private function getUserSale(Request $request):?Sale
    {   $user=$request->getSession()->get("user");
        $saleDao=$this->getDoctrine()->getRepository(Sale::class);
        $criteria = array('person' => $user,'validated'=>false);
        $sale=$saleDao->findOneBy($criteria);
        return $sale;
    }

    /**
     * @param Sale $sale
     */
    private function saveUserSale(Sale $sale)
    {   $em=$this->getDoctrine()->getManager();
        $em->persist($sale);
        $em->flush();
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/edit")
     */
    public function editSaleAction(Request $request)
    {   $sale=$this->getUserSale($request);
        if($sale!=null)
        {   $now=new \DateTimeImmutable();
            $min=$now->modify("+2 day");
            $max=$now->modify("+20 day");
            $user=$request->getSession()->get("user");
            $user=$this->getDoctrine()->getRepository(Person::class)->find($user->getId());
            return $this->render("/sale/sale.html.twig",["sale"=>$sale,"minDate"=>$min->format("Y-m-d"),"maxDate"=>$max->format("Y-m-d"),"hasOwnAddress"=>$user->getAddress()!=null,"hasPreviousAddresses"=>count($user->getAddresses())!=0]);
        }
        else
        {   $this->addFlash("error","pas de commande non validée trouvée");
            return $this->redirect("/");

        }

    }
    // Toutes les réponses rest auront le même format : [id,prix_unitaire,quantité]
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/addProduct/{id}")
     */
    public function addToProductAction(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $content=$sale->addProduct($product,1);
        $sale->price();
        $jsonContent=$this->serializer->serialize($content,"json");
        $this->saveUserSale($sale);
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/addOffer/{id}")
     */
    public function addToOfferAction(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $content=$sale->addOffer($offer,1);
        $sale->price();
        $this->saveUserSale($sale);
        $jsonContent=$this->serializer->serialize($content,"json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/addService/{id}")
     */
    public function addToServiceAction(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $content=$sale->addService($service,1);
        $sale->price();
        $this->saveUserSale($sale);
        $jsonContent=$this->serializer->serialize($content,"json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/rmProduct/{id}"))
     */
    public function rmProduct(Request $request,int $id)
    {
        $sale = $this->getUserSale($request);
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            if ($productContent->getProduct()->getId() == $id) {
                $tmp = $productContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delProduct($request, $id);
                $productContent->setQuantity($tmp);
                $sale->price();
                $this->saveUserSale($sale);
                $jsonContent=$this->serializer->serialize($productContent,"json");
                return new Response($jsonContent);
            }
        }
        return new Response(null);

    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/rmService/{id}"))
     */
    public function rmService(Request $request,int $id)
    {
        $sale = $this->getUserSale($request);
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            if ($serviceContent->getService()->getId() == $id) {
                $tmp = $serviceContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delService($request, $id);
                $serviceContent->setQuantity($tmp);
                $sale->price();
                $this->saveUserSale($sale);
                $jsonContent=$this->serializer->serialize($serviceContent,"json");
                return new Response($jsonContent);
            }
        }
        return new Response(null);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/rmOffer/{id}"))
     */
    public function rmOffer(Request $request,int $id)
    {
        $sale = $this->getUserSale($request);
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            if ($offerContent->getOffer()->getId() == $id) {
                $tmp = $offerContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delOffer($request, $id);
                $offerContent->setQuantity($tmp);
                $sale->price();
                $this->saveUserSale($sale);
                $jsonContent=$this->serializer->serialize($offerContent,"json");
                return new Response($jsonContent);
            }
        }
        return new Response(null);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Delete("/delProduct/{id}"))
     */
    public function delProduct(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($id);
        $em=$this->getDoctrine()->getManager();
        $jsonContent=null;
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            if($productContent->getProduct()->equals($product)) {
                $em->remove($productContent);
                $productContent->forceSetToZero();
                $sale->price();
                $jsonContent=$this->serializer->serialize($productContent,"json");
                break;
            }
        }
        $sale->removeProduct($product);
        if($sale->empty())
        {   $em->remove($sale);
            $em->flush();
        }
        else{
            $this->saveUserSale($sale);
        }
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Delete("/delService/{id}"))
     */
    public function delService(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($id);
        $em=$this->getDoctrine()->getManager();
        $jsonContent=null;
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            if($serviceContent->getService()->equals($service)) {
                $em->remove($serviceContent);
                $serviceContent->forceSetToZero();
                $sale->price();
                $jsonContent=$this->serializer->serialize($serviceContent,"json");
                break;
            }
        }
        $sale->removeService($service);
        if($sale->empty())
        {   $em->remove($sale);
            $em->flush();
        }
        else{
            $this->saveUserSale($sale);
        }
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Delete("/delOffer/{id}"))
     */
    public function delOffer(Request $request,int $id)
    {   $sale=$this->getUserSale($request);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($id);
        $em=$this->getDoctrine()->getManager();
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            if($offerContent->getOffer()->equals($offer)) {
                $em->remove($offerContent);
                $offerContent->forceSetToZero();
                $sale->price();
                $jsonContent=$this->serializer->serialize($offerContent,"json");
                break;
            }
        }
        $sale->removeOffer($offer);
        if($sale->empty())
        {   $em->remove($sale);
            $em->flush();
        }
        else{
            $this->saveUserSale($sale);
        }
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/validate")
     */
    public function validateAction(Request $request)
    {   $sale=$this->getUserSale($request);
        $addressChoice=$request->request->get("addressChoice");
        $this->addFlash("error",$addressChoice."");
        switch($addressChoice)
        {
            case "own":   //own
                //$this->addFlash("error","own");
                $address=$sale->getPerson()->getAddress();
                if($address==null)
                {   $this->addFlash("error","vous n'avez pas enregistré votre propre adresse");
                    return $this->redirect("/user/sale/edit");
                }
                break;
            case "used":   //used
                //$this->addFlash("error","used");
                $addressId=$request->request->get("addressId");
                $address=$this->getDoctrine()->getRepository(Address::class)->find($addressId);
                if($address==null)
                {   $this->addFlash("error","vous n'avez pas choisi une adresse correcte");
                    return $this->redirect("/user/sale/edit");
                }
                break;
            case "new":   //new
               //$this->addFlash("error","new");
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
     * @param string $class
     * @param int $id
     * @param Request $request
     * @param int $quantity
     * @return Response
     * @Rest\Put ("/updateQuantity/{class}/{id}/{quantity}")
     */
    public function updateQuantity(string $class,int $id,Request $request,int $quantity){
        switch ($class) {
            case "product":
                $dao=$this->getDoctrine()->getRepository(Product::class);
                break;
            case "offer":
                $dao=$this->getDoctrine()->getRepository(Offer::class);
                break;
            case "service":
                $dao=$this->getDoctrine()->getRepository(Service::class);
                break;
            default :
                throw new RuntimeException('invalid class value in updateQuantity function');
        }
        $sale=$this->getUserSale($request);
        $object=$dao->find($id);
        $content=$sale->updateQuantity($object,$quantity);
        $this->saveUserSale($sale);
        $sale->price();
        $jsonContent=$this->serializer->serialize($content,"json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @return Response
     * @Route("/list")
     */
    public function listUserSales(Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Sale::class);
        $user=$request->getSession()->get("user");
        $sales=$dao->findBy(["person"=>$user]);
        return $this->render("sale/listClientView.html.twig",["sales"=>$sales]);
    }
}