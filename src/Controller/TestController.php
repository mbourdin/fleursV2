<?php
namespace App\Controller;
use App\Entity;
use App\Entity\City;
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
        $request->getSession()->set("user",$user);
        return $this->render("/testing/test.html.twig");
    }
    /**
     * @Route("/testAdressForm")
     */
    public function adressFormAction()
    {
        return $this->render("address/form.html.twig");
    }
    /**
     * @Route("/testAdminHack")
     */
    public function testAdminHackAction()
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $cities =$dao->findAll();
        return $this->render('admin/city.html.twig',["cities"=>$cities]);
    }
    /**
     * @Route("testProductsByType/{type}")
     */
    public function productsByType(string $type)
    {
        $productTypeDao=$this->getDoctrine()->getRepository(
            e::class);
        $products=$productTypeDao->findOneByNameLikeAndActiveTrue($type)->getProducts();
        return $this->render("product/listClientView.html.twig",["products"=>$products]);
    }
}