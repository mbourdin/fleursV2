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
     * @Route("/admin/city", name="city")
     */
    public function cityAction(Request $request)
    {   $dao=$this->getDoctrine()->getRepository(City::class);

        $cities =$dao->findAll();

        return $this->render("admin/city.html.twig",["cities"=>$cities]);
    }


    private function getCityFromApi(int $inseeid): City
    {   $city=new City();
        $response=file_get_contents("https://geo.api.gouv.fr/communes/".$inseeid."?fields=nom&format=json&geometry=centre");
        $json=json_decode($response);

        if ($json==null){
            return null;
        }
        $city->setName($json->nom);
        $city->setInseeid($inseeid);
        return $city;
    }
    private function saveCity(City $city)
    {   $em=$this->getDoctrine()->getManager();
        $em->persist($city);
        $em->flush();

    }
    /**
     * @Rest\Post("/admin/city/addActive", name="cityaddactive")
     */
    public function addActiveAction(Request $request)
    {   $id=$request->request->get("inseeid");
        $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->findOneBy(["inseeid"=>$id]);
        if($city==null)
        {   $city=$this->getCityFromApi($id);
            $city->setActive(true);
            $this->saveCity($city);
        }
        elseif (!$city->getActive())
        {   $city->setActive(true);
            $this->saveCity($city);
            $this->addFlash("success","ville ajoutée active");
        }
        else
        {
            $this->addFlash("error","cette ville est déja présente et active");
        }
        $json_city=$this->serializer->serialize($city,"json");
        return new Response($json_city);
    }


    /**
     * @Route("/admin/city/activate/{id}", name="cityactivate")
     */
    public function activateAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->find($id);
        if($city!=null)
        {   $city->setActive(true);
            $this->addFlash("success","ville  activée");
            $this->saveCity($city);
        }
        else{
            $this->addFlash("error","ville inexistante dans la base");
        }
        return $this->redirect("/admin/city");
    }
    /**
     * @Route("/admin/city/deactivate/{id}", name="citydeactivate")
     */
    public function deactivateAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->find($id);
        if($city!=null)
        {   $city->setActive(false);
            $this->addFlash("success","ville  désactivée");
            $this->saveCity($city);
        }
        else
        {
            $this->addFlash("error","ville inexistante dans la base");
        }
        return $this->redirect("/admin/city");
    }
    /**
     * @Route("/admin/city/delete/{id}")
     */
    public function deleteAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->find($id);
        if($city!=null)
        {   $em=$this->getDoctrine()->getManager();
            $em->remove($city);
            $em->flush();
            $this->addFlash("success","ville  supprimée");
        }
        else
        {
            $this->addFlash("error","ville inexistante dans la base");
        }
        return $this->redirect("/admin/city");
    }

}