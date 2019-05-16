<?php
namespace App\Controller;
use App\Entity\Product;
use App\Entity\Service;
use App\Form\ServiceForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/service")
 */
class ServiceController extends Controller
{
    /**
     * @Route("/", name="service_index", methods={"GET"})
     */
    public function index(): Response
    {   $dao=$this->getDoctrine()->getRepository(Service::class);
        return $this->render('service/list.html.twig', [
            'services' => $dao->findAll(),
        ]);
    }
    /**
     * @Route("/add", name="service_add", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceForm::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($service);
            $entityManager->flush();
            return $this->redirectToRoute('service_index');
        }
        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/show/{id}", name="service_show", methods={"GET"})
     */
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }
    /**
     * @Route("/edit/{id}", name="service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Service $service): Response
    {
        $form = $this->createForm(ServiceForm::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('service_index', [
                'id' => $service->getId(),
            ]);
        }
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'products'=>$products,
        ]);
    }

    /**
     * @Route("/list")
     */
    public function list()
    {
        $dao=$this->getDoctrine()->getRepository(Service::class);
        return $this->render("service/listClientView.html.twig",["services"=>$dao->findAll()]);
    }
}