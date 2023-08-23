<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\SendMailService;
use App\Service\PdfService;
use Symfony\Component\Security\Core\Security;
use App\Form\ContactType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/employe', name: 'app_employe_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,Security $security): Response
    {
        $today = new \DateTime();
        $isGRH = $security->isGranted('ROLE_GRH');
        $showButton = $isGRH && $today->format('m-d') === '08-22';
        return $this->render('employe/index.html.twig', [
            'showButton' => $showButton,
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/grh', name: 'app_grh_index', methods: ['GET'])]
    public function indexGrh(UserRepository $userRepository): Response
    {
        return $this->render('grh/index.html.twig', [

            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/newEmploye', name: 'app_employe_new', methods: ['GET', 'POST'])]
    public function newEmploye(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        //$form2 = $this->createForm(ContactType::class);
        $form->handleRequest($request);



            $entityManager->persist($user);
            $entityManager->flush();



            return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);


       /* return $this->renderForm('employe/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);*/
    }
    #[Route('/newGrh', name: 'app_grh_new', methods: ['GET', 'POST'])]
    public function newGRH(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setRoles(["GRH"]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_grh_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grh/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/employe/{id}', name: 'app_employe_show', methods: ['GET'])]
    public function show(User $user,PdfService $pdf)
    {
        /*$html = $this->render('employe/show.html.twig', ['user' => $user]);
        $pdf->showPdFile($html);*/
         return $this->render('employe/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/grh/{id}', name: 'app_grh_show', methods: ['GET'])]
    public function showGrh(User $user,PdfService $pdf)
    {
        /*$html = $this->render('employe/show.html.twig', ['user' => $user]);
        $pdf->showPdFile($html);*/
        return $this->render('grh/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit/employe', name: 'app_employe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employe/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit/Grh', name: 'app_grh_edit', methods: ['GET', 'POST'])]
    public function editGrh(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_grh_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grh/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/employe', name: 'app_employe_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/grh', name: 'app_grh_delete', methods: ['POST'])]
    public function deleteGrh(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
    }
}
