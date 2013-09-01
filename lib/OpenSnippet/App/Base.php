<?php

namespace OpenSnippet\App;

use OpenSnippet\Core\Config;

abstract class Base
{
    protected $app      = null;
    protected $request  = null;
    protected $url      = null;
    protected $db       = null;
    protected $twig     = null;

    protected $sidebar_categories = array();
    protected $sidebar_tags = array();

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
        $this->request = $app['request'];
        $this->url = $app['url_generator'];
        $this->db = $app['db'];
        $this->twig = $app['twig'];

        $this->getCategories();
        $this->getTags();
    }


    protected function render($view, $params = array())
    {
        return $this->twig->render($view, array_merge(
                                            $params, 
                                            array(
                                                'sidebar_categories' => $this->sidebar_categories, 
                                                'sidebar_tags' => $this->sidebar_tags
                                            )
                                        )
        );
    }


    protected function getCategories()
    {
        $this->sidebar_categories = $this->db->fetchAll('SELECT category.*, count(snippet.id) AS count
                                                         FROM snippet
                                                            INNER JOIN category
                                                                ON snippet.category_id = category.id
                                                         GROUP BY category.id
                                                         ORDER BY category.name');
    }


    protected function getTags()
    {
        $this->sidebar_tags = $this->db->fetchAll('SELECT tag.*, count(snippet.id) AS count
                                                   FROM snippet
                                                        INNER JOIN snippet_has_tag
                                                            ON snippet_has_tag.snippet_id = snippet.id
                                                        INNER JOIN tag
                                                            ON snippet_has_tag.tag_id = tag.id
                                                   GROUP BY tag.id
                                                   ORDER BY tag.name');
    }
}