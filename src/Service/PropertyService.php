<?php

namespace App\Service;

use App\Entity\Property;
use App\Enum\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\NonUniqueResultException;

class PropertyService
{
    private PropertyRepository $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository) {
        $this->propertyRepository = $propertyRepository;
    }

    public function add(Property $property): void
    {
        $this->propertyRepository->save($property);
    }

    public function getAll(): array
    {
        return $this->propertyRepository->findAll();
    }

    public function isValidType(string $type): bool
    {
        return $type == PropertyType::APARTMENT || $type == PropertyType::HOUSE ||  $type == PropertyType::OFFICE;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getPropertyById(int $id): ?Property
    {
        return $this->propertyRepository->findById($id);
    }

    public function delete(Property $property): void
    {
        $this->propertyRepository->remove($property);
    }
}
