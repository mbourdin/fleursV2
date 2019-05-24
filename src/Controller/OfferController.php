<?php
namespace App\Controller;
use App\Entity\Offer;
use App\Entity\Product;
use App\Form\OfferForm;
use App\Repository\OfferRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class OfferController extends Controller
{
    /**
     * @return Response
     * @Route("/admin/offer", name="offer_index", methods={"GET"})
     */
    public function index(): Response
    {   $offerRepository=$this->getDoctrine()->getRepository(Offer::class);
        return $this->render('offer/list.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }
    /**
     * @param Request $request
     * @return Response
     * @Route("/admin/offer/add", name="offer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferForm::class, $offer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();
            return $this->redirectToRoute('offer_index');
        }
        return $this->render('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param Offer $offer
     * @return Response
     * @Route("/admin/offer/show/{id}", name="offer_show", methods={"GET"})
     * @Route("/offer/read/{id}")
     */
    public function show(Offer $offer): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @param Request $request
     * @param Offer $offer
     * @return Response
     * @Route("/admin/offer/edit/{id}", name="offer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Offer $offer): Response
    {
        $form = $this->createForm(OfferForm::class, $offer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($offer);
            $entityManager->flush();
            return $this->redirectToRoute('offer_index');
        }
        $products=$this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'edit'=>true,
            'products'=>$products
        ]);
    }



}