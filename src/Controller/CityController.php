<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;

class CityController extends Controller
{   // Section City
    private function getCityFromApi(int $inseeid): City
    {   $city=new City();



        return $city;
    }
    private function saveCity(City $city)
    {   $em=$this->getDoctrine()->getManager();
        $em->persist(city);
        $em->flush();

    }
    /**
     * @Route("/admin/city/addActive/{id}", name="cityaddactive")
     */
    public function addActiveAction(Request $request,int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->findOneBy(["inseeid"=>$id]);
        if($city==null)
        {   $city=$this->getCityFromApi($id);
            $city->setActive(true);
            $this->saveCity($city);
            $this->addFlash("success","ville ajoutée active");
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

        return $this->redirect("/admin/city");
    }
    /**
     * @Route("/user/city/addInactive/{id}", name="cityaddinactive")
     */
    public function addInactiveAction(Request $request,int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->findOneBy(["inseeid"=>$id]);
        if($city==null)
        {   $city=$this->getCityFromApi($id);
            $city->setActive(false);
            $this->saveCity($city);
        }
        return $this->redirect("/admin/city");
    }
    /**
     * @Route("/admin/city/activate/{id}", name="cityaddinactive")
     */
    public function activateAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->findOneBy(["inseeid"=>$id]);
        if($city==null)
        {   $city=$this->getCityFromApi($id);
            $city->setActive(true);
            $this->addFlash("success","ville  activée");
        }
        return $this->redirect("/admin/city");
    }
    /**
     * @Route("/admin/city/deactivate/{id}", name="cityaddinactive")
     */
    public function deactivateAction(int $id)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $city=$dao->findOneBy(["inseeid"=>$id]);
        if($city==null)
        {   $city=$this->getCityFromApi($id);
            $city->setActive(false);
            $this->addFlash("success","ville  activée");
        }
        return $this->redirect("/admin/city");
    }
}