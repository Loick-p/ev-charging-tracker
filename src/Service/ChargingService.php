<?php

namespace App\Service;

use App\Entity\Charging;
use App\Repository\ChargingRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ChargingService
{
    public function __construct(
        private ChargingRepository $chargingRepository,
        private Security $security
    ) {}

    public function getChargings(): array
    {
        return $this->security->getUser()->getChargings()->toArray();
    }

    public function getChargingStats(?string $dateRange): array
    {
        $startDate = null;
        $endDate = null;
        if ($dateRange) {
            [$startStr, $endStr] = explode(' - ', $dateRange);
            $startDate = \DateTime::createFromFormat('d/m/Y', trim($startStr));
            $endDate = \DateTime::createFromFormat('d/m/Y', trim($endStr));
        }

        return $this->chargingRepository->getOwnerChargingStats(
            $this->security->getUser()->getId(),
            $startDate,
            $endDate
        );
    }

    public function createCharging(Charging $charging): Charging
    {
        $charging->setOwner($this->security->getUser());
        $charging->setTotalCost($this->calculateTotalCost($charging));

        $this->chargingRepository->save($charging, true);

        return $charging;
    }

    public function editCharging(Charging $charging): Charging
    {
        $charging->setTotalCost($this->calculateTotalCost($charging));

        $this->chargingRepository->save($charging, true);

        return $charging;
    }

    public function removeCharging(Charging $charging): void
    {
        $this->chargingRepository->remove($charging, true);
    }

    private function calculateTotalCost(Charging $charging): float
    {
        return $charging->getTotalKwh() * $charging->getStation()->getElectricityPrice();
    }
}
