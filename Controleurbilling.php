<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    #[Route('/facture', name: 'create_facture', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $facture = new Facture();
        $facture->setAmount($data['amount']);
        $facture->setDueDate(new \DateTime($data['due_date']));
        $facture->setCustomerEmail($data['customer_email']);

        $entityManager->persist($facture);
        $entityManager->flush();

        // Appel au service de notification
        $this->notificationService->createNotification(
            $data['customer_email'], 
            'Montant de votre facture: ' . $data['amount'], 
            'Facture crée'
        );

        return new JsonResponse(['status' => 'Facture crée'], JsonResponse::HTTP_CREATED);
    }


}