<?php

namespace App\Libs\Mappers;


use kalanis\kw_mapper\Mappers;
use kalanis\kw_mapper\Storage;


/**
 * Class GroupMapper
 * @package App\Libs\Mappers
 * Map record entries on database columns
 */
class GroupMapper extends Mappers\Database\ADatabase
{
    protected function setMap(): void
    {
        $this->setSource('docker');
        $this->setTable('groups');
        $this->setRelation('id', 'g_id');
        $this->setRelation('name', 'g_name');
        $this->setRelation('desc', 'g_desc');
        $this->setRelation('deleted', 'g_deleted');
        $this->addPrimaryKey('id');
    }
}
