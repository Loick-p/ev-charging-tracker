<?php

namespace App\Controller;

use App\Entity\Station;
use App\Form\StationType;
use App\Service\StationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StationController extends AbstractController
{
    public function __construct(
        private readonly StationService $stationService
    ) {}

    #[Route('/stations', name: 'station.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('station/index.html.twig', [
            'stations' => $this->stationService->getStations(
                $request->query->getInt('page', 1),
            ),
        ]);
    }

    #[Route('/stations/create', name: 'station.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $station = new Station();

        $form = $this->createForm(StationType::class, $station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->stationService->createStation($station);

            $this->addFlash('success', 'Station created successfully.');
            return $this->redirectToRoute('station.index');
        }

        return $this->render('station/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/stations/{id}', name: 'station.edit', methods: ['GET', 'POST'])]
    public function edit(Station $station, Request $request): Response
    {
        $form = $this->createForm(StationType::class, $station);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->stationService->editStation($station);

            $this->addFlash('success', 'Station updated successfully.');
            return $this->redirectToRoute('station.index');
        }

        return $this->render('station/edit.html.twig', [
            'station' => $station,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/stations/{id}/remove', name: 'station.remove', methods: ['POST'])]
    public function remove(Station $station, Request $request): Response
    {
        if ($this->isCsrfTokenValid('remove_station_' . $station->getId(), $request->request->get('_token'))) {
            $this->stationService->removeStation($station);
            $this->addFlash('success', 'Station deleted successfully.');
        }

        return $this->redirectToRoute('station.index');
    }
}
