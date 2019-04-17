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
        $cookie=$request->cookies->get('bucket');
        $sale=new Entity\Sale();

        if($cookie!=null)
        {   $sale=unserialize($cookie);
            $request->cookies->remove("bucket");
            $product=$sale->getProducts()->first()->getProduct();
        }
        else{
            $sale->setId(0);
            $product=new Entity\Product();
            $product->setId(1);
            $product->setNom("produit1");
        }
        $sale->add($product,1);
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

}