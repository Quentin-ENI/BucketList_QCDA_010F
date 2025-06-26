<?php

namespace App\Model;


use Symfony\Component\Serializer\Attribute\SerializedName;

class Event
{

    #[SerializedName('daterange_fr')]
    private ?string $daterange = null;
    private ?string $thumbnail = null;
    #[SerializedName('title_fr')]
    private ?string $title = null;
    #[SerializedName('location_city')]
    private ?string $city = null;
    #[SerializedName('location_name')]
    private ?string $locationName = null;
    #[SerializedName('location_address')]
    private ?string $locationAddress = null;
    #[SerializedName('description_fr')]
    private ?string $description = null;
    #[SerializedName('canonicalurl')]
    private ?string $canonicalUrl = null;

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getDaterange(): ?string
    {
        return $this->daterange;
    }

    public function setDaterange(?string $daterange): void
    {
        $this->daterange = $daterange;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getLocationName(): ?string
    {
        return $this->locationName;
    }

    public function setLocationName(?string $locationName): void
    {
        $this->locationName = $locationName;
    }

    public function getLocationAddress(): ?string
    {
        return $this->locationAddress;
    }

    public function setLocationAddress(?string $locationAddress): void
    {
        $this->locationAddress = $locationAddress;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(?string $canonicalUrl): void
    {
        $this->canonicalUrl = $canonicalUrl;
    }
}
