<?php

namespace App\Libs\Tables;


use kalanis\kw_address_handler\Forward;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_input\Interfaces\IFiltered;
use kalanis\kw_mapper\Interfaces\IQueryBuilder;
use kalanis\kw_mapper\Search\Search;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\TableException;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\form_kw\Fields as KwField;


/**
 * Class ConfigTable
 * @package App\Libs\Tables
 * Complete configs table description
 */
class ConfigTable
{
    /** @var IFiltered */
    protected $variables = null;
    /** @var Table */
    protected $table = null;
    /** @var Forward */
    protected $forward = null;

    /**
     * @param IFiltered $inputs
     */
    public function __construct(IFiltered $inputs)
    {
        $this->variables = $inputs;
        $this->forward = new Forward();
    }

    /**
     * @param Search $search
     * @throws FormsException
     * @throws TableException
     */
    public function composeWeb(Search $search): void
    {
        $helper = new \kalanis\kw_table\kw\Helper();
        $helper->fillKwPage($this->variables);
        $this->table = $helper->getTable();

        $this->table->setDefaultHeaderFilterFieldAttributes(['style' => 'width:100%']);

        // columns
        $this->table->addOrderedColumn('ID', new Columns\Func('id', [$this, 'idLink']), new KwField\TextExact());
        $this->table->addOrderedColumn('Key', new Columns\Bold('key'), new KwField\TextContains());
        $this->table->addOrderedColumn('Value', new Columns\Basic('value'), new KwField\TextContains());

        $columnActions = new Columns\Multi('&nbsp;&nbsp;', 'id');
        $columnActions->addColumn(new Columns\Func('id', [$this, 'editLink']));
        $columnActions->addColumn(new Columns\Func('id', [$this, 'deleteLink']));
        $columnActions->style('width:200px', new Rules\Always());

        $this->table->addColumn('Actions', $columnActions);

        // sorting and connecting datasource
        $this->table->addOrdering('id',IQueryBuilder::ORDER_DESC);
        $this->table->addDataSetConnector(new \kalanis\kw_connect\search\Connector($search));

        // records per page
        $this->table->getPager()->getPager()->setLimit(10);
    }

    public function idLink($id): string
    {
        $this->forward->setLink('/admin/config-edit/' . $id);
        $this->forward->setForward('/admin/users');
        return sprintf('<a href="%s" class="button">%s</a>',
            $this->forward->getLink(),
            strval($id)
        );
    }

    public function editLink($id): string
    {
        $this->forward->setLink('/admin/config-edit/' . $id);
        $this->forward->setForward('/admin/users');
        return sprintf('<a href="%s" title="%s" class="button button-edit"> &#x1F589; </a>',
            $this->forward->getLink(),
            'Update'
        );
    }

    public function deleteLink($id): string
    {
        $this->forward->setLink('/admin/config-remove/' . $id);
        $this->forward->setForward('/admin/users');
        return sprintf('<a href="%s" title="%s" class="button button-delete"> &#x1F7AE; </a>',
            $this->forward->getLink(),
            'Delete'
        );
    }

    /**
     * @throws TableException
     * @throws ConnectException
     * @return string
     */
    public function __toString()
    {
        return $this->table->render();
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
