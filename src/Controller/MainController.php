<?php
namespace App\Controller;
use App\Entity\Sale;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utility\OnEventActions;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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


    /**
     * @route ("/logout",name="logout")
     */
    public function logoutAction(Request $request)
    {
        OnEventActions::logout($request->getSession());
        return $this->redirect("/");
    }

//    /**
//     * @Route ("/inscription",name="inscription")
//     */
//    public function registerAction(Request $request)
//    {
//        $user = $this->userManager->createUser();
//        $user->setEnabled(true);
//
//        $event = new GetResponseUserEvent($user, $request);
//        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);
//
//        if (null !== $event->getResponse()) {
//            return $event->getResponse();
//        }
//
//        $form = $this->formFactory->createForm();
//        $form->setData($user);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted()) {
//            if ($form->isValid()) {
//                $event = new FormEvent($form, $request);
//                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
//
//                $this->userManager->updateUser($user);
//
//                if (null === $response = $event->getResponse()) {
//                    $url = $this->generateUrl('fos_user_registration_confirmed');
//                    $response = new RedirectResponse($url);
//                }
//
//                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
//
//                return $response;
//            }
//
//            $event = new FormEvent($form, $request);
//            $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);
//
//            if (null !== $response = $event->getResponse()) {
//                return $response;
//            }
//        }
//
//        return $this->render('@FOSUser/Registration/register.html.twig', array(
//            'form' => $form->createView(),
//        ));
//    }
}