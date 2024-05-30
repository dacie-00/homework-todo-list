<?php

namespace App;

class TODOItem
{
    const STATUS_UNCHECKED = "unchecked";
    const STATUS_CHECKED = "checked";

    private string $text;
    private int $id;
    private string $state = self::STATUS_UNCHECKED;

    public function __construct(string $text, int $id)
    {
        $this->text = $text;
        $this->id = $id;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): int
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
        return ["text" => $this->text, "id" => $this->id, "state" => $this->state];
    }

    public static function deserialize(\stdClass $stringItem): TODOItem
    {
        var_dump($stringItem);
        $item = new TODOItem($stringItem->text, $stringItem->id);
        $item->setState($stringItem->state);
        return $item;
    }
}
