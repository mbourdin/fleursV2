<?php


namespace App\Controller;


use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/accueil/produits")
     */

    public function produitAction(){
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render("product/ListClientView.html.twig",["products"=>$products]);
    }
}