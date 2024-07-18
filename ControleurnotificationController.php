<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ControleurnotificationController extends AbstractController
{
    #[Route('/controleurnotification', name: 'app_controleurnotification')]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $notification = new Notification();
        $notification->setEmailRecipient($data['email_recipient']);
        $notification->setMessage($data['message']);
        $notification->setSujet($data['sujet']);

        $entityManager->persist($notification);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Notification crée'], JsonResponse::HTTP_CREATED);
    }
}
