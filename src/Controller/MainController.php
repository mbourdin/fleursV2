<?php
namespace App\Controller;
use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Sale;
use App\Form\ProfileForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Utility\OnEventActions;
class MainController extends Controller
{
    /**
     * @Route("/",name="index")
     */
    public function indexAction()
    {  $products=$this->getDoctrine()->getRepository(Product::class)->findBy(["active"=>true]);
        return  $this->render("default/home.html.twig",["products"=>$products]);
    }


    /**
     * @route ("/logout",name="logout")
     */
    public function logoutAction(Request $request)
    {
        OnEventActions::logout($request->getSession());
        return $this->redirect("/");
    }
    /**
     * @route ("/Profile")
     */
    public function profileAction(Request $request)
    {   $user=$request->getSession()->get("user");
        return $this->render("bundles/FOSUserBundle/Profile/show.html.twig",["user"=>$user]);

    }

    /**
     * @Route("/Profile/edit")
     */
    public function profileEditAction(Request $request)
    {   $user=$request->getSession()->get("user");
        $user=$this->getDoctrine()->getRepository(Person::class)->find($user->getId());
        $form=$this->createForm(ProfileForm::class,$user);
        $form->add('submit', SubmitType::class, [
            'label' => 'sauvegarder',
            'attr' => ['class' => 'btn btn-primary pull-right'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //on enregistre le produit dans la bdd

            $reg = $this->getDoctrine()->getManager();

            $reg->persist($user);
            $reg->flush();
            $request->getSession()->set("photo",$user->getPhotopath());
            $this->addFlash("success","profile modifé : ".$user->getUsername());
            return $this->redirect("/Profile");
        }
        //on va générer le Html
        $formView= $form->createView();

        // on rend la vue
        return $this->render('bundles/FOSUserBundle/Profile/edit.html.twig', array('form' => $formView,"user"=>$user));
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