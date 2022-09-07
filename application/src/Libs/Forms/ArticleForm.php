<?php

namespace App\Libs\Forms;


use App\Libs\Mappers\ArticleRecord;
use kalanis\kw_forms\Controls;
use kalanis\kw_forms\Form;
use kalanis\kw_rules\Interfaces\IRules;


/**
 * Class ArticleForm
 * @package App\Libs\Mappers
 * Create form with mapping onto Article Record
 * @property Controls\Text $title
 * @property Controls\Textarea $content
 * @property Controls\Submit $submit
 */
class ArticleForm extends Form
{
    public function composeEdit(?ArticleRecord $record): void
    {
        $title = $this->addText('title', 'Title', $record ? $record->title : null);
        $title->addRule(IRules::IS_NOT_EMPTY, 'Must be filled!');

        // content input
        $this->addTextarea('content', 'Content', $record ? $record->content : null);

        // submit
        $this->addSubmit('submit', 'Save');
    }
}
