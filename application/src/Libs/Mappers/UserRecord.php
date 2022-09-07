<?php
namespace App\Libs\Mappers;


use kalanis\kw_mapper\Interfaces\IEntryType;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Storage;


/**
 * Class UserRecord
 * User record as data object as is in database
 * @property int $id
 * @property string $login
 * @property string $pass
 * @property int $classId
 * @property int $groupId
 * @property string $display
 * @property string $deleted
 * @property \App\Libs\Mappers\GroupRecord[] $groups
 * @property \App\Libs\Mappers\ClassRecord[] $classes
 * @property \App\Libs\Mappers\ArticleRecord[] $articles
 */
class UserRecord extends Records\ASimpleRecord
{
    protected function addEntries(): void
    {
        $this->addEntry('id', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('login', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('pass', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('classId', IEntryType::TYPE_INTEGER, 100);
        $this->addEntry('groupId', IEntryType::TYPE_INTEGER, 100);
        $this->addEntry('display', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('deleted', IEntryType::TYPE_STRING, 32);
        $this->addEntry('groups', IEntryType::TYPE_ARRAY, []);
        $this->addEntry('classes', IEntryType::TYPE_ARRAY, []);
        $this->addEntry('articles', IEntryType::TYPE_ARRAY, []);
        $this->setMapper('\App\Libs\Mappers\UserMapper');
    }
}
