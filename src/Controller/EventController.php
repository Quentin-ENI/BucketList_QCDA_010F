<?php

namespace App\Controller;

use App\DTO\EventDTO;
use App\Form\EventForm;
use App\Model\Event;
use App\Service\EventAPIService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class EventController extends AbstractController
{

    #[Route('/events', name: 'event_list', methods: ['GET', 'POST'])]
    public function list(
        SerializerInterface $serializer,
        Request $request,
        EventAPIService $eventAPIService
    ): Response
    {
        // Traitement du formulaire
        $eventDTO = new EventDTO();
        $eventForm = $this->createForm(EventForm::class, $eventDTO);

        $eventForm->handleRequest($request);

        $url = EventAPIService::BASE_URL;

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            if ($eventDTO->getCity()) {
                $url .= EventAPIService::URL_EXTENSION_CITY . urlencode($eventDTO->getCity());
            }

            if ($eventDTO->getStartDate()) {
                $startDate = $eventDTO->getStartDate()->format('Y-m-d');
                $url .= EventAPIService::URL_EXTENSION_FIRSTDATE . $startDate;
            }
        }

        $url .= "&limit=20";

        // Appel API externe
        $events = $eventAPIService->fetchEvents($url);

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'eventForm' => $eventForm->createView(),
        ]);
    }
}
