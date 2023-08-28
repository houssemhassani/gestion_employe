<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\UserType;
use App\Service\SendMailService;
use App\Service\PdfService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use App\Form\ContactType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    #[Route('/employe', name: 'app_employe_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,Security $security): Response
    {
        if($security->getUser()->getRoles()==["GRH"]
            || $security->getUser()->getRoles()==["ADMIN"]
        || $security->getUser()->getRoles()==["Resp_Financier"]){
            //dd($security->getUser());


        $today = new \DateTime();
        $isGRH = $security->isGranted('ROLE_GRH');
        $showButton = $isGRH && $today->format('m-d') === '08-22';
        $users=$userRepository->findAll();
        $employeeUsers = [];

// Parcourir chaque utilisateur pour vérifier leur rôle
        foreach ($users as $user) {
            // Supposons que le rôle soit stocké dans une propriété "role" de l'objet utilisateur
            if ($user->getRoles() === ["EMPLOYE"]) {
                // Ajouter l'utilisateur à la liste des employés
                $employeeUsers[] = $user;
            }
        }
        return $this->render('employe/index.html.twig', [
            'showButton' => $showButton,
            'users' => $employeeUsers,
        ]);}
        else
        return $this->render('error_modal.html.twig');
    }

    #[Route('/grh', name: 'app_grh_index', methods: ['GET'])]
    public function indexGrh(UserRepository $userRepository,Security $security): Response
    {
        if( $security->getUser()->getRoles()==["ADMIN"]
            || $security->getUser()->getRoles()==["Resp_Financier"]){
        $users=$userRepository->findAll();
        return $this->render('grh/index.html.twig', [

            'users' => $users,
        ]);}
        else
            return $this->render('error_modal.html.twig');
    }


    #[Route('/newEmploye', name: 'app_employe_new', methods: ['GET', 'POST'])]
    public function newEmploye(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = new User();
        $user->setRoles(["EMPLOYE"]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if($form->isSubmitted() ) {

            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $form->get("password")->getData())
            );
            $user->setToken($this->generateToken());
            //dd($user);
            //$em = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employe/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/newGrh', name: 'app_grh_new', methods: ['GET', 'POST'])]
    public function newGRH(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setRoles(["GRH"]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if($form->isSubmitted() ) {

            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $form->get("password")->getData())
            );
            $user->setToken($this->generateToken());
            //dd($user);
            //$em = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_grh_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grh/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/newEmploye', name: 'app_emp_new', methods: ['GET', 'POST'])]
    public function newEmp(Request $request, EntityManagerInterface $entityManager): Response
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

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
