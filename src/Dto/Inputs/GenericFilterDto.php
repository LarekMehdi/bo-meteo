<?php

namespace App\Dto\Inputs;

use Symfony\Component\Validator\Constraints as Assert;

class GenericFilterDto
{
    #[Assert\Positive(message: 'Page must be positive')]
    public int $page = 1;

    #[Assert\Positive(message: 'Limit must be positive')]
    public int $limit = 10;

    public function __construct(?int $page = null, ?int $limit = null)
    {
        if (null !== $page) {
            $this->page = max($page, 1);
        }
        if (null !== $limit) {
            $this->limit = max($limit, 1);
        }
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }
}
