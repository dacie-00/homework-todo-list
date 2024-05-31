<?php

namespace App;

use Carbon\Carbon;

class TODOList
{
    const SORT_DUE_DATE = "due date";
    const SORT_ADDED_DATE = "added date";
    const SORT_ALPHABETICAL = "alphabetical order";
    /**
     * @var TODOItem[]
     */
    private array $items = [];
    private IDGenerator $idGenerator;
    private string $sort = self::SORT_ADDED_DATE;

    public function __construct()
    {
        $this->idGenerator = new IDGenerator();
    }

    public function add(string $text, ?Carbon $dueDate): TODOItem
    {
        $item = new TODOItem($text, $this->idGenerator->id());
        $item->setCreationDate(Carbon::now());
        if ($dueDate !== null) {
            $item->setDueDate($dueDate);
        }
        $this->items[] = $item;
        return $item;
    }

    public function get(int $id): ?TODOItem
    {
        foreach ($this->items as $item) {
            if ($item->getId() == $id) {
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
        if ($item->getState() == TODOItem::STATUS_UNCHECKED) {
            $item->check();
            return;
        }
        $item->uncheck();
    }

    public function serialize(): string
    {
        $serializedItems = [];
        foreach ($this->items as $item) {
            $serializedItems[] = $item->serialize();
        }
        return json_encode($serializedItems, JSON_PRETTY_PRINT);
    }

    public function deserialize(array $serializedItems): void
    {
        foreach ($serializedItems as $item) {
            $this->items[] = TODOItem::deserialize($item);
        }
        $highestId = 0;
        foreach ($this->getItems() as $item) {
            if ($item->getId() > $highestId) {
                $highestId = $item->getId();
            }
        }
        $this->idGenerator->addHighestId($highestId);
    }

    public function getItems(): array
    {
        switch ($this->sort) {
            case self::SORT_DUE_DATE:
                usort($this->items, function (TODOItem $a, TODOItem $b) {
                    return $a->getDueDate() <=> $b->getDueDate();
                });
                break;
            case self::SORT_ADDED_DATE:
                usort($this->items, function (TODOItem $a, TODOItem $b) {
                    return $a->getCreationDate() <=> $b->getCreationDate();
                });
                break;
            case self::SORT_ALPHABETICAL:
                usort($this->items, function (TODOItem $a, TODOItem $b) {
                    return $a->getText() <=> $b->getText();
                });
                break;

        }
        return $this->items;
    }

    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }
}