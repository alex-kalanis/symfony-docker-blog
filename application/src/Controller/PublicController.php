<?php

namespace App\Controller;


use App\Libs;
use App\Libs\ArticlesListing;
use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources;
use kalanis\kw_confs\Config;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_paging\Positions;
use kalanis\kw_paging\Render\SimplifiedPager;
use kalanis\kw_table\core\Connector\PageLink;
use Michelf\MarkdownExtra\MarkdownExtra;


class PublicController extends AAppController
{
    /**
     * @throws \kalanis\kw_forms\Exceptions\FormsException
     * @throws \kalanis\kw_mapper\MapperException
     * @throws \kalanis\kw_table\core\TableException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listing()
    {
        $pager = new BasicPager();
        $paginator = new SimplifiedPager(new Positions($pager), new PageLink(new Handler(new Sources\Inputs($this->inputVariables())), $pager));
        $lib = new ArticlesListing($pager);
        $lib->maxAvailable();
        $pager->setLimit(10);
        $records = $lib->getAvailableArticles();
        $translation = new Libs\Translation(new MarkdownExtra());

        return $this->render('default.html.twig', [
            'controller_name' => 'AddressController',
            'page_title' => Config::get('core', 'core.page_title', 'This blog'),
            'articles_list' => Config::get('core', 'core.articles_list', 'Available articles'),
            'blog_title' => Config::get('core', 'core.blog_title', 'This dummy blog'),
            'articles' => $records,
            'translate' => $translation,
            'pager' => $paginator,
        ]);
    }

    /**
     * @param string $slug
     * @throws \kalanis\kw_mapper\MapperException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function single(string $slug)
    {
        $linked = new Libs\LinkTranslation();
        $who = $linked->getAsRecord($slug);
        if (empty($who)) {
            return $this->fw();
        }

        $translation = new Libs\Translation(new MarkdownExtra());
        return $this->render('single.html.twig', [
            'controller_name' => 'AddressController',
            'page_title' => Config::get('core', 'core.page_title', 'This blog'),
            'article_title' => $translation->title($who),
            'article_content' => $translation->full($who),
        ]);
    }
}
