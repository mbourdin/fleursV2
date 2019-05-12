<?php
namespace App\Controller;
use App\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class TestController extends Controller
{
    private $serializer;
    /**
     * TestController constructor.
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
    /**
     * @Route("/test",name="test")
     */
    public function testAction(Request $request)
    {
        $userDao=$this->getDoctrine()->getRepository(Entity\Person::class);
        $sessionUser=$request->getSession()->get("user");
        if($sessionUser!=null){
            $user=$userDao->find($sessionUser->getId());
        }
        else {$user=null;}
        $request->getSession()->set("user",$user);
        $sale=$request->getSession()->get("sale");
        if($sale==null)
        {
            $sale=new Entity\Sale();

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
        //pour rappel la liste des parametres du constructeur de cookies :
        //string nom, object contenu, int heure d'expiration,string path ,string domain,bool secure,bool httponly
        $sessionUser=$request->getSession()->set("sale",$sale);
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