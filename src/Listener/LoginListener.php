<?php


namespace App\Listener;

use App\Entity\Person;
use App\Entity\Sale;
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
        OnEventActions::setPermissions($session,$user);
        $saleDao=$this->em->getRepository(Sale::class);
        $criteria = array('person' => $user,'validated'=>false);
        $sale=$saleDao->findOneBy($criteria);
        if($sale!=null && !$sale->empty())
        {   /*$sale->setId(null);
            foreach($sale->getProducts()->getIterator() as $i => $productContent)
            {
                $productContent->setId(null);
            }
            foreach($sale->getServices()->getIterator() as $i => $serviceContent)
            {
                $serviceContent->setId(null);
            }
            foreach($sale->getOffers()->getIterator() as $i => $offerContent)
            {
                $offerContent->setId(null);
            }
            */
            $session->set("sale",$sale);
        }
    }
}