<?php

namespace App\Libs\Forms;


use App\Libs\Mappers\ConfigRecord;
use kalanis\kw_forms\Controls;
use kalanis\kw_forms\Form;
use kalanis\kw_rules\Interfaces\IRules;


/**
 * Class ConfigForm
 * @package App\Libs\Mappers
 * Create form with mapping onto Config Record
 * @property Controls\Text $key
 * @property Controls\Text $value
 * @property Controls\Submit $submit
 */
class ConfigForm extends Form
{
    public function composeEdit(?ConfigRecord $record): void
    {
        // first name input
        $key = $this->addText('key', 'Key', $record ? $record->key : null);
        $key->addRule(IRules::IS_NOT_EMPTY, 'Must be filled!');

        // first name input
        $this->addText('value', 'Value', $record ? $record->value : null);

        // submit
        $this->addSubmit('submit', 'Save');
    }
}
