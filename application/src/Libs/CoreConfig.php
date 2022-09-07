<?php

namespace App\Libs;


use App\Libs\Mappers\ConfigRecord;
use kalanis\kw_confs\Interfaces\IConf;
use kalanis\kw_mapper\MapperException;
use kalanis\kw_mapper\Search\Search;


/**
 * Class CoreConfig
 * Configs in DB
 * @package App\Tasks\Database
 */
class CoreConfig implements IConf
{
    /** @var string */
    protected $part = '';

    /**
     * @param string $part
     */
    public function setPart(string $part): void
    {
        $this->part = $part;
    }

    public function getConfName(): string
    {
        return 'core';
    }

    /**
     * @throws MapperException
     * @return array<int|string, int|string|float|bool>
     */
    public function getSettings(): array
    {
        $search = new Search(new ConfigRecord());
        if (!empty($this->part)) {
            $search->like('key', $this->part . '%');
        }
        $search->null('deleted');
        /** @var ConfigRecord[] $records */
        $records = $search->getResults();
        $result = [];
        foreach ($records as $record) {
            $result[strval($record->key)] = $record->value;
        }
        return $result;
    }
}
