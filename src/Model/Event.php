<?php

namespace App\Model;


use Symfony\Component\Serializer\Attribute\SerializedName;

class Event
{

    #[SerializedName('location_city')]
    private ?string $city = null;
    #[SerializedName('firstdate_begin')]
    private ?\DateTimeImmutable $startDate = null;

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $date): self
    {
        $this->startDate = $date;
        return $this;
    }


}
