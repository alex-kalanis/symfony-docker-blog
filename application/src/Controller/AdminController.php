<?php

namespace App\Controller;


use App\Libs;
use kalanis\kw_mapper\Search\Search;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class AdminController extends AAppController
{
    public function listing(): Response
    {
        return $this->render('admin/listing.html.twig', [
            'controller_name' => 'AdminController',
            'title' => 'Admin',
            'table' => '',
        ]);
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @throws \kalanis\kw_forms\Exceptions\FormsException
     * @throws \kalanis\kw_mapper\MapperException
     * @throws \kalanis\kw_table\core\TableException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logs(): Response
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
