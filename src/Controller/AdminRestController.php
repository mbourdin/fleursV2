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

use App\Entity\Offer;
class AdminRestController extends Controller
{   private $serializer;
    /**
     * AdminRestController constructor.
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
        return new Response($this->serializer->serialize($type,"json"));
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
        return new Response($typeId);
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
    /**
     * @Rest\Put("/admin/offer/associateProduct/{offerId}/{productId}/{quantity}")
     */
    public function associateOfferProduct(int $productId,int $offerId,int $quantity)
    {   $productDao=$this->getDoctrine()->getRepository(Product::class);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $product=$productDao->find($productId);
        $offer=$offerDao->find($offerId);
        $offer->addProduct($product,$quantity);
        $em=$this->getDoctrine()->getManager();
        $em->merge($offer);
        $em->flush();
        return new Response($this->serializer->serialize($product,"json"));
    }
    /**
     * @Rest\Put("/admin/offer/dissociateProduct/{offerId}/{productId}")
     */
    public function dissociateOfferProduct(int $offerId,int $productId)
    {   $productDao=$this->getDoctrine()->getRepository(Product::class);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($offerId);
        $product=$productDao->find($productId);
        $offer->removeProduct($product);
        $em=$this->getDoctrine()->getManager();
        $em->merge($offer);
        $em->flush();
        return new Response($product->getId());
    }

}