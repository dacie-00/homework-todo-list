<?php

namespace App;

class IDGenerator
{
    private array $ids = [];

    public function id(): int
    {
        $newID = end($this->ids) + 1;
        $this->ids[] = $newID;
        return $newID;
    }

    public function addHighestId(int $id): void
    {
        $this->ids[] = $id;
    }
}