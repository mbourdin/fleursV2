<?php
namespace App\Controller;
use App\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;


class TestController extends Controller
{
    /**
     * @Route("/test",name="test")
     */
    public function testAction(Request $request)
    {   $session=$request->getSession();
        $cookie=$request->cookies->get("bucket");
        $sale=new Entity\Sale();

        if($cookie!=null)
        {   $sale=unserialize($cookie);
            $request->cookies->remove("bucket");
        }
        else{
            $sale->setId(0);
        }
        $product=new Entity\Product();
        $product->setId(1);
        $product->setName("produit1");
        $product2=new Entity\Product();
        $product2->setId(2);
        $product2->setName("produit2");
        $sale->add($product2,2);
        $service=new Entity\Service();
        $service->setId(1);
        $service->setName("service1");
        $sale->add($service,3);
        $service2=new Entity\Service();
        $service2->setId(2);
        $service2->setName("service2");
        $sale->add($service2,4);
        $offer=new Entity\Offer();
        $offer->setId(1);
        $offer->setName("offer1");
        $sale->add($offer,1);

        $session->set("sale",$sale);
        $response=new Response();
        $serialSale=serialize($sale);
        //pour rappel la liste des parametres du constructeur de cookies :
        //string nom, object contenu, int heure d'expiration,string path ,string domain,bool secure,bool httponly
        $cookie=new Cookie("bucket",$serialSale);

        $response->headers->setCookie($cookie);
        $response->send();
        return $this->render("/testing/test.html.twig");
    }
    /**
     * @Route("/panier",name="panier")
     */
    public function panierAction(Request $request)
    {   $cookie=$request->cookies->get("bucket");
        $sale=unserialize($cookie);

        return $this->render("/testing/panier.html.twig",["sale"=>$sale]);
    }
}