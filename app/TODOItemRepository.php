<?php

namespace App;

class TODOItemRepository
{
    /**
     * @var TODOItem[]
     */
    private array $items = [];

    public function __construct()
    {
    }

    public function add(TODOItem $item): void
    {
        $this->items[] = $item;
    }

    public function remove(TODOItem $item): void
    {
        foreach ($this->items as $index => $otherItem) {
            if ($item === $otherItem) {
                unset($this->items[$index]);
            }
        }
    }

    public function get(int $id): ?TODOItem
    {
        foreach ($this->items as $item) {
            if ($item->id() == $id) {
                return $item;
            }
        }
        return null;
    }

    public function check(int $id): void
    {
        if ($item = $this->get($id)) {
            $item->check();
        }
    }

    public function uncheck(int $id): void
    {
        if ($item = $this->get($id)) {
            $item->check();
        }
    }
}