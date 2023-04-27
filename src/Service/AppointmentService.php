<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;

class AppointmentService
{
    private AppointmentRepository $repository;

    /**
     * @param AppointmentRepository $repository
     */
    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function add(Appointment $appointment): void
    {
        $this->repository->save($appointment);
    }

    public function getAllByPropertyIdAndDate(int $propertyId, string $date): array
    {
        return $this->repository->findByDateAndPropertyId($date, $propertyId);
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }
}
