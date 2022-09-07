<?php

namespace App\Controller;


use kalanis\kw_address_handler\Forward;
use kalanis\kw_address_handler\Sources;
use kalanis\kw_input\Inputs;
use kalanis\kw_input\Interfaces\IFiltered;
use kalanis\kw_input\Filtered\Variables;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


abstract class AAppController extends AbstractController
{
    /**
     * Steps forward
     * @param string $defaultTarget
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function fw($defaultTarget = '/')
    {
        $fw = new Forward();
        $fw->setSource(new Sources\Inputs($this->inputVariables()));
        return $this->redirect($fw->has() ? $fw->get() : $defaultTarget);
    }

    /**
     * @return IFiltered
     * @todo: create class for transformation of _VARS from Symfony to kw_* so it shouldn't be necessary to use original inputs
     *    hint: Symfony\Component\HttpFoundation\Request -> $->request->get()
     */
    protected function inputVariables()
    {
        $inputs = new Inputs();
        $inputs->setSource([])->loadEntries();
        return new Variables($inputs);
    }
}
