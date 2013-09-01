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

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
        $this->request = $app['request'];
        $this->url = $app['url_generator'];
        $this->db = $app['db'];
        $this->twig = $app['twig'];
    }
}