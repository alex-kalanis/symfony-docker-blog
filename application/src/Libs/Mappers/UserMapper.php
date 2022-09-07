<?php

namespace App\Libs\Mappers;


use kalanis\kw_mapper\Mappers;
use kalanis\kw_mapper\Storage;


/**
 * Class UserMapper
 * @package App\Libs\Mappers
 * Map record entries on database columns
 */
class UserMapper extends Mappers\Database\ADatabase
{
    protected function setMap(): void
    {
        $this->setSource('docker');
        $this->setTable('users');
        $this->setRelation('id', 'u_id');
        $this->setRelation('login', 'u_login');
        $this->setRelation('pass', 'u_pass');
        $this->setRelation('classId', 'u_class_id');
        $this->setRelation('groupId', 'u_group_id');
        $this->setRelation('display', 'u_display');
        $this->setRelation('deleted', 'u_deleted');
        $this->addPrimaryKey('id');
        $this->addForeignKey('groups', '\App\Libs\Mappers\GroupRecord', 'groupId', 'id');
        $this->addForeignKey('classes', '\App\Libs\Mappers\ClassRecord', 'classId', 'id');
        $this->addForeignKey('articles', '\App\Libs\Mappers\ArticleRecord', 'id', 'userId');
    }
}
