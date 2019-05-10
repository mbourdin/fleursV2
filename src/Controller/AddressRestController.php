<?php


namespace App\Controller;
use App\Entity\Address;
use App\Entity\Person;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AddressRestController extends Controller
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
     * @Rest\Get("/user/address/getOwn")
     *
     */


    public function getOwnAddres(Request $request)
    {   $user=$request->getSession()->get("user");
        if($user==null)
        {
            throw new \RuntimeException("utilisateur non connecté");
        }
        $user=$this->getDoctrine()->getRepository(Person::class)->find($user->getId()) ;
        $json_address=$this->serializer->serialize($user->getAddress(),"json");
        $response=new Response($json_address);
        return $response;
    }
}