<?php


namespace App\Controller;
use App\Entity\Person;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class AdminRestController extends Controller
{   // L'admin ne doit PAS pouvoir modifier ses droits
    private function isMyself(Person $user,Request $request)
    {   $sessionUser=$request->getSession()->get("user");
        return $sessionUser->equals($user);
    }

    /**
     * @Rest\Put("/admin/ban/{id}")
     */
    public function ban(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setBanned(true);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/unban/{id}")
     */
    public function unban(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setBanned(false);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/delete/{id}")
     */
    public function delete(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setDeleted(true);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }
    /**
     * @Rest\Put("/admin/undelete/{id}")
     */
    public function unDelete(int $id,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setDeleted(false);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }

    /**
     * @Rest\Put("/admin/setUserRights/{id}/{rights}")
     */
    public function setUserRights(int $id,int $rights,Request $request)
    {   $dao=$this->getDoctrine()->getRepository(Person::class);
        $user=$dao->find($id);
        if($this->isMyself($user,$request))
        {
            throw new \RuntimeException("Tentative de modification par l'admin de ses propres paramètres!");
        }
        $user->setRights($rights);
        $em=$this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        return new Response();
    }

}