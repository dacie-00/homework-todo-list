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
        rsort($this->ids);
        if ($this->ids[0] > $id) {
            throw new \LogicException("Generator has ID that is higher than the provided ID");
        }
        $this->ids[] = $id;
    }
}