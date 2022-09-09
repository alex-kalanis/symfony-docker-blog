<?php

namespace App\Libs\Mappers;


use kalanis\kw_mapper\Mappers;
use kalanis\kw_mapper\Storage;


/**
 * Class LogMapper
 * @package App\Libs\Mappers
 * Map record entries on database columns
 */
class LogMapper extends Mappers\Database\ADatabase
{
    protected function setMap(): void
    {
        $this->setSource('docker');
        $this->setTable('logs');
        $this->setRelation('id', 'log_id');
        $this->setRelation('type', 'log_type');
        $this->setRelation('source', 'log_source');
        $this->setRelation('name', 'log_name');
        $this->setRelation('trace', 'log_trace');
        $this->addPrimaryKey('id');
    }
}
