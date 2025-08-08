<?php

namespace App\Controller;

use App\Repository\ChargingRepository;
use App\Service\ChargingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    public function __construct(
        private readonly ChargingService $chargingService,
    ) {}

    #[Route('/', name: 'dashboard.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $stats = $this->chargingService->getChargingStats($request->query->get('daterange'));

        return $this->render('dashboard/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}
