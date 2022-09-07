<?php
namespace App\Libs\Mappers;


use kalanis\kw_mapper\Interfaces\IEntryType;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Search\Search;
use kalanis\kw_mapper\Storage;


/**
 * Class GroupRecord
 * Group record as data object as is in database
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $phone
 * @property string $email
 * @property string $note
 * @property string $deleted
 */
class GroupRecord extends Records\ASimpleRecord
{
    protected function addEntries(): void
    {
        $this->addEntry('id', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('name', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('desc', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('deleted', IEntryType::TYPE_STRING, 32);
        $this->setMapper('\App\Libs\Mappers\GroupMapper');
    }

    public static function getAll(): array
    {
        $lib = new Search(new static());
        $lib->useOr();
        $lib->notNull('deleted');
        $lib->to('deleted', date('Y-m-d H:i:s'));
        $content = $lib->getResults();
        $result = [];
        foreach ($content as $item) {
            $result[intval($item->getEntry('id')->getData())] = strval($item->getEntry('name')->getData());
        }
        return $result;
    }
}
