<?php


namespace App\Controller;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{   public function searchServices(string $name)
    {   $dao=$this->getDoctrine()->getRepository(Service::class);
        $services=$dao->findByNameLikeAndActiveTrue($name);
        return $services;

    }
    public function searchProducts(string $name)
    {   $dao=$this->getDoctrine()->getRepository(Product::class);
        $products=$dao->findByNameLikeAndActiveTrue($name);
        return $products;
    }
    public function searchOffers(string $name)
    {   $dao=$this->getDoctrine()->getRepository(Offer::class);
        $offers=$dao->findByNameLikeAndActiveTrue($name);
        return $offers;
    }
    /**
     * @Route("/activeProductsList")
     */
    public function showActiveProducts()
    {   $list=$this->searchProducts("");
        return $this->render("product/list.html.twig",["products"=>$list]);
    }
    /**
     * @Route("/activeOffersList")
     */
    public function showActiveOffers()
    {   $list=$this->searchProducts("");
        return $this->render("offer/list.html.twig",["offers"=>$list]);
    }

    /**
     * @Route("/orchid")
     */
    public function orchideesAction()
    {   $list=$this->searchProducts("orchid");
        return $this->render("product/list.html.twig",["products"=>$list]);
    }
    /**
     * @Route("/rip")
     */
    public function ripAction()
    {   $list=$this->searchProducts("orchid");
        return $this->render("product/list.html.twig",["list"=>$list]);
    }
    /**
     * @Route("/search/products/{name}")
     */
    public function spAction(string $name)
    {   $list=$this->searchProducts($name);
        return $this->render("product/list.html.twig",["products"=>$list]);
    }
    /**
     * @Route("/search/services/{name}")
     */
    public function ssAction(string $name)
    {   $list=$this->searchServices($name);
        return $this->render("service/list.html.twig",["services"=>$list]);
    }
    /**
     * @Route("/search/offers/{name}")
     */
    public function soAction(string $name)
    {   $list=$this->searchOffers($name);
        return $this->render("offer/list.html.twig",["offers"=>$list]);
    }
    /**
     * @Route("/search/combo")
     */
    public function comboAction(Request $request)
    {   if($request->getSession()->get("admin")==true)
        {   $view="admin";
        }
        else
        {
            $view="client";
        }
        $name=$request->request->get("name");
        $products=$this->searchProducts($name);
        $services=$this->searchServices($name);
        $offers=$this->searchOffers($name);
        if($view=="admin"){
            return $this->render("/searchResult.html.twig",["offers"=>$offers,"services"=>$services,"products"=>$products]);
        }
        else
        {
            return $this->render("/searchResult.html.twig",["offers"=>$offers,"services"=>$services,"products"=>$products]);
        }
    }

}