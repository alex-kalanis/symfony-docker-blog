<?php
namespace App\Libs\Mappers;


use kalanis\kw_mapper\Interfaces\IEntryType;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Storage;


/**
 * Class LogRecord
 * Log record as data object as is in database
 * @property int $id
 * @property int $type
 * @property string $source
 * @property string $name
 * @property string $trace
 */
class LogRecord extends Records\ASimpleRecord
{
    protected function addEntries(): void
    {
        $this->addEntry('id', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('type', IEntryType::TYPE_INTEGER, 1024);
        $this->addEntry('source', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('name', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('trace', IEntryType::TYPE_STRING, 65536);
        $this->setMapper('\App\Libs\Mappers\AddressMapper');
    }
}
