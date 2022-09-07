<?php

namespace App\Libs;


use App\Libs\Mappers\ArticleRecord;
use kalanis\kw_mapper\MapperException;
use kalanis\kw_pager\Interfaces\IPager;


/**
 * Class ArticlesListing
 * List articles
 * @package App\Tasks\Database
 */
class ArticlesListing
{
    /** @var IPager */
    protected $pager = null;

    public function __construct(IPager $pager)
    {
        $this->pager = $pager;
    }

    public function maxAvailable(): void
    {
        $record = new ArticleRecord();
        $this->pager->setMaxResults($record->getMapper()->countAvailableArticles());
    }

    /**
     * @throws MapperException
     * @return ArticleRecord[]
     */
    public function getAvailableArticles(): array
    {
        $record = new ArticleRecord();
        return $record->getMapper()->getAvailableArticles(
            null,
            $this->pager->getOffset(),
            $this->pager->getLimit()
        );
    }

    public function cutContent(ArticleRecord $record): string
    {
        return mb_substr($record->content, 0, 150);
    }
}
