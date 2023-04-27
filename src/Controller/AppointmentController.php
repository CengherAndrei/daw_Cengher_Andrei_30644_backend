<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Service\AppointmentService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    private AppointmentService $appointmentService;

    /**
     * @param AppointmentService $appointmentService
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    #[Route('/appointment', name: 'app_appointment')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AppointmentController.php',
        ]);
    }

    #[Route('/api/appointment', name: 'app_add_appointment', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $params = $request->toArray();
        $clientId = $params['clientId'];
        $propertyId = $params['propertyId'];
        $date = $params['date'];

        if(!empty($this->appointmentService->getAllByPropertyIdAndDate($propertyId, $date))) {

            return $this->json([
                'message' => 'There is already an appointment fot this date.'
            ])->setStatusCode(400);
        }

        $appointment = (new Appointment())->setClientId($clientId)
            ->setPropertyId($propertyId)
            ->setDate($date);

        $this->appointmentService->add($appointment);

        return $this->json([
            'message' => 'Appointment successfully added.'
        ]);
    }

    #[Route('/api/appointment/all', name: 'app_get_appointments', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $appointments = $this->appointmentService->getAll();

        return $this->json([
            'appointments' => $appointments
        ]);
    }
}
