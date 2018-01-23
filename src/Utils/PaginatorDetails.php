<?php

namespace App\Utils;

/**
 * Class PaginatorDetails.
 */
class PaginatorDetails
{
    /** @var string */
    private $route;

    /** @var int */
    private $limit;

    /** @var int */
    private $page;

    /** @var array */
    private $parameters;

    /**
     * PaginatorDetails constructor.
     * @param string $route
     * @param array  $parameters
     */
    public function __construct(string $route, array $parameters)
    {
        $this->route = $route;
        $this->limit = (int) ($parameters['limit'] ?? 10);
        $this->page = (int) ($parameters['page'] ?? 1);
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function route(): string
    {
        return $this->route;
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function page(): int
    {
        return $this->page;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addToParameters(string $key, $value): void
    {
        $this->parameters[$key] = $value;
    }
}
