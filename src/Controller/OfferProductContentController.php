<?php
namespace App\Controller;
use App\Entity\OfferProductContent;
use App\Form\OfferProductContentForm;
use App\Repository\OfferProductContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/product_offer")
 */
class OfferProductContentController extends Controller
{
    /**
     * @Route("/", name="product_offer_content_index", methods={"GET"})
     */
    public function index(): Response
    {   $dao=$this->getDoctrine()->getRepository(OfferProductContent::class);
        return $this->render('offer/content/index.html.twig', [
            'product_offer_contents' => $dao->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="product_offer_content_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offerProductContent = new OfferProductContent();
        $form = $this->createForm(OfferProductContentForm::class, $offerProductContent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offerProductContent);
            $entityManager->flush();
            return $this->redirectToRoute('product_offer_content_index');
        }
        return $this->render('offer/content/new.html.twig', [
            'product_offer_content' => $offerProductContent,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="product_offer_content_show", methods={"GET"})
     */
    public function show(OfferProductContent $offerProductContent): Response
    {
        return $this->render('offer/content/show.html.twig', [
            'product_offer_content' => $offerProductContent,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="product_offer_content_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OfferProductContent $offerProductContent): Response
    {
        $form = $this->createForm(OfferProductContent::class, $offerProductContent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('product_offer_content_index', [
                'id' => $offerProductContent->getId(),
            ]);
        }
        return $this->render('offer/content/edit.html.twig', [
            'product_offer_content' => $offerProductContent,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="product_offer_content_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OfferProductContent $offerProductContent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offerProductContent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offerProductContent);
            $entityManager->flush();
        }
        return $this->redirectToRoute('product_offer_content_index');
    }
}