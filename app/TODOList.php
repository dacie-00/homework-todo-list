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
                $this->items = array_values($this->items);
            }
        }
    }

    public function toggleCheck(TODOItem $item): void
    {
        if ($item->state() == TODOItem::STATUS_UNCHECKED) {
            $item->check();
            return;
        }
        $item->uncheck();
    }

    public function items(): array
    {
        return $this->items;
    }

    public function serialize(): string
    {
        $serializedItems = [];
        foreach ($this->items as $item) {
            $serializedItems[] = $item->serialize();
        }
        return json_encode($serializedItems);
    }

    public function deserialize(array $serializedItems): void
    {
        foreach ($serializedItems as $item) {
            $this->items[] = TODOItem::deserialize($item);
        }
        $highestId = 0;
        foreach ($this->items() as $item){
            if ($item->id() > $highestId) {
                $highestId = $item->id();
            }
        }
        $this->idGenerator->addHighestId($highestId);
    }
}