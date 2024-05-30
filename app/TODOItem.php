<?php

namespace App;

class TODOItem
{
    const STATUS_UNCHECKED = "unchecked";
    const STATUS_CHECKED = "checked";

    private string $text;
    private string $state = self::STATUS_UNCHECKED;

    public function __construct(string $text)
    {
        $this->text = $text;
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
}
