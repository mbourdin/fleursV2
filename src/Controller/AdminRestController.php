<?php


namespace App\Controller;
use App\Entity\OfferProductContent;
use App\Entity\ServiceProductContent;
use App\Entity\Person;
use App\Entity\ProductType;
use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\Service;
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
     * @Rest\Get("/admin/sale/list/{day}")
     * @param int day difference en jours avec la date actuelle.
     */
    public function getSales(int $day)
    {   $dao=$this->getDoctrine()->getRepository(Sale::class);
        $sales=$dao->findAllByDaysFromNow($day);
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
        $content=new OfferProductContent();
        $content->setOffer($offer);
        $content->setProduct($product);
        $content->setQuantity($quantity);
        $em=$this->getDoctrine()->getManager();
        $em->persist($content);
        $em->flush();
        return new Response($this->serializer->serialize($content,"json"));
    }
    /**
     * @Rest\Put("/admin/offer/dissociateProduct/{offerId}/{productId}")
     */
    public function dissociateOfferProduct(int $offerId,int $productId)
    {   $productDao=$this->getDoctrine()->getRepository(Product::class);
        $offerDao=$this->getDoctrine()->getRepository(Offer::class);
        $offer=$offerDao->find($offerId);
        $product=$productDao->find($productId);
        $contentDao=$this->getDoctrine()->getRepository(OfferProductContent::class);
        $content=$contentDao->findOneBy(["product"=>$product,"offer"=>$offer]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($content);
        $em->flush();
        return new Response($productId);
    }

    /**
     * @Rest\Put("/admin/service/associateProduct/{serviceId}/{productId}/{quantity}")
     */
    public function associateServiceProduct(int $productId,int $serviceId,int $quantity)
    {   $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($serviceId);
        $productDao=$this->getDoctrine()->getRepository(Product::class);
        $product=$productDao->find($productId);
        $content=new ServiceProductContent();
        $content->setProduct($product);
        $content->setService($service);
        $content->setQuantity($quantity);
        $em=$this->getDoctrine()->getManager();
        $em->persist($content);
        $em->flush();
        return new Response($this->serializer->serialize($content,"json"));
    }
    /**
     * @Rest\Put("/admin/service/dissociateProduct/{serviceId}/{productId}")
     */
    public function dissociateServiceProduct(int $serviceId,int $productId)
    {   $productDao=$this->getDoctrine()->getRepository(Product::class);
        $serviceDao=$this->getDoctrine()->getRepository(Service::class);
        $service=$serviceDao->find($serviceId);
        $product=$productDao->find($productId);
        $contentDao=$this->getDoctrine()->getRepository(ServiceProductContent::class);
        $content=$contentDao->findOneBy(["product"=>$product,"service"=>$service]);
        $em=$this->getDoctrine()->getManager();
        $em->remove($content);
        $em->flush();
        return new Response($productId);
    }

}