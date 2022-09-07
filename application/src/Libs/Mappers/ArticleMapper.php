<?php

namespace App\Libs\Mappers;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_mapper\Mappers;
use kalanis\kw_mapper\Storage;


/**
 * Class ArticleMapper
 * @package App\Libs\Mappers
 * Map record entries on database columns
 */
class ArticleMapper extends Mappers\Database\ADatabase
{
    protected function setMap(): void
    {
        $this->setSource('docker');
        $this->setTable('articles');
        $this->setRelation('id', 'a_id');
        $this->setRelation('userId', 'u_id');
        $this->setRelation('title', 'a_title');
        $this->setRelation('content', 'a_content');
        $this->setRelation('publish', 'a_publish');
        $this->setRelation('deleted', 'a_deleted');
        $this->addPrimaryKey('id');
        $this->addForeignKey('users', '\App\Libs\Mappers\UserRecord', 'userId', 'id');
    }

    /**
     * @throws MapperException
     * @return ArticleRecord[]
     * contains OR in clause
     */
    public function countAvailableArticles(): array
    {
        $query = 'SELECT COUNT(`a_id`) '
            . 'FROM ' . $this->getTable() . ' WHERE `a_publish` IS NOT NULL AND `a_publish` > :date AND (`a_deleted` IS NULL OR `a_deleted` < :date)';
        $params = [':date' => date('Y-m-d H:i')];
        $result = $this->database->query($query, $params);

        $line = reset($result);
        return intval(reset($line));
    }

    /**
     * @param int|null $id
     * @param int|null $offset
     * @param int|null $limit
     * @throws MapperException
     * @return ArticleRecord[]
     * contains OR in clause
     */
    public function getAvailableArticles(?int $id, ?int $offset, ?int $limit): array
    {
        $query = 'SELECT `a_id` AS `id`, `u_id` AS `userId`, `a_title` AS `title`, `a_content` AS `content`, `a_publish` AS `publish`, `a_deleted` AS `deleted` '
            . 'FROM ' . $this->getTable() . ' WHERE `a_publish` IS NOT NULL AND `a_publish` > :date AND (`a_deleted` IS NULL OR `a_deleted` < :date)';
        $params = [':date' => date('Y-m-d H:i')];
        if (!is_null($id)) {
            $query .= ' AND `a_id` = :id';
            $params[':id'] = $id;
        }
        $query .= ' ORDER BY `a_publish` DESC, `a_id` DESC';
        if (!is_null($limit)) {
            if (!is_null($offset)) {
                $query .= sprintf(' LIMIT %d,%d', $offset, $limit);
            } else {
                $query .= sprintf(' LIMIT %d', $limit);
            }
        }
        $result = $this->database->query($query, $params);

        $items = [];
        foreach ($result as $line) {
            $item = new ArticleRecord();
            $item->loadWithData($line);
            $items[] = $item;
        }

        return $items;
    }
}
