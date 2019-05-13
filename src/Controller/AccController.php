<?php


namespace App\Controller;
use App\Entity\Person;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use \RuntimeException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acc")
 */
class AccController extends Controller
{
    private $serializer;
    /**
     * AddressRestController constructor.
     */
    public function __construct()
    {   $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }
    /**
     * @Route("/user/list")
     */
    public function userListAction()
    {
        $dao = $this->getDoctrine()->getRepository(Person::class);
        $result = $dao->findAll();
        return $this->render('user/list.html.twig', array('users' => $result));
    }
    // L'admin ne doit PAS pouvoir modifier ses droits
    private function isMyself(Person $user,Request $request)
    {   $sessionUser=$request->getSession()->get("user");
        return $sessionUser->equals($user);
    }

    /**
     * @Rest\Put("/ban/{id}")
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
     * @Rest\Put("/unban/{id}")
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
     * @Rest\Put("/delete/{id}")
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
     * @Rest\Put("/undelete/{id}")
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
     * @Rest\Put("/setUserRights/{id}/{rights}")
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