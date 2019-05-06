<?php
namespace App\Controller;
use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\Offer;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Service;

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
    * @Route("/user/sale/edit")
    */
    public function editSaleAction(Request $request)
    {   $sale=$this->getUserSale($request);
        return $this->render("/sale/sale.html.twig",["sale"=>$sale,]);

    }

    /**
     * @Route("/user/sale/addProduct/{id}")
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
     * @Route("/user/sale/addOffer/{id}")
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
     * @Route("/user/sale/addService/{id}")
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
     * @Route("/user/sale/rmProduct/{id}"))
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
     * @Route("/user/sale/rmService/{id}"))
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
     * @Route("/user/sale/rmOffer/{id}"))
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
     * @Route("/user/sale/delProduct/{id}"))
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
     * @Route("/user/sale/delService/{id}"))
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
     * @Route("/user/sale/delOffer/{id}"))
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
     * @Route("/user/sale/validate")
     */
    public function validateAction(Request $request)
    {   $sale=$this->getUserSale($request);
        $sale->setValidated(true);
        $this->saveUserSale($sale);
        $this->addFlash("success","commande validée");
        return $this->redirect("/");
    }
    /**
     * @Route("/user/sale/delete")
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
     * @Route ("/user/sale/updateQuantity/{class}/{id}")
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