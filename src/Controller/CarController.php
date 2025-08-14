<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Service\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CarController extends AbstractController
{
    public function __construct(
        private readonly CarService $carService
    ) {}

    #[Route('/cars', name: 'car.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('car/index.html.twig', [
            'cars' => $this->carService->getCars(
                $request->query->getInt('page', 1)
            ),
        ]);
    }

    #[Route('/cars/create', name: 'car.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->carService->createCar($car);

            $this->addFlash('success', 'Car created successfully.');
            return $this->redirectToRoute('car.index');
        }

        return $this->render('car/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cars/{id}', name: 'car.edit', methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject: 'car')]
    public function edit(Car $car, Request $request): Response
    {
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->carService->editCar($car);

            $this->addFlash('success', 'Car updated successfully.');
            return $this->redirectToRoute('car.index');
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cars/{id}/remove', name: 'car.remove', methods: ['POST'])]
    #[IsGranted('DELETE', subject: 'car')]
    public function remove(Car $car, Request $request): Response
    {
        if ($this->isCsrfTokenValid('remove_car_' . $car->getId(), $request->request->get('_token'))) {
            $this->carService->removeCar($car);
            $this->addFlash('success', 'Car deleted successfully.');
        }

        return $this->redirectToRoute('car.index');
    }
}
