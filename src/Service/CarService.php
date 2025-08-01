<?php

namespace App\Service;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\SecurityBundle\Security;

class CarService
{
    private CarRepository $carRepository;
    private Security $security;

    public function __construct(CarRepository $carRepository, Security $security)
    {
        $this->carRepository = $carRepository;
        $this->security = $security;
    }

    public function getCars(): array
    {
        return $this->security->getUser()->getCars()->toArray();
    }

    public function createCar(Car $car): Car
    {
        $car->setOwner($this->security->getUser());

        $this->carRepository->save($car, true);

        return $car;
    }

    public function editCar(Car $car): Car
    {
        $this->carRepository->save($car, true);

        return $car;
    }

    public function removeCar(Car $car): void
    {
        $this->carRepository->remove($car, true);
    }
}
