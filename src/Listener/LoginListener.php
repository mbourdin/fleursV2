<?php


namespace App\Listener;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Utility\OnEventActions;
use Doctrine\ORM\EntityManager;
use App\Repository\PersonRepository;
class LoginListener
{   protected $userManager;

    public function __construct(UserManagerInterface $userManager){
        $this->userManager = $userManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {   $login = $event->getAuthenticationToken()->getUser();
        $user=$this->userManager->findUserByUsername($login);
        $session=$event->getRequest()->getSession();
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