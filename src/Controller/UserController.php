<?php
namespace App\Controller;
use App\Entity\Person;
use App\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends Controller
{
    /**
     * @Route("/formUser", name="saveuser")
     */
    public function saveUserAction(Request $request)
    {
        $user =new Person();
        $form=$this->get('form.factory')->create(UtilisateurType::class,$user,['action_origin_is_admin'=>true]);
        //Si POST
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirect("/");
        }
        //Si GET
        return $this->render("testing/formUser.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route ("/loadUser", name="loaduser")
     */
    public function loadUserAction()
    {   $users =$this->getDoctrine()->getRepository(Person::class)->findAll();
        return $this->render('testing/readUser.html.twig',["users"=>$users]);
    }
    /**
     * @Route("/user/panier/show",name="panier")
     */
    public function panierShowAction(Request $request)
    {   $cookie=$request->cookies->get("bucket");
        if ($cookie==null) {
            $sale = new Sale();
            $serialSale=serialize($sale);
            $cookie=new Cookie("bucket",$serialSale);
            $response=new Response();
            $response->headers->setCookie($cookie);
            $response->send();
        }
        else {
            $sale = unserialize($cookie);
        }

        return $this->render("/panier/panier.html.twig",["sale"=>$sale]);
    }
    /**
     * @Route("/user/panier/save",name="savePanier")
     */
    public function savePanierAction(Request $request)
    {
        $cookie=$request->cookies->get("bucket");
        $sale=unserialize($cookie);
        $em=$this->getDoctrine()->getManager();
        $em->persist($sale);
        $em->flush();
        $this->addFlash("success","panier sauvegardé");
        return $this->redirect("/test/panier/list");
    }

}