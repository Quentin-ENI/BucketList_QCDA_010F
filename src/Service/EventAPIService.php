<?php

namespace App\Service;

use App\Model\Event;
use Symfony\Component\Serializer\SerializerInterface;

class EventAPIService
{
    public const BASE_URL = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda";
    public const URL_EXTENSION_CITY = '&refine.location_city=';
    public const URL_EXTENSION_FIRSTDATE = '&refine.firstdate_begin=';

    public function __construct(
        private SerializerInterface $serializer
    )
    {}

    /**
     * Fetch an API and collect events
     * @param string $url
     * @return array
     */
    public function fetchEvents(string $url): array {
        $content = file_get_contents($url);

        $eventsArray = $this->serializer->decode($content, 'json')['records'];

        $events = [];

        foreach ($eventsArray as $eventElement) {
            $event = $this->serializer->denormalize($eventElement["fields"], Event::class);
            $events[] = $event;
        }

        return $events;
    }
}
