<?php


namespace App\Controller;
use App\Entity\Person;
use App\Entity\ProductType;
use App\Entity\Product;
use App\Entity\Sale;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdminRestController extends Controller
{   private $serializer;
    /**
     * AdminRestController constructor.
     */
    public function __construct()
    {   $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }



    // L'admin ne doit PAS pouvoir modifier ses droits
    private function isMyself(Person $user,Request $request)
    {   $sessionUser=$request->getSession()->get("user");
        return $sessionUser->equals($user);
    }

    /**
     * @Rest\Put("/admin/ban/{id}")
     */
    public function ban(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setBanned(true);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/unban/{id}")
     */
    public function unban(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setBanned(false);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/delete/{id}")
     */
    public function delete(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setDeleted(true);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/undelete/{id}")
     */
    public function unDelete(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setDeleted(false);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }

    /**
     * @Rest\Put("/admin/setUserRights/{id}/{rights}")
     */
    public function setUserRights(int $id,int $rights,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setRights($rights);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/product/associateType/{productId}/{typeId}")
     */
    public function associateProductType(int $productId,int $typeId)
    {   $productDao=$this->getDoctrine()->getRepository(Product::class);
        $typeDao=$this->getDoctrine()->getRepository(ProductType::class);
        $product=$productDao->find($productId);
        $type=$typeDao->find($typeId);
        $product->addType($type);
        $em=$this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/product/dissociateType/{productId}/{typeId}")
     */
    public function dissociateProductType(int $productId,int $typeId)
    {   $productDao=$this->getDoctrine()->getRepository(Product::class);
        $typeDao=$this->getDoctrine()->getRepository(ProductType::class);
        $product=$productDao->find($productId);
        $type=$typeDao->find($typeId);
        $product->removeType($type);
        $em=$this->getDoctrine()->getManager();
        $em->merge($product);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Get("/admin/sales/list/{day}")
     * @param int day difference en jours avec la date actuelle.
     */
    public function getSales(int $day)
    {   $dao=$this->getDoctrine()->getRepository(Sale::class);
        $now=new DateTimeImmutable();
        $sales=$dao->findBy(["date"=>$now->modify("+".$day." day" )]);
        $json_sales=$this->serializer->serialize($sales,"json");
        $response =new Response($json_sales);
        return $response;

    }
}