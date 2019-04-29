<?php


namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\City;

class CityRestController extends Controller
{
    /**
     * @Rest\Post(
     *    path = "/api/city/createInactive",
     *    name = "app_article_create_inactive"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("city", converter="fos_rest.request_body")
     */
    public function createInactiveAction(City $city)
    {   $dao=$this->getDoctrine()->getRepository(City::class);
        $oldCity=$dao->findOneBy($city->getInseeid());
        if($oldCity!=null)
        {   $city=$oldCity;
        }
        else
        {   $city->setActive(false);
        }
        $em = $this->getDoctrine()->getManager();

        $em->persist($city);
        $em->flush();

        return $city;
    }
    /**
     * @Rest\Post(
     *    path = "/api/city/createActive",
     *    name = "app_article_create_active"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("city", converter="fos_rest.request_body")
     */
    public function createActiveAction(City $city)
    {   $dao=$this->getDoctrine()->getRepository('App:City');
        $oldCity=$dao->findOneBy($city->getInseeid());
        if($oldCity!=null)
        {   $city=$oldCity;
        }
        $city->setActive(true);

        $em = $this->getDoctrine()->getManager();

        $em->persist($city);
        $em->flush();

        return $city;
    }
    /**
     * @Rest\Post(
     *    path = "/api/city/disable",
     *    name = "app_article_disable"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("city", converter="fos_rest.request_body")
     */
    public function disableAction(City $city)
    {   $city->setActive(false);
        $em = $this->getDoctrine()->getManager();

        $em->persist($city);
        $em->flush();
    }

}