<?php

namespace App;

use Carbon\Carbon;
use stdClass;

class TODOItem
{
    const STATUS_UNCHECKED = "unchecked";
    const STATUS_CHECKED = "checked";

    private string $text;
    private int $id;
    private string $state = self::STATUS_UNCHECKED;
    private Carbon $creationDate;
    private ?Carbon $dueDate;

    public function __construct(string $text, int $id)
    {
        $this->text = $text;
        $this->id = $id;
        $this->creationDate = Carbon::now();
        $this->dueDate = null;
    }

    public static function deserialize(stdClass $stringItem): TODOItem
    {
        $item = new TODOItem($stringItem->text, $stringItem->id);
        $item->setState($stringItem->state);
        $item->setCreationDate(Carbon::parse($stringItem->creationDate));
        $item->setDueDate(Carbon::parse($stringItem->dueDate));
        return $item;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function check(): void
    {
        $this->setState(self::STATUS_CHECKED);
    }

    public function uncheck(): void
    {
        $this->setState(self::STATUS_UNCHECKED);
    }

    public function serialize(): array
    {
        return [
            "text" => $this->text,
            "id" => $this->id,
            "state" => $this->state,
            "creationDate" => $this->getCreationDate()->toDateTimeString(),
            "dueDate" => $this->getDueDate() == null ? null : $this->getDueDate()->toDateTimeString(),
        ];
    }

    public function getCreationDate(): Carbon
    {
        return $this->creationDate;
    }

    public function setCreationDate(Carbon $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    public function getDueDate(): ?Carbon
    {
        return $this->dueDate;
    }

    public function setDueDate(?Carbon $dueDate): void
    {
        $this->dueDate = $dueDate;
    }
}
