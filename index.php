<?php
declare(strict_types=1);

use App\Ask;
use App\TODOList;
use App\TODOListDisplay;
use Carbon\Carbon;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__ . '/vendor/autoload.php';


$application = new Application();

$run = new class extends Command {
    protected static $defaultName = "run";


    private function extractIDFromChoice(string $choice): int
    {
        $parenthesis = substr($choice, strrpos($choice, "("));
        return (int)substr($parenthesis, 1, strlen($parenthesis) - 2);
    }

    private function getSave(): ?array
    {
        if (file_exists("data/save.json")) {
            return json_decode(file_get_contents("data/save.json"));
        }
        return null;
    }

    private function save(string $data): void
    {
        file_put_contents("data/save.json", $data);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $todoList = new TODOList();
        if ($save = $this->getSave()) {
            $todoList->deserialize($save);
        }
        $todoListDisplay = new TODOListDisplay($output);

        Ask::initialize($input, $output, new QuestionHelper());
        while (true) {
            $todoListDisplay->list($todoList->getItems());
            $action = Ask::listAction();

            switch ($action) {
                case Ask::ADD_ITEM:
                    $name = Ask::text();
                    if ($name == null) {
                        break;
                    }
                    $dueDate = Ask::date();
                    if ($dueDate !== null) {
                        $dueDate = Carbon::parse($dueDate);
                    }
                    $todoList->add($name, $dueDate);
                    $this->save($todoList->serialize());
                    break;
                case Ask::DELETE_ITEM:
                    $id = $this->extractIDFromChoice(Ask::item($todoList->getItems()));
                    $item = $todoList->get($id);
                    $todoList->remove($item);
                    $this->save($todoList->serialize());
                    break;
                case Ask::TOGGLE_CHECK_ITEM:
                    $id = $this->extractIDFromChoice(Ask::item($todoList->getItems()));
                    $item = $todoList->get($id);
                    $todoList->toggleCheck($item);
                    $this->save($todoList->serialize());
                    break;
                case Ask::EDIT_ITEM:
                    $id = $this->extractIDFromChoice(Ask::item($todoList->getItems()));
                    $item = $todoList->get($id);
                    $editAction = Ask::editItem();
                    switch ($editAction) {
                        case Ask::EDIT_ITEM_TEXT:
                            $item->setText(Ask::editText($item->getText()));
                            break;
                        case Ask::EDIT_ITEM_DUE_DATE:
                            $dueDate = Ask::editDate($item->getDueDate());
                            if ($dueDate !== null) {
                                $dueDate = Carbon::parse($dueDate);
                            }
                            $item->setDueDate($dueDate);
                            break;
                    }
                    $this->save($todoList->serialize());
                    break;
                case Ask::CHANGE_SORTING;
                    $todoList->setSort(Ask::sort());
                    break;
                case Ask::EXIT;
                    break 2;
            }
        }

        return Command::SUCCESS;
    }
};

$application->add($run);
$application->setDefaultCommand("run");
$application->run();