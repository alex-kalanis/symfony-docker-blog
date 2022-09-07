<?php

namespace App\Libs\Mappers;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_mapper\Mappers;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Storage;


/**
 * Class ClassMapper
 * @package App\Libs\Mappers
 * Map record entries on database columns
 */
class ClassMapper extends Mappers\AMapper
{
    use Mappers\File\TTranslate;

    /** @var array<int, array<int, int|string>> */
    protected $records = [];

    /** @var array<int, array<int, int|string>> */
    protected $data = [
        [1, 'Maintainer'],
        [2, 'Admin'],
        [3, 'User'],
    ];

    protected function setMap(): void
    {
        $this->setSource('code');
        $this->setRelation('id', 0);
        $this->setRelation('title', 1);
        $this->addPrimaryKey('id');
    }

    public function getAlias(): string
    {
        return 'code_classes';
    }

    protected function insertRecord(Records\ARecord $record): bool
    {
        throw new MapperException('Cannot insert into static array!');
    }

    protected function updateRecord(Records\ARecord $record): bool
    {
        throw new MapperException('Cannot update in static array!');
    }

    public function countRecord(Records\ARecord $record): int
    {
        $matches = $this->findMatched($record);
        return count($matches);
    }

    public function loadMultiple(Records\ARecord $record): array
    {
        $toLoad = $this->findMatched($record);

        $result = [];
        foreach ($toLoad as $key) {
            $result[] = $this->records[$key];
        }
        return $result;
    }

    protected function loadRecord(Records\ARecord $record): bool
    {
        $matches = $this->findMatched($record);
        if (empty($matches)) { // nothing found
            return false;
        }

        $dataLine = & $this->records[reset($matches)];
        foreach ($this->relations as $objectKey => $recordKey) {
            $entry = $record->getEntry($objectKey);
            $entry->setData($dataLine->offsetGet($objectKey), true);
        }
        return true;
    }

    /**
     * @param Records\ARecord $record
     * @param bool $usePks
     * @param bool $wantFromStorage
     * @throws MapperException
     * @return string[]|int[]
     */
    private function findMatched(Records\ARecord $record, bool $usePks = false, bool $wantFromStorage = false): array
    {
        $this->loadOnDemand($record);

        $toProcess = array_keys($this->records);
        $toProcess = array_combine($toProcess, $toProcess);

        // through relations
        foreach ($this->relations as $objectKey => $recordKey) {
            if (!$record->offsetExists($objectKey)) { // nothing with unknown relation key in record
                // @codeCoverageIgnoreStart
                if ($usePks && in_array($objectKey, $this->primaryKeys)) { // is empty PK
                    return []; // probably error?
                }
                continue;
                // @codeCoverageIgnoreEnd
            }
            if (empty($record->offsetGet($objectKey))) { // nothing with empty data
                if ($usePks && in_array($objectKey, $this->primaryKeys)) { // is empty PK
                    return [];
                }
                continue;
            }

            foreach ($this->records as $knownKey => $knownRecord) {
                if ( !isset($toProcess[$knownKey]) ) { // not twice
                    continue;
                }
                if ($usePks && !in_array($objectKey, $this->primaryKeys)) { // is not PK
                    continue;
                }
                if ($wantFromStorage && !$knownRecord->getEntry($objectKey)->isFromStorage()) { // look through only values known in storage
                    continue;
                }
                if ( !$knownRecord->offsetExists($objectKey) ) { // unknown relation key in record is not allowed into compare
                    // @codeCoverageIgnoreStart
                    unset($toProcess[$knownKey]);
                    continue;
                }
                // @codeCoverageIgnoreEnd
                if ( empty($knownRecord->offsetGet($objectKey)) ) { // empty input is not need to compare
                    unset($toProcess[$knownKey]);
                    continue;
                }
                if ( strval($knownRecord->offsetGet($objectKey)) != strval($record->offsetGet($objectKey)) ) {
                    unset($toProcess[$knownKey]);
                    continue;
                }
            }
        }

        return $toProcess;
    }

    /**
     * More records on one mapper - reload with correct one
     * @param Records\ARecord $record
     * @throws MapperException
     */
    private function loadOnDemand(Records\ARecord $record): void
    {
        if (empty($this->records)) {
            $this->loadSource($record);
        }
    }

    /**
     * @param Records\ARecord $record
     * @throws MapperException
     */
    private function loadSource(Records\ARecord $record): void
    {
        $lines = $this->data;
        $records = [];
        foreach ($lines as &$line) {

            $item = clone $record;

            foreach ($this->relations as $objectKey => $recordKey) {
                $entry = $item->getEntry($objectKey);
                $entry->setData($this->translateTypeFrom($entry->getType(), $line[$recordKey]), true);
            }
            $records[] = $item;
        }
        $this->records = $records;
    }

    protected function deleteRecord(Records\ARecord $record): bool
    {
        throw new MapperException('Cannot delete in static array!');
    }
}
