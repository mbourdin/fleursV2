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
    {   $cookie=$request->cookies->get("bucket");
        $userDao=$this->getDoctrine()->getRepository(Entity\Person::class);
        $sessionUser=$request->getSession()->get("user");
        if($sessionUser!=null){
            $user=$userDao->find($sessionUser->getId());
        }
        else {$user=null;}
        $request->getSession()->set("user",$user);
        $sale=new Entity\Sale();
        if($cookie!=null )
        {   $sale=unserialize($cookie);
        }

        $sale->setId(null);
        $productDao=$this->getDoctrine()->getRepository(Entity\Product::class);
        $product=$productDao->find(1);
        $sale->add($product,3);
        $serviceDao=$this->getDoctrine()->getRepository(Entity\Service::class);
        $service=$serviceDao->find(1);
        $sale->add($service,2);
        $offerDao=$this->getDoctrine()->getRepository(Entity\Offer::class);
        $offer=$offerDao->find(1);
        $sale->add($offer,4);
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
     * @Route("/testAdressForm")
     */
    public function adressFormAction()
    {
        return $this->render("address/form.html.twig");
    }
}