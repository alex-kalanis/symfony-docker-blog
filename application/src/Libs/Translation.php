<?php

namespace App\Libs;


use App\Libs\Mappers\ArticleRecord;
use Michelf\MarkdownExtra\Interfaces\IMarkdown;


/**
 * Class Translation
 * Translate record into link and back
 * @package App\Tasks\Database
 */
class Translation
{
    /** @var IMarkdown */
    protected $markdown = null;
    /** @var string */
    protected $dateFormat = '';

    public function __construct(IMarkdown $markdown, string $dateFormat = 'Y-m-d')
    {
        $this->markdown = $markdown;
        $this->dateFormat = $dateFormat;
    }

    public function title(ArticleRecord $record): string
    {
        return $record->title;
    }

    public function published(ArticleRecord $record): string
    {
        return date($this->dateFormat, strtotime($record->publish));
    }

    public function preview(ArticleRecord $record): string
    {
        return mb_substr($this->full($record), 0, 150);
    }

    public function full(ArticleRecord $record): string
    {
        return $this->markdown->transform($record->title);
    }
}
