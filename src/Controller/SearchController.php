<?php


namespace App\Controller;
use App\Entity\Product;
use App\Entity\Service;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{   public function searchServices(string $name)
    {   $dao=$this->getDoctrine()->getRepository(Service::class);
        $services=$dao->findByNameLikeAndActiveTrue($name);

    }
    public function searchProducts(string $name)
    {   $dao=$this->getDoctrine()->getRepository(Product::class);
        $products=$dao->findByNameLikeAndActiveTrue($name);
        return $products;
    }

    /**
     * @Rest\Route("/activeProductsList")
     */
    public function showActiveProducts()
    {   $list=$this->searchProducts("");
        return $this->render("product/list.html.twig",["list"=>$list]);
    }
    /**
     * @Rest\Route("/orchid")
     */
    public function orchideesAction()
    {   $list=$this->searchProducts("orchid");
        return $this->render("product/list.html.twig",["list"=>$list]);
    }
    /**
     * @Rest\Route("/rip")
     */
    public function ripAction()
    {   $list=$this->searchProducts("orchid");
        return $this->render("product/list.html.twig",["list"=>$list]);
    }
    /**
     * @Rest\Route("/searchProducts/{name}")
     */
    public function spAction(string $name)
    {   $list=$this->searchProducts($name);
        return $this->render("product/list.html.twig",["list"=>$list]);
    }
}