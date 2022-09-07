<?php
namespace App\Libs\Mappers;


use kalanis\kw_mapper\Interfaces\IEntryType;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Storage;


/**
 * Class ClassRecord
 * Class record as data object as is in database
 * @property int $id
 * @property string $title
 */
class ClassRecord extends Records\ASimpleRecord
{
    protected function addEntries(): void
    {
        $this->addEntry('id', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('title', IEntryType::TYPE_STRING, 1024);
        $this->setMapper('\App\Libs\Mappers\ClassMapper');
    }

    public static function getAll(): array
    {
        $lib = new static();
        $content = $lib->loadMultiple();
        $result = [];
        foreach ($content as $item) {
            $result[intval($item->getEntry('id')->getData())] = strval($item->getEntry('title')->getData());
        }
        return $result;
    }
}
