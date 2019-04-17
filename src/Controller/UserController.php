<?php
namespace App\Controller;
use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
}