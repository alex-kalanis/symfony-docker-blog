<?php

namespace App\Tasks;


use App\Libs\Mappers\ArticleRecord;
use kalanis\kw_clipr\Tasks\ATask;


/**
 * Class Read
 * @package App\Tasks
 * @property int|null $record
 */
class Read extends ATask
{
    public function startup(): void
    {
        parent::startup();
        $this->params->addParam('record', 'record', '(\d+)', null, null, 'Which post will be shown');
    }

    public function desc(): string
    {
        return 'Show any post available';
    }

    public function process(): void
    {
        if (empty($this->record)) {
            $this->sendErrorMessage('Must set which article will be read!');
            return;
        }

        $article = new ArticleRecord();
        $articles = $article->getMapper()->getAvailableArticles($this->record);

        if (empty($articles)) {
            $this->sendErrorMessage('Wanted article has not been found!');
            return;
        }

        /** @var ArticleRecord $article */
        $article = reset($articles);
        $this->writeLn(str_pad('', 60, '-'));
        $this->writeLn('<yellow>' . $article->title . '</yellow>');
        $this->writeLn(str_pad('', 60, '-'));
        $this->writeLn();
        $this->writeLn($article->content);
    }
}
