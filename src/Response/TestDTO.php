<?php

declare(strict_types=1);

namespace AdgoalCommon\AffiliTest\Response;

/**
 * Class TestDTO.
 */
class TestDTO
{
    /**
     * @var string[]
     */
    private $traceList = [];

    /**
     * @var int[]
     */
    private $codesList = [];

    public static function fromRaw(array $raw)
    {
        $dto = new static();
        $dto->traceList = $raw['data'];

        if (isset($raw['meta']['codes'])) {
            $dto->codesList = $raw['meta']['codes'];
        }

        return $dto;
    }

    /**
     * @return string[]
     */
    public function getTraceList(): array
    {
        return $this->traceList;
    }

    /**
     * @return int[]
     */
    public function getCodesList(): array
    {
        return $this->codesList;
    }

    public function getTraceByNumber(int $number): ?string
    {
        if (!isset($this->traceList[$number])) {
            return null;
        }

        return $this->traceList[$number];
    }

    /**
     * @param int $number
     *
     * @return int|mixed
     */
    public function getCodeByNumber(int $number): ?int
    {
        if (!isset($this->codesList[$number])) {
            return null;
        }

        return $this->codesList[$number];
    }
}
