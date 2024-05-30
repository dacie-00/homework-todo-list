<?php

namespace App;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class Ask
{
    private static InputInterface $input;
    private static OutputInterface $output;
    private static QuestionHelper $helper;

    const CHECK_ITEM = "check item";
    const EDIT_ITEM = "edit item";
    const DELETE_ITEM = "delete item";

    static function initialize(InputInterface $input, OutputInterface $output, QuestionHelper $helper): void
    {
        self::$input = $input;
        self::$output = $output;
        self::$helper = $helper;
    }

    static function listAction(): string
    {
        $question = new ChoiceQuestion("Action", [self::CHECK_ITEM, self::EDIT_ITEM, self::DELETE_ITEM]);
        return self::$helper->ask(self::$input, self::$output, $question);
    }

    /**
     * @param TODOItem[] $items
     */
    static function item(array $items): string
    {
        $choices = [];
        foreach ($items as $item) {
            $choices[] = "{$item->text()} ({$item->id()})";
        }
        $question = new ChoiceQuestion("Action", $choices);
        return self::$helper->ask(self::$input, self::$output, $question);
    }
}