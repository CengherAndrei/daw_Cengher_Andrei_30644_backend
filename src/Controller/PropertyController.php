<?php

namespace App\Controller;


use App\Entity\Property;
use App\Service\PropertyService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService) {
        $this->propertyService = $propertyService;
    }

    #[Route('/api/property', name: 'app_add_property', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $params = $request->toArray();

        try {
            $type = $this->propertyService->isValidType($params['type']) ? $params['type'] : throw new Exception('Invalid type');
            $property = (new Property())->setAddress($params['address'])->setCity($params['city'])->setPrice($params['price'])->setToBuy($params['toBuy'])->setSurface($params['surface'])->setType($type);
            $this->propertyService->add($property);
        } catch (Exception $e) {
            return $this->json([
                'message' => 'Bad request. Missing or invalid parameters.',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }

        return $this->json([
            'message' => 'Property successfully added!'
        ]);
    }

    #[Route('/api/property/all', name: 'app_get_all_properties', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $properties = $this->propertyService->getAll();

        return $this->json([
           'properties' => $properties
        ]);
    }

    #[Route('/api/property', name: 'app_edit_property', methods: ['PUT'])]
    public function edit(Request $request): JsonResponse
    {
        $params = $request->toArray();
        $id = $params['id'] ?? null;
        $type = $params['type'] ?? null;
        $city = $params['city'] ?? null;
        $price = $params['price'] ?? null;
        $address = $params['address'] ?? null;
        $toBuy = $params['toBuy'] ?? null;
        $surface = $params['surface'] ?? null;

        if(!$id) {

            return $this->json([
                'message' => 'Invalid request.'
            ])->setStatusCode(500);
        }

        $property = $this->propertyService->getPropertyById($id);
        if(!$property) {

            return $this->json([
                'message' => 'Invalid id given.'
            ])->setStatusCode(400);
        }

        if($type) $property->setType($type);
        if($city) $property->setCity($city);
        if($price) $property->setPrice($price);
        if($address) $property->setAddress($address);
        if($toBuy) $property->setToBuy($toBuy);
        if($surface) $property->setSurface($surface);

        $this->propertyService->add($property);

        return $this->json([
            'message' => 'Property successfully updated.'
        ]);
    }

    #[Route('/api/property', name: 'app_get_property', methods: ['GET'])]
    public function getPropertyById(Request $request): JsonResponse
    {
        $propertyId = $request->get("id") ?? null;
        if($propertyId) {
            try {
                $property = $this->propertyService->getPropertyById($propertyId);

                return $this->json([
                    'property' => $property
                ]);
            } catch (Exception) {

                return $this->json([
                    'message' => 'Invalid id given.'
                ])->setStatusCode(400);
            }
        }

        return $this->json([
            'message' => 'Invalid request. Missing id.'
        ])->setStatusCode(500);
    }

    #[Route('/api/property', name: 'app_delete_property', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $propertyId = $request->get("id") ?? null;

        if($propertyId) {
            try {
                $property = $this->propertyService->getPropertyById($propertyId);
                $this->propertyService->delete($property);

                return $this->json([
                    'message' => 'Property successfully deleted.'
                ]);
            } catch (Exception) {

                return $this->json([
                    'message' => 'Invalid id given.'
                ])->setStatusCode(400);
            }
        }

        return $this->json([
            'message' => 'Invalid request. Missing id.'
        ])->setStatusCode(500);
    }
}
