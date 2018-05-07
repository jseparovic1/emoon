<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;

/**
 * Class Coin.
 */
class Coin implements ResourceInterface
{
    use Timestampable;

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $symbol;

    /** @var string */
    private $priceUsd;

    /** @var int */
    private $rank;

    /** @var string */
    private $imagePath;

    /** @var integer */
    private $canonicalId;

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

    public function getPriceUsd(): ?string
    {
        return $this->priceUsd;
    }

    public function setPriceUsd(?string $priceUsd): void
    {
        $this->priceUsd = $priceUsd;
    }

    public function getCanonicalId(): ?int
    {
        return $this->canonicalId;
    }

    public function setCanonicalId(?int $canonicalId): void
    {
        $this->canonicalId = $canonicalId;
    }

}
