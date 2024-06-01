<?php

namespace App;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class Ask
{
    const ADD_ITEM = "add item";
    const TOGGLE_CHECK_ITEM = "toggle check item";
    const EDIT_ITEM = "edit item";
    const EDIT_ITEM_TEXT = "text";
    const EDIT_ITEM_DUE_DATE = "due date";
    const DELETE_ITEM = "delete item";
    const CHANGE_SORTING = "change sorting";
    const EXIT = "exit";
    private static InputInterface $input;
    private static OutputInterface $output;
    private static QuestionHelper $helper;

    public static function initialize(InputInterface $input, OutputInterface $output, QuestionHelper $helper): void
    {
        self::$input = $input;
        self::$output = $output;
        self::$helper = $helper;
    }

    public static function listAction(): string
    {
        $question = new ChoiceQuestion("Action", [
            self::ADD_ITEM,
            self::TOGGLE_CHECK_ITEM,
            self::EDIT_ITEM,
            self::DELETE_ITEM,
            self::CHANGE_SORTING,
            self::EXIT
        ]);
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    /**
     * @param TODOItem[] $items
     */
    public static function item(array $items): string
    {
        $choices = [];
        foreach ($items as $item) {
            $choices[] = "{$item->getText()} ({$item->getId()})";
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

    public static function text(): ?string
    {
        $question = new Question("Enter text - \n");
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    public static function date(): string
    {
        $question = new Question("Enter the due date - \n");
        $question->setValidator(function ($answer) {
            if ($answer == "") {
                return null;
            }
            try {
                Carbon::parse($answer);
            } catch (InvalidFormatException $e) {
                throw new RuntimeException("Invalid due date");
            }
            return $answer;
        });
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    public static function editDate(?Carbon $getDueDate): string
    {
        $question = new Question("Change due date - \n");
        $question->setAutocompleterValues([$getDueDate]);
        $question->setValidator(function ($answer) {
            if ($answer == "") {
                return null;
            }
            try {
                Carbon::parse($answer);
            } catch (InvalidFormatException $e) {
                throw new RuntimeException("Invalid due date");
            }
            return $answer;
        });
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    public static function editItem(): string
    {
        $question = new ChoiceQuestion("Edit - ", [
            self::EDIT_ITEM_TEXT,
            self::EDIT_ITEM_DUE_DATE
        ]);
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    public static function sort(): string
    {
        $question = new ChoiceQuestion("Sort by - ", [
            TODOlist::SORT_ADDED_DATE,
            TODOlist::SORT_DUE_DATE,
            TODOList::SORT_ALPHABETICAL
        ]);
        return self::$helper->ask(self::$input, self::$output, $question);
    }

}