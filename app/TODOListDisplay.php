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
            $table->addRow([
                $checked,
                $item->getText(),
                $item->getCreationDate()->toDateString(),
                $item->getDueDate() == null ? "" : Carbon::now()->to($item->getDueDate(), CarbonInterface::DIFF_ABSOLUTE),
            ]);
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