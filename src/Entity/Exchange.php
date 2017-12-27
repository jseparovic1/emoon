<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * Class Exchange
 * @package App\Entity
 */
class Exchange implements ResourceInterface
{
    use Timestampable;

    /** @var mixed */
    protected $id;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $description;

    /** @var  string */
    protected $website;

    /** @var  string */
    protected $affiliateLink;

    /** @var  string */
    protected $twitter;

    /** @var  ArrayCollection */
    protected $coins;

    public function __construct()
    {
        $this->coins = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website)
    {
        $this->website = $website;
    }

    public function getAffiliateLink(): ?string
    {
        return $this->affiliateLink;
    }

    public function setAffiliateLink(?string $affiliateLink)
    {
        $this->affiliateLink = $affiliateLink;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return ArrayCollection|PersistentCollection
     */
    public function getCoins()
    {
        return $this->coins;
    }

    public function addCoin(Coin $coin)
    {
        if (!$this->coins->contains($coin)) {
            $this->coins->add($coin);
            $coin->addExchange($this);
        }
    }

    public function removeCoin(Coin $coin)
    {
        if ($this->coins->contains($coin)) {
            $this->coins->removeElement($coin);
            $coin->removeExchange($this);
        }
    }
}
