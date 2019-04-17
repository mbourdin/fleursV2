<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
class AdminController extends Controller
{
    /**
     * @Route("/admin/city", name="city")
     */
    public function cityAction(Request $request)
    {   $dao=$this->getDoctrine()->getRepository('App:City');
        $city =new City();

        $cities =$dao->findAll();
        /*
        $form=$this->get('form.factory')->create(CityType::class,$city,['action_origin_is_admin'=>true]);
        //Si POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();
            return $this->redirect("/");

        }
        */
        //Si GET
        return $this->render("admin/city.html.twig",["cities"=>$cities]);
    }

}