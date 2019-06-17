<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Sale;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Offer;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use FOS\RestBundle\Controller\Annotations as Rest;

class PanierController extends Controller
{
    private $serializer;
    /**
     * PanierController constructor.
     */
    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = [$normalizer];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param Request $request
     * @return Sale|mixed
     */
    private function getSale(Request $request)
    {
        $sale = $request->getSession()->get("sale");
        if ($sale == null) {
            return new Sale();
        }
        return $sale;
    }

    /**
     * @param Sale $sale
     * @param Request $request
     */
    private function savePanier(Sale $sale, Request $request)
    {
        $request->getSession()->set("sale", $sale);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/panier/show",name="panier")
     */
    public function panierShowAction(Request $request)
    {
        $sale = $this->getSale($request);
        if ($sale == null) {
            $sale = new Sale();
            $this->savePanier($sale, $request);
        }
        return $this->render("/panier/panier.html.twig", ["sale" => $sale]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/user/panier/save",name="savePanier")
     */
    public function savePanierAction(Request $request)
    {
        $sale = $this->getSale($request);

        if ($sale->empty()) {
            $this->addFlash("failure", "impossible de commander : panier vide");
            return $this->redirect("/panier/show");
        }
        $personDao = $this->getDoctrine()->getRepository(Person::class);
        $person = $personDao->find($request->getSession()->get("user"));
        $sale->setPerson($person);
        $sale->setValidated(false);
        $saleDao = $this->getDoctrine()->getRepository(Sale::class);
        $criteria = array('person' => $person, 'validated' => false);
        $oldSale = $saleDao->findOneBy($criteria);
        $em = $this->getDoctrine()->getManager();
        if ($oldSale != null) {
            $em->remove($oldSale);
            $em->flush();
        }
        $em->merge($sale);
        $em->flush();
        $this->addFlash("success", "panier sauvegardé");
        $this->clearPanier($request);
        return $this->redirect("/user/sale/edit");
    }
    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/panier/addProduct/{id}")
     */
    public function addToProduct(Request $request, int $id)
    {
        $this->addProduct($request, $id);
        return $this->redirect('/panier/show');
    }
    /**
     * @param Request $request
     * @param int $id
     * @Rest\Put("/panier/rest/addProduct/{id}")
     */
    public function addToProductRest(Request $request, int $id)
    {
        $content = $this->addProduct($request, $id);
        $jsonContent = $this->serializer->serialize($content, "json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/panier/addService/{id}"))
     */
    public function addToService(Request $request, int $id)
    {
        $this->addService($request, $id);
        return $this->redirect("/panier/show");
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/panier/rest/addService/{id}")
     */
    public function addToServiceRest(Request $request, int $id)
    {
        $content = $this->addService($request, $id);
        $jsonContent = $this->serializer->serialize($content, "json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/panier/addOffer/{id}"))
     */
    public function addToOffer(Request $request, int $id)
    {
        $this->addOffer($request, $id);
        return $this->redirect("/panier/show");
    }
    /**
     * @param Request $request
     * @param int $id
     * @Rest\Put("/panier/rest/addOffer/{id}")
     */
    public function addToOfferRest(Request $request, int $id)
    {
        $content = $this->addOffer($request, $id);
        $jsonContent = $this->serializer->serialize($content, "json");
        return new Response($jsonContent);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \App\Entity\SaleProductContent|mixed
     */
    private function addProduct(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        $productDao = $this->getDoctrine()->getRepository(Product::class);
        $product = $productDao->find($id);
        $content = $sale->addProduct($product, 1);
        $sale->price();
        $this->savePanier($sale, $request);
        return $content;
    }
    /**
     * @param Request $request
     * @param int $id
     * @return \App\Entity\SaleServiceContent|mixed
     */
    private function addService(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        $serviceDao = $this->getDoctrine()->getRepository(Service::class);
        $service = $serviceDao->find($id);
        $content = $sale->addService($service, 1);
        $sale->price();
        $this->savePanier($sale, $request);
        return $content;
    }
    /**
     * @param Request $request
     * @param int $id
     * @return \App\Entity\SaleOfferContent|mixed
     */
    private function addOffer(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        $offerDao = $this->getDoctrine()->getRepository(Offer::class);
        $offer = $offerDao->find($id);
        $content = $sale->addOffer($offer, 1);
        $sale->price();
        $this->savePanier($sale, $request);
        return $content;
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/panier/rest/rmProduct/{id}"))
     */
    public function rmProduct(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        foreach ($sale->getProducts()->getIterator() as $i => $productContent) {
            if ($productContent->getProduct()->getId() == $id) {
                $tmp = $productContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delProduct($request, $id);
                $content = $productContent->setQuantity($tmp);
                $sale->price();
                $this->savePanier($sale, $request);
                $jsonContent = $this->serializer->serialize($content, "json");
                return new Response($jsonContent);
            }
        }
        return new Response(null);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/panier/rest/rmService/{id}"))
     */
    public function rmService(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        foreach ($sale->getServices()->getIterator() as $i => $serviceContent) {
            if ($serviceContent->getService()->getId() == $id) {
                $tmp = $serviceContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delService($request, $id);
                $content = $serviceContent->setQuantity($tmp);
                $sale->price();
                $this->savePanier($sale, $request);
                $jsonContent = $this->serializer->serialize($content, "json");
                return new Response($jsonContent);
            }
        }
        return new Response(null);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Put("/panier/rest/rmOffer/{id}"))
     */
    public function rmOffer(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        foreach ($sale->getOffers()->getIterator() as $i => $offerContent) {
            if ($offerContent->getOffer()->getId() == $id) {
                $tmp = $offerContent->getQuantity() - 1;
                if ($tmp <= 0) return $this->delOffer($request, $id);
                $content = $offerContent->setQuantity($tmp);
                $sale->price();
                $this->savePanier($sale, $request);
                $jsonContent = $this->serializer->serialize($content, "json");
                return new Response($jsonContent);
            }
        }
        return new Response(null);
    }
    /**
     * @param string $class
     * @param int $id
     * @param int $quantity
     * @param Request $request
     * @return Response
     * @Rest\Put ("/panier/updateQuantity/{class}/{id}/{quantity}")
     */
    public function updateQuantity(string $class, int $id, int $quantity, Request $request)
    {
        switch ($class) {
            case "product":
                $dao = $this->getDoctrine()->getRepository(Product::class);
                break;
            case "offer":
                $dao = $this->getDoctrine()->getRepository(Offer::class);
                break;
            case "service":
                $dao = $this->getDoctrine()->getRepository(Service::class);
                break;
            default :
                throw new RuntimeException('invalid class value in updateQuantity function');
                break;
        }
        $sale = $this->getSale($request);
        $object = $dao->find($id);
        $content = $sale->updateQuantity($object, $quantity);
        $sale->price();
        $jsonContent = $this->serializer->serialize($content, "json");
        $this->savePanier($sale, $request);
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Delete("/panier/rest/delProduct/{id}"))
     */
    public function delProduct(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        $productDao = $this->getDoctrine()->getRepository(Product::class);
        $product = $productDao->find($id);
        $content = $sale->removeProduct($product);
        $content->forceSetToZero();
        $sale->price();
        $this->savePanier($sale, $request);
        $jsonContent = $this->serializer->serialize($content, "json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Delete("/panier/rest/delService/{id}"))
     */
    public function delService(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        $serviceDao = $this->getDoctrine()->getRepository(Service::class);
        $service = $serviceDao->find($id);

        $content = $sale->removeService($service);
        $content->forceSetToZero();
        $sale->price();
        $this->savePanier($sale, $request);
        $jsonContent = $this->serializer->serialize($content, "json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Rest\Delete("/panier/rest/delOffer/{id}"))
     */
    public function delOffer(Request $request, int $id)
    {
        $sale = $this->getSale($request);
        $offerDao = $this->getDoctrine()->getRepository(Offer::class);
        $offer = $offerDao->find($id);
        $content = $sale->removeOffer($offer);
        $content->forceSetToZero();
        $sale->price();
        $this->savePanier($sale, $request);
        $jsonContent = $this->serializer->serialize($content, "json");
        return new Response($jsonContent);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/panier/clear")
     */
    public function clearPanier(Request $request)
    {
        $this->savePanier(new Sale(), $request);
        return $this->redirect("/panier/show");
    }
}