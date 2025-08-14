<?php

namespace App\Controller;

use App\Entity\Charging;
use App\Form\ChargingType;
use App\Service\ChargingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ChargingController extends AbstractController
{
    public function __construct(
        private readonly ChargingService $chargingService
    ) {}

    #[Route('/chargings', name: 'charging.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('charging/index.html.twig', [
            'chargings' => $this->chargingService->getChargings(
                $request->query->getInt('page', 1),
                20
            ),
        ]);
    }

    #[Route('/chargings/create', name: 'charging.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $charging = new Charging();

        $form = $this->createForm(ChargingType::class, $charging, [
            'owner' => $this->getUser(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->chargingService->createCharging($charging);

            $this->addFlash('success', 'Charging session created successfully.');
            return $this->redirectToRoute('charging.index');
        }

        return $this->render('charging/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/chargings/{id}', name: 'charging.edit', methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject: 'charging')]
    public function edit(Charging $charging, Request $request): Response
    {
        $form = $this->createForm(ChargingType::class, $charging, [
            'owner' => $this->getUser(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->chargingService->editCharging($charging);

            $this->addFlash('success', 'Charging session updated successfully.');
            return $this->redirectToRoute('charging.index');
        }

        return $this->render('charging/edit.html.twig', [
            'charging' => $charging,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/chargings/{id}/remove', name: 'charging.remove', methods: ['POST'])]
    #[IsGranted('DELETE', subject: 'charging')]
    public function remove(Charging $charging, Request $request): Response
    {
        if ($this->isCsrfTokenValid('remove_charging_' . $charging->getId(), $request->request->get('_token'))) {
            $this->chargingService->removeCharging($charging);
            $this->addFlash('success', 'Charging session deleted successfully.');
        }

        return $this->redirectToRoute('charging.index');
    }
}
