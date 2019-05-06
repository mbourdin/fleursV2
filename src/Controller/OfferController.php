<?php
namespace App\Controller;
use App\Entity\Offer;
use App\Entity\Product;
use App\Form\OfferForm;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/offer")
 */
class OfferController extends Controller
{
    /**
     * @Route("/", name="offer_index", methods={"GET"})
     */
    public function index(): Response
    {   $offerRepository=$this->getDoctrine()->getRepository(Offer::class);
        return $this->render('offer/list.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="offer_new", methods={"GET","POST"})
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
     * @Route("/show/{id}", name="offer_show", methods={"GET"})
     */
    public function show(Offer $offer): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }
    /**
     * @Route("/edit/{id}", name="offer_edit", methods={"GET","POST"})
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
        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="offer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();
        }
        return $this->redirectToRoute('offer_index');
    }

    /**
     * @Route("/list")
     */
    public function list()
    {
        $dao=$this->getDoctrine()->getRepository(Offer::class);
        return $this->render("offer/listClientView.html.twig",["offers"=>$dao->findAll()]);
    }
}