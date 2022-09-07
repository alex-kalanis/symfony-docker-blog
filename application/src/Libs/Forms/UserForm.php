<?php

namespace App\Libs\Forms;


use App\Libs\Mappers\ClassRecord;
use App\Libs\Mappers\GroupRecord;
use App\Libs\Mappers\UserRecord;
use kalanis\kw_forms\Controls;
use kalanis\kw_forms\Form;
use kalanis\kw_rules\Interfaces\IRules;


/**
 * Class UserForm
 * @package App\Libs\Mappers
 * Create form with mapping onto User Record
 * @property Controls\Text $login
 * @property Controls\Password $pass
 * @property Controls\Text $display
 * @property Controls\Select $classId
 * @property Controls\Select $groupId
 * @property Controls\Submit $submit
 */
class UserForm extends Form
{
    public function composeFull(?UserRecord $record): void
    {
        // class input
        $this->addSelect('classId', 'Class', $record ? $record->classId : null, ClassRecord::getAll());

        // group input
        $this->addSelect('groupId', 'Groups', $record ? $record->groupId : null, GroupRecord::getAll());

        $this->composeEdit($record);
    }

    public function composeEdit(?UserRecord $record): void
    {
        // first name input
        $login = $this->addText('login', 'Login', $record ? $record->login : null);
        $login->addRule(IRules::IS_NOT_EMPTY, 'Must be filled!');

        // password input
        $this->addPassword('pass', 'Password');

        // phone input
        $this->addText('display', 'Show name', $record ? $record->display : null);

        // submit
        $sub = $this->addSubmit('submit', 'Save');
        $sub->addRule(IRules::SATISFIES_CALLBACK, 'Must be unique!', [$this, 'uniqueData']);
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws \kalanis\kw_mapper\MapperException
     */
    public function uniqueData($value): bool
    {
        if (!$login = $this->getControl('login')) {
            return true;
        }
        $addr = new UserRecord();
        $addr->login = strval($login->getValue());
        return 1 >= $addr->count();
    }
}
