<?php
namespace App\Libs\Mappers;


use kalanis\kw_mapper\Interfaces\IEntryType;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Storage;


/**
 * Class ConfigRecord
 * Config record as data object as is in database
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string $deleted
 */
class ConfigRecord extends Records\ASimpleRecord
{
    protected function addEntries(): void
    {
        $this->addEntry('id', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('key', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('value', IEntryType::TYPE_STRING, 65536);
        $this->addEntry('deleted', IEntryType::TYPE_STRING, 32);
        $this->setMapper('\App\Libs\Mappers\ConfigMapper');
    }
}
