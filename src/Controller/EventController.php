<?php

namespace App\Controller;

use App\Form\EventForm;
use App\Model\Event;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class EventController extends AbstractController
{
    private const URL = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda";

    #[Route('/event', name: 'app_event', methods: ['GET', 'POST'])]
    public function index(
        SerializerInterface $serializer,
        Request $request
    ): Response
    {
        $url = self::URL;

        $events = [];

        $event = new Event();
        $eventForm = $this->createForm(EventForm::class, $event);

        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $varCity = '&refine.location_city=';
            $varDate = '&refine.firstdate_begin=';

            if ($event->getCity()) {
                $url .= $varCity . urlencode($event->getCity());
            }

            if ($event->getStartDate()) {
                $startDate = $event->getStartDate()->format('Y-m-d');
                $url .= $varDate . $startDate;
            }

            //Call API
            $content = file_get_contents($url);
            $events = $serializer->decode($content, 'json')['records'];
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'eventForm' => $eventForm->createView(),
        ]);
    }
}
