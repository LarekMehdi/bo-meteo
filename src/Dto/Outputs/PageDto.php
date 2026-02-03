<?php

namespace App\Dto\Outputs;

class PageDto
{
    /**
     * @var array<T>
     */
    private array $datas;

    private int $totalElements;

    public function __construct(array $datas, int $totalElements)
    {
        $this->datas = $datas;
        $this->totalElements = $totalElements;
    }

    /**
     * @return array<T>
     */
    public function getDatas(): array
    {
        return $this->datas;
    }

    /**
     * @param array<T> $datas
     */
    public function setDatas(array $datas): self
    {
        $this->datas = $datas;

        return $this;
    }

    public function getTotalElements(): int
    {
        return $this->totalElements;
    }

    public function setTotalElements(int $totalElements): self
    {
        $this->totalElements = $totalElements;

        return $this;
    }
}
