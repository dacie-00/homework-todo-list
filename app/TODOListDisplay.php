<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class TODOListDisplay
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param TODOItem[] $items
     */
    public function list(array $items): void
    {
        $table = new Table($this->output);


        foreach ($items as $item) {
            $checked = $item->getState() == TODOItem::STATUS_CHECKED ? "X" : " ";

            $color = "";
            $pastDueDate = false;
            if ($item->getDueDate() != null) {
                if ($item->getDueDate()->lessThan(Carbon::now())) {
                    $color = "red";
                    $pastDueDate = true;
                }
            }
            if ($item->getState() == TODOItem::STATUS_CHECKED) {
                $color = "white";
            }
            $dueDate = $item->getDueDate();
            if ($dueDate !== null) {
                $dueDate = Carbon::now()->to(
                    $item->getDueDate(),
                    CarbonInterface::DIFF_ABSOLUTE,
                    false,
                    2
                );
                if ($pastDueDate) {
                    $dueDate = "-" . $dueDate;
                }
            } else {
                $dueDate = "";
            }
            $row = [
                $checked,
                $item->getText(),
                $item->getCreationDate()->toDateString(),
                $dueDate
            ];
            if ($color != "") {
                foreach ($row as &$cell) {
                    $cell = "<fg=$color>" . $cell . "</>";
                }
            }
            $table->addRow($row);
        }
        $table
            ->setStyle("box")
            ->getStyle()
            ->setPadType(STR_PAD_BOTH);
        $table
            ->setHeaders(["Done", "Task", "Creation date", "Due in"])
            ->render();
    }

}