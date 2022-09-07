<?php

namespace App\Tasks;


use App\Libs\ArticlesListing;
use kalanis\kw_clipr\Tasks\ATask;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_paging\Positions;
use kalanis\kw_paging\Render\CliPager;


/**
 * Class Listing
 * @package App\Tasks
 * @property int|null $page
 */
class Listing extends ATask
{
    public function startup(): void
    {
        parent::startup();
        $this->params->addParam('page', 'page', '(\d+)', null, null, 'Table page');
    }

    public function desc(): string
    {
        return 'Listing addresses from Cli';
    }

    public function process(): void
    {
        $pager = new BasicPager();
        $lib = new ArticlesListing($pager);
        $lib->maxAvailable();
        $pager->setLimit(10);
        $pager->setActualPage(intval($this->page));

        $records = $lib->getAvailableArticles();
        foreach ($records as $record) {
            $this->writeLn(sprintf('<yellow>%s</yellow>', $record->title));
            $this->writeLn(str_pad('', 60, '-'));
            $this->writeLn(sprintf('<cyan>%d</cyan>, <magenta>%s</magenta>', $record->id, $record->publish));
            $this->writeLn(str_pad('', 60, '-'));
            $this->writeLn($lib->cutContent($record));
            $this->writeLn(str_pad('', 60, '-'));
        }
        $this->writeLn(new CliPager(new Positions($pager)));
    }
}
