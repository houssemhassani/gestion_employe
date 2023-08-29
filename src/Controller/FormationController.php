<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(Security $security,FormationRepository $formationRepository): Response
    {
        if($security->getUser()->getRoles()==["GRH"]||
            $security->getUser()->getRoles()==["EMPLOYE"]) {
            return $this->render('formation/index.html.twig', [
                'formations' => $formationRepository->findAll(),
            ]);
        }
        else
            return $this->render('error_modal.html.twig');;
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    
    public function new(Security $security,Request $request, EntityManagerInterface $entityManager): Response
    {
        if($security->getUser()->getRoles()==["GRH"]) {
            $formation = new Formation();
            $form = $this->createForm(FormationType::class, $formation);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $formation->setCreatedAt(new \DateTimeImmutable());

                $entityManager->persist($formation);
                $entityManager->flush();

                return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
            } else
                return $this->renderForm('formation/new.html.twig', [
                    'formation' => $formation,
                    'form' => $form,
                ]);
        }
        else
            return $this->render('error_modal.html.twig');;
    }

    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Security $security,Formation $formation): Response
    {
        if ($security->getUser()->getRoles() == ["GRH"] ||
            $security->getUser()->getRoles() == ["EMPLOYE"]) {
            return $this->render('formation/show.html.twig', [
                'formation' => $formation,
            ]);
        } else
            return $this->render('error_modal.html.twig');
    }

    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,Security $security, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($security->getUser()->getRoles() == ["GRH"]){
            $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        } else
            return $this->renderForm('formation/edit.html.twig', [
                'formation' => $formation,
                'form' => $form,
            ]);
    }
        else
            return $this->render('error_modal.html.twig');
    }

    #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
