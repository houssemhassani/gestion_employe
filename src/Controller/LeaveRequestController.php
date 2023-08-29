<?php

namespace App\Controller;

use App\Entity\LeaveRequest;
use App\Form\LeaveRequestType;
use App\Repository\LeaveRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/leave/request')]
class LeaveRequestController extends AbstractController
{
    #[Route('/', name: 'app_leave_request_index', methods: ['GET'])]
    public function index(Security $security,LeaveRequestRepository $leaveRequestRepository): Response
    {
        if($security->getUser()->getRoles()==["GRH"] || $security->getUser()->getRoles()==["EMPLOYE"]) {
            $isGRH = $security->getUser()->getRoles()==["GRH"];
            $showButton = $isGRH;

            return $this->render('leave_request/index.html.twig', [
                'leave_requests' => $leaveRequestRepository->findAll(),
                'showButton' => $showButton,
            ]);
        }
        else
            return $this->render('error_modal.html.twig');
    }

    #[Route('/new', name: 'app_leave_request_new', methods: ['GET', 'POST'])]
    public function new(Security $security,Request $request, EntityManagerInterface $entityManager): Response
    {
        if($security->getUser()->getRoles()==["EMPLOYE"]) {
            $leaveRequest = new LeaveRequest();
            $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($leaveRequest);
                $entityManager->flush();

                return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
            } else

                return $this->renderForm('leave_request/new.html.twig', [
                    'leave_request' => $leaveRequest,
                    'form' => $form,
                ]);
        }else
            return $this->render('error_modal.html.twig');
    }

    #[Route('/{id}', name: 'app_leave_request_show', methods: ['GET'])]
    public function show(Security $security, LeaveRequest $leaveRequest): Response
    {
        if($security->getUser()->getRoles()==["GRH"]||
            $security->getUser()->getRoles()==["EMPLOYE"]) {
            return $this->render('leave_request/show.html.twig', [
                'leave_request' => $leaveRequest,
            ]);
        }
        else
            return $this->render('error_modal.html.twig');
    }

    #[Route('/{id}/accept', name: 'app_leave_request_accept', methods: ['GET', 'POST'])]
    public function accept(Security $security,Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        if($security->getUser()->getRoles()==["GRH"]) {


            $leaveRequest->setTypeOfLeaveRequest(true);
            $entityManager->flush();

            return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }
        else
            return $this->render('error_modal.html.twig');
    }
    #[Route('/{id}/refuse', name: 'app_leave_request_refuse', methods: ['GET', 'POST'])]
    public function refuse( Security $security,Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        if($security->getUser()->getRoles()==["GRH"]){
            $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $leaveRequest->setTypeOfLeaveRequest(false);
                $entityManager->flush();

                return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
            }
            else
                return $this->renderForm('leave_request/edit.html.twig', [
                    'leave_request' => $leaveRequest,
                    'form' => $form,
                ]);
        }
        else
            return $this->render('error_modal.html.twig');
    }

    #[Route('/{id}', name: 'app_leave_request_delete', methods: ['POST'])]
    public function delete(Security  $security, Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        if ($security->getUser()->getRoles() == ["EMPLOYE"]) {
            if ($this->isCsrfTokenValid('delete' . $leaveRequest->getId(), $request->request->get('_token'))) {
                $entityManager->remove($leaveRequest);
                $entityManager->flush();
            } else
                return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }
            return $this->render('error_modal.html.twig');
    }
}
