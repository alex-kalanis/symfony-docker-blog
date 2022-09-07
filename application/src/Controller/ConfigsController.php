<?php

namespace App\Controller;


use App\Libs;
use kalanis\kw_forms\Adapters\InputVarsAdapter;
use kalanis\kw_mapper\Adapters\DataExchange;
use kalanis\kw_mapper\Search\Search;
use Symfony\Component\HttpFoundation\Request;


class ConfigsController extends AAppController
{
    /**
     * @param Request $request
     * @throws \kalanis\kw_forms\Exceptions\FormsException
     * @throws \kalanis\kw_mapper\MapperException
     * @throws \kalanis\kw_table\core\TableException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listing(Request $request)
    {
        $showDeleted = boolval(intval(strval($request->request->get('alsoDeleted', ''))));
        $search = new Search(new Libs\Mappers\ConfigRecord());
        if (!$showDeleted) {
            $search->null('deleted');
        }
        $table = new Libs\Tables\ConfigTable($this->inputVariables());
        $table->composeWeb($search);
        return $this->render('admin/listing.html.twig', [
            'controller_name' => 'ConfigsController',
            'title' => 'Configurations',
            'table' => $table,
        ]);
    }

    /**
     * @throws \kalanis\kw_forms\Exceptions\FormsException
     * @throws \kalanis\kw_mapper\MapperException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add()
    {
        $form = new Libs\Forms\ConfigForm('users');
        $form->composeEdit(null);
        $form->setInputs(new InputVarsAdapter($this->inputVariables()));
        if ($form->process()) {
            $who = new Libs\Mappers\ConfigRecord();
            $ex = new DataExchange($who);
            $ex->import($form->getValues());

            // todo: discus - redirect back or to edit?
            if ($who->save()) {
                return $this->fw();
            }
        }

        return $this->render('admin/users/edit.html.twig', [
            'controller_name' => 'ConfigsController',
            'page_title' => 'Add config',
            'form' => $form,
        ]);
    }

    /**
     * @param int $id
     * @throws \kalanis\kw_forms\Exceptions\FormsException
     * @throws \kalanis\kw_mapper\MapperException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(int $id)
    {
        $search = new Search(new Libs\Mappers\ConfigRecord());
        $search->exact('id', $id);
        if (!$search->getCount()) {
            return $this->fw();
        }
        $all = $search->getResults();
        $who = reset($all);

        $form = new Libs\Forms\ConfigForm('users');
        $form->composeEdit($who);
        $form->setInputs(new InputVarsAdapter($this->inputVariables()));
        if ($form->process()) {
            $ex = new DataExchange($who);
            $ex->import($form->getValues());

            // todo: discus - redirect back or stay here?
            if ($who->save()) {
                return $this->fw();
            }
        }

        return $this->render('admin/users/edit.html.twig', [
            'controller_name' => 'ConfigsController',
            'page_title' => 'Edit config',
            'form' => $form,
        ]);
    }

    /**
     * @param int $id
     * @throws \kalanis\kw_mapper\MapperException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(int $id)
    {
        $record = new Libs\Mappers\ConfigRecord();
        $record->id = $id;

        if (0 < $record->count()) {
            $record->load();
            $record->deleted = date('Y-m-d H:i:s');
            $record->save();
        }

        return $this->fw();
    }
}
