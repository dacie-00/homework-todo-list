<?php

namespace App;

class IDGenerator
{
    private array $ids = [];
    public function id()
    {
        $newID = end($this->ids) + 1;
        $this->ids[] = $newID;
        return $newID;
    }

    public function addHighestId(int $id)
    {
        $this->ids[] = $id;
    }
}