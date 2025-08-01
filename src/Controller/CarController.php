<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Service\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarController extends AbstractController
{
    #[Route('/cars', name: 'car.index', methods: ['GET'])]
    public function index(CarService $carService): Response
    {
        return $this->render('car/index.html.twig', [
            'cars' => $carService->getCars(),
        ]);
    }

    #[Route('/car/create', name: 'car.create', methods: ['GET', 'POST'])]
    public function create(Request $request, CarService $carService): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carService->createCar($car);

            return $this->redirectToRoute('car.index');
        }

        return $this->render('car/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/car/{id}', name: 'car.edit', methods: ['GET', 'POST'])]
    public function edit(Car $car, Request $request, CarService $carService): Response
    {
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carService->editCar($car);

            return $this->redirectToRoute('car.index');
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/car/{id}/remove', name: 'car.remove', methods: ['POST'])]
    public function remove(Car $car, Request $request, CarService $carService): Response
    {
        if ($this->isCsrfTokenValid('remove_car_' . $car->getId(), $request->request->get('_token'))) {
            $carService->removeCar($car);
        }

        return $this->redirectToRoute('car.index');
    }
}
