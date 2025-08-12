<?php

namespace App\Service;

use App\Entity\Car;
use App\Repository\CarRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CarService
{
    public function __construct(
        private CarRepository $carRepository,
        private EntityFetcherService $fetcher,
        private Security $security
    ) {}

    public function getCars(int $page = 1, int $limit = 10): PaginationInterface
    {
        return $this->fetcher->fetchByOwner(
            Car::class,
            $this->security->getUser(),
            $page,
            $limit,
        );
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
