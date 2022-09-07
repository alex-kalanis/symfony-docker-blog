<?php

namespace App\Libs\Tables;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_input\Interfaces\IFiltered;
use kalanis\kw_mapper\Interfaces\IQueryBuilder;
use kalanis\kw_mapper\Search\Search;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\TableException;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\form_kw\Fields as KwField;


/**
 * Class LogTable
 * @package App\Libs\Tables
 * Complete logs table description
 */
class LogTable
{
    /** @var IFiltered */
    protected $variables = null;
    /** @var Table */
    protected $table = null;

    /**
     * @param IFiltered $inputs
     */
    public function __construct(IFiltered $inputs)
    {
        $this->variables = $inputs;
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
        $this->table->addOrderedColumn('ID', new Columns\Basic('id'), new KwField\TextExact());
        $this->table->addOrderedColumn('Type', new Columns\Basic('type'), new KwField\TextContains());
        $this->table->addOrderedColumn('Source', new Columns\Basic('source'), new KwField\TextContains());
        $this->table->addOrderedColumn('Name', new Columns\Basic('name'), new KwField\TextContains());
        $this->table->addColumn('Trace', new Columns\Basic('trace'));

        // sorting and connecting datasource
        $this->table->addOrdering('id',IQueryBuilder::ORDER_DESC);
        $this->table->addDataSetConnector(new \kalanis\kw_connect\search\Connector($search));

        // records per page
        $this->table->getPager()->getPager()->setLimit(10);
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
