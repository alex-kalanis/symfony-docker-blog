<?php
namespace App\Libs\Mappers;


use kalanis\kw_mapper\Interfaces\IEntryType;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Storage;


/**
 * Class ArticleRecord
 * Article record as data object as is in database
 * @property int $id
 * @property int $userId
 * @property string $title
 * @property string $content
 * @property string $publish
 * @property string $deleted
 * @property \App\Libs\Mappers\UserRecord[] $users
 */
class ArticleRecord extends Records\ASimpleRecord
{
    protected function addEntries(): void
    {
        $this->addEntry('id', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('userId', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('title', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('content', IEntryType::TYPE_STRING, 65536);
        $this->addEntry('publish', IEntryType::TYPE_STRING, 32);
        $this->addEntry('deleted', IEntryType::TYPE_STRING, 32);
        $this->addEntry('users', IEntryType::TYPE_ARRAY, []);
        $this->setMapper('\App\Libs\Mappers\ArticleMapper');
    }
}
