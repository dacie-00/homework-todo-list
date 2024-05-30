<?php
declare(strict_types=1);

use App\Ask;
use App\TODOItem;
use App\TODOList;
use App\TODOListDisplay;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

require_once __DIR__ . '/vendor/autoload.php';


$application = new Application();

$start = new class extends Command
{
    protected static $defaultName = "start";


    private function extractIDFromChoice(string $choice): int
    {
        $parenthesis = substr($choice, strrpos($choice, "("));
        return (int)substr($parenthesis, 1, strlen($parenthesis) - 2);
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $todoList = new TODOList();
        if (file_exists("save")) {
            $todoList->deserialize(json_decode(file_get_contents("save")));
            echo json_last_error();
        }
        $todoListDisplay = new TODOListDisplay($output);

        Ask::initialize($input, $output, new Symfony\Component\Console\Helper\QuestionHelper());
        while(true){
            $todoListDisplay->list($todoList->items());
            $action = Ask::listAction();

            switch ($action) {
                case Ask::ADD_ITEM:
                    $name = Ask::text();
                    $todoList->add($name);
                    break;
                case Ask::DELETE_ITEM:
                    $id = $this->extractIDFromChoice(Ask::item($todoList->items()));
                    $item = $todoList->get($id);
                    $todoList->remove($item);
                    break;
                case Ask::TOGGLE_CHECK_ITEM:
                    $id = $this->extractIDFromChoice(Ask::item($todoList->items()));
                    $item = $todoList->get($id);
                    $todoList->toggleCheck($item);
                    break;
                case Ask::EDIT_ITEM:
                    $id = $this->extractIDFromChoice(Ask::item($todoList->items()));
                    $item = $todoList->get($id);
                    $item->setText(Ask::editText($item->text()));
                    break;
                case Ask::SAVE:
                    file_put_contents("save", $todoList->serialize());
            }
        }


        return Command::SUCCESS;
    }
};

$application->add($start);
$application->setDefaultCommand("start");
$application->run();