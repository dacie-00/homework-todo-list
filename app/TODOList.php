<?php

namespace App;

class TODOList
{
    /**
     * @var TODOItem[]
     */
    private array $items = [];
    private IDGenerator $idGenerator;

    public function __construct()
    {
        $this->idGenerator = new IDGenerator();
    }

    public function add(string $text): TODOItem
    {
        $item = new TODOItem($text, $this->idGenerator->id());
        $this->items[] = $item;
        return $item;
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

    public function remove(TODOItem $item): void
    {
        foreach ($this->items as $index => $otherItem) {
            if ($item === $otherItem) {
                unset($this->items[$index]);
            }
        }
    }

    public function check(TODOItem $item): void
    {
        $item->check();
    }

    public function uncheck(TODOItem $item): void
    {
        $item->uncheck();
    }

    public function items(): array
    {
        return $this->items;
    }
}