<?php

namespace App;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class Ask
{
    private static InputInterface $input;
    private static OutputInterface $output;
    private static QuestionHelper $helper;

    const ADD_ITEM = "add item";
    const TOGGLE_CHECK_ITEM = "toggle check item";
    const EDIT_ITEM = "edit item";
    const DELETE_ITEM = "delete item";

    public static function initialize(InputInterface $input, OutputInterface $output, QuestionHelper $helper): void
    {
        self::$input = $input;
        self::$output = $output;
        self::$helper = $helper;
    }

    public static function listAction(): string
    {
        $question = new ChoiceQuestion("Action", [self::ADD_ITEM, self::TOGGLE_CHECK_ITEM, self::EDIT_ITEM, self::DELETE_ITEM]);
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    /**
     * @param TODOItem[] $items
     */
    public static function item(array $items): string
    {
        $choices = [];
        foreach ($items as $item) {
            $choices[] = "{$item->text()} ({$item->id()})";
        }
        $question = new ChoiceQuestion("Action", $choices);
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    public static function editText(string $text): string
    {
        $beginning = trim(substr($text, 0, 50)) . "...";
        $question = new Question("Editing text '$beginning' - \n", $text);
        $question->setAutocompleterValues([$text]);
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    public static function text(): string
    {
        $question = new Question("Enter text - \n");
        return self::$helper->ask(self::$input, self::$output, $question);
    }
}