<?php

namespace App\Libs;


use App\Libs\Mappers\ArticleRecord;
use kalanis\kw_connect\core\Interfaces\IOrder;
use kalanis\kw_mapper\MapperException;
use kalanis\kw_mapper\Search\Search;


/**
 * Class LinkTranslation
 * Translate record into link and back
 * @package App\Tasks\Database
 */
class LinkTranslation
{
    public function getAsLink(int $id, string $title): string
    {
        return sprintf('%s-%d', urlencode($title), $id);
    }

    /**
     * @param string $link
     * @throws MapperException
     * @return ArticleRecord|null
     */
    public function getAsRecord(string $link): ?ArticleRecord
    {
        list(,,$id) = explode('-', $link, 2);
        $search = new Search(new ArticleRecord());
        $search->exact('id', $id);
        $search->orderBy('id', IOrder::ORDER_ASC);
        $objects = $search->getResults();
        if (!empty($objects)) {
            $object = reset($objects);
            return $object;
        } else {
            return null;
        }
    }
}
