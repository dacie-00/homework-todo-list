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

    public function populate($ids)
    {
        $this->ids = $ids;
    }

}