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
        $this->setRelation('id', 'l_id');
        $this->setRelation('type', 'l_type');
        $this->setRelation('source', 'l_source');
        $this->setRelation('name', 'l_name');
        $this->setRelation('trace', 'l_trace');
        $this->addPrimaryKey('id');
    }
}
