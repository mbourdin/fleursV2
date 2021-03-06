<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use FOS\RestBundle\Controller\Annotations as Rest;
use RuntimeException;

class CityController extends Controller
{   private $serializer;
    /**
     * AdminRestController constructor.
     */
    public function __construct()
    {   $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = [$normalizer];
        $encoders = [new JsonEncoder()];
        $this->serializer = new Serializer($normalizers, $encoders);

    }
    /**
     * @param Request $request
     * @return Response
     * @Route("/admin/city", name="city")
     */
    public function cityAction(Request $request)
    {   $dao=$this->getDoctrine()->getRepository(City::class);

        $cities =$dao->findAll();

        return $this->render("admin/city.html.twig",["cities"=>$cities]);
    }
     /**
     * @param City $city
     */
    private function saveCity(City $city)
    {   $em=$this->getDoctrine()->getManager();
        $em->persist($city);
        $em->flush();
    }
    /**
     * @param Request $request
     * @return Response
     * @Rest\Post("/admin/city/addActive", name="cityaddactive")
     */
    public function addActiveAction(Request $request)
    {   $id=$request->request->get("inseeId");
        $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->findOneBy(["inseeid"=>$id]);
        if($city==null)
        {   $city=City::getFromApi($id);
            $city->setActive(true);
            $this->saveCity($city);
//            $this->addFlash("success","ville ajoutée active");
        }
        elseif (!$city->getActive())
        {   $city->setActive(true);
            $this->saveCity($city);
//            $this->addFlash("success","ville ajoutée active");
        }
        else
        {
//            $this->addFlash("error","cette ville est déja présente et active");
        }
        $json_city=$this->serializer->serialize($city,"json");
        return new Response($json_city);
    }
    /**
     * @param int $id
     * @return Response
     * @Rest\Put("/admin/city/activate/{id}")
     */
    public function activateAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->find($id);
        if($city!=null)
        {   $city->setActive(true);
            $this->saveCity($city);
        }
        else
        {
            throw new RuntimeException("erreur dans CityController->activateAction(): ville non trouvée dans la base");
        }
        return new Response($id);
    }
    /**
     * @param int $id
     * @return Response
     * @Rest\Put("/admin/city/deactivate/{id}")
     */
    public function deactivateAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->find($id);
        if($city!=null)
        {   $city->setActive(false);
            $this->saveCity($city);
        }
        else
        {
            throw new RuntimeException("erreur dans CityController->deactivateAction(): ville non trouvée dans la base");
        }
        return new Response($id);
    }
    /**
     * @param int $id
     * @return Response
     * @Rest\Delete("/admin/city/delete/{id}")
     */
    public function deleteAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->find($id);
        if($city!=null)
        {   $em=$this->getDoctrine()->getManager();
            $em->remove($city);
            $em->flush();
        }
        else
        {
            throw new RuntimeException("erreur dans CityController->deleteAction(): ville non trouvée dans la base");
        }
        return new Response($id);
    }
}