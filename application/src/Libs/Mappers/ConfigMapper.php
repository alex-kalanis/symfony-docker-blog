<?php

namespace App\Libs\Mappers;


use kalanis\kw_mapper\Mappers;
use kalanis\kw_mapper\Storage;


/**
 * Class ConfigMapper
 * @package App\Libs\Mappers
 * Map record entries on database columns
 */
class ConfigMapper extends Mappers\Database\ADatabase
{
    protected function setMap(): void
    {
        $this->setSource('docker');
        $this->setTable('configs');
        $this->setRelation('id', 'cfg_id');
        $this->setRelation('key', 'cfg_key');
        $this->setRelation('value', 'cfg_value');
        $this->setRelation('deleted', 'cfg_deleted');
        $this->addPrimaryKey('id');
    }
}
