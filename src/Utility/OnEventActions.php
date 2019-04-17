<?php
namespace App\Utility;
use App\Entity\Person;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
//cette classe rassemble les actions déclanchées par différent événements
class OnEventActions
{   //initialisation de variables de session lors d'une connexion réussie
    static function setPermissions(SessionInterface $session, Person $user)
    {
        $userRights = $user->getRights() % 2 == 1;
        $adminRights = ($user->getRights() / 2) % 2 == 1;
        $accAdminRights = ($user->getRights() / 4) % 2 == 1;
        $session->set("userRights", $userRights);
        $session->set("adminRights", $adminRights);
        $session->set("accAdminRights", $accAdminRights);
        $session->set("user",$user);
        $session->set("connected",true);
    }
    static function logout(SessionInterface $session)
    {
        $session->clear();
        $session->invalidate();
    }
}