<?php
namespace App\Controller;
use App\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utility\OnEventActions;
class MainController extends Controller
{
    /**
     * @Route("/",name="index")
     */
    public function indexAction()
    {   return  $this->render("default/home.html.twig");
    }
    /**
     * @Route("/planning")
     */
    public function planningAction()
    {   return $this->render("default/planning.html.twig");
    }
//    /**
//     * @param Request $request
//     * @Route("/login",name="login")
//     */
//    public function loginAction(Request $request)
//    {
//        $login=new Login();
//        $form=$this->get('form.factory')->create(LoginType::class,$login);
//        //Si POST
//        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
//            $user =$this->getDoctrine()->getRepository(Person::class)->findOneBy(array('email' =>$login->getLogin() ));
//            if($user===null){
//                $this->addFlash("error", "erreur de connexion");
//                return $this->render("login/login.html.twig",array('form'=>$form->createView()));
//            }
//            if($user->getPassword()===$login->getPassword())
//            {   OnEventActions::setPermissions($request->getSession(),$user);
//            }
//            return $this->redirect("/");
//        }
//        //Si GET
//        return $this->render("login/login.html.twig",array('form'=>$form->createView()));
//    }
    /**
     * @route ("/logout",name="logout")
     */
    public function logoutAction(Request $request)
    {
        OnEventActions::logout($request->getSession());
        return $this->redirect("/");
    }

}