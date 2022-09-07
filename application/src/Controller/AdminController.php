<?php

namespace App\Controller;


use App\Libs;
use kalanis\kw_mapper\Search\Search;


class AdminController extends AAppController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listing()
    {
        return $this->render('admin/listing.html.twig', [
            'controller_name' => 'AdminController',
            'title' => 'Admin',
            'table' => '',
        ]);
    }

    /**
     * @throws \kalanis\kw_forms\Exceptions\FormsException
     * @throws \kalanis\kw_mapper\MapperException
     * @throws \kalanis\kw_table\core\TableException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logs()
    {
        $table = new Libs\Tables\LogTable($this->inputVariables());
        $table->composeWeb(new Search(new Libs\Mappers\LogRecord()));
        return $this->render('admin/listing.html.twig', [
            'controller_name' => 'AdminController',
            'title' => 'Logs',
            'table' => $table,
        ]);
    }
}
