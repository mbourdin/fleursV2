<?php


namespace App\Listener;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Utility\OnEventActions;
use Doctrine\ORM\EntityManager;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Response;
class LoginListener
{   protected $userManager;
    private $em;

    public function __construct(UserManagerInterface $userManager,EntityManagerInterface $em){
        $this->userManager = $userManager;
        $this->em=$em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {   $login = $event->getAuthenticationToken()->getUser();
        $id=$this->userManager->findUserByUsername($login)->getId();
        $user=$this->em->getRepository(Person::class)->find($id);

        $session=$event->getRequest()->getSession();
        if($user->getBanned())
        {
            OnEventActions::logout($session);
            $event->getAuthenticationToken()->setAuthenticated(false);
            $session->getBag("flashes")->add("error","your account is banned");
            return null;

        }
        if($user->getDeleted())
        {
            OnEventActions::logout($session);
            $event->getAuthenticationToken()->setAuthenticated(false);
            $session->getBag("flashes")->add("error","Votre compte est marqué comme supprimé");
            return null;
        }
        OnEventActions::setPermissions($session,$user);//le warning ici n'est pas grave
        // c'est simpelemnt l'IDE qui ne détermine pas que $user est forcément de la classe
        // 2 fois dérivée Person.

        // In order to test if it works, create a file with the name login.txt in the /web path of your project
//        $myfile = fopen("login.txt", "w");
//        fwrite($myfile, $login);
//        fclose($myfile);
        // do something else
        // return new Response();
    }
}