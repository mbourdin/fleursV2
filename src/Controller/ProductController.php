<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/accueil/produits")
     */

    public function produitAction(){
        return $this->render("default/product.html.twig");
    }
}