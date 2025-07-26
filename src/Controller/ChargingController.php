<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChargingController extends AbstractController
{
    #[Route('/chargings', name: 'charging.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('charging/index.html.twig');
    }
}
