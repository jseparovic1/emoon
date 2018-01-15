<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * Class Coin
 * @package App\Entity
 */
class Coin implements ResourceInterface
{
    use Timestampable;

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $nameCanonical;

    /** @var string */
    private $symbol;

    /** @var string */
    private $priceUsd;

    /** @var string */
    private $marketCap;

    /** @var int */
    private $rank;

    /** @var string */
    private $website;

    /** @var string */
    private $twitter;

    /** @var string */
    private $description;

    /** @var string */
    private $status;

    /** @var string */
    private $imagePath;

    /** @var  ArrayCollection */
    private $exchanges;

    public function __construct()
    {
        $this->exchanges = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website)
    {
        $this->website = $website;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter)
    {
        $this->twitter = $twitter;
    }

    public function setImagePath(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol)
    {
        $this->symbol = $symbol;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank)
    {
        $this->rank = $rank;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getExchanges(): ?ArrayCollection
    {
        return $this->exchanges;
    }

    public function addExchange(Exchange $exchange)
    {
        if ($this->exchanges->contains($exchange)) {
            $this->exchanges->add($exchange);
        }
    }

    public function removeExchange(Exchange $exchange)
    {
        if ($this->exchanges->contains($exchange)) {
            $this->exchanges->removeElement($exchange);
        }
    }

    public function getNameCanonical(): ?string
    {
        return $this->nameCanonical;
    }

    public function setNameCanonical(?string $nameCanonical)
    {
        $this->nameCanonical = $nameCanonical;
    }

    public function getPriceUsd(): ?string
    {
        return $this->priceUsd;
    }

    public function setPriceUsd(?string $priceUsd): void
    {
        $this->priceUsd = $priceUsd;
    }

    public function getMarketCap(): ?string
    {
        return $this->marketCap;
    }

    public function setMarketCap(?string $marketCap): void
    {
        $this->marketCap = $marketCap;
    }
}
