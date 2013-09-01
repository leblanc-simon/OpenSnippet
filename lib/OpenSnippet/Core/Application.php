<?php

namespace OpenSnippet\Core;

use \OpenSnippet\App;

/**
 * Classe gérant la configuration de l'application
 *
 * @package     OpenSnippet
 * @subpackage  OpenSnippet\Core
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php MIT
 */
class Application
{
    static private $app = null;

    static public function run()
    {
        self::$app = new \Silex\Application();

        require_once __DIR__.'/../../../config/config.php';

        if (Config::get('debug', false) === true) {
            self::$app['debug'] = true;
        }

        self::register();

        self::routing();

        self::$app->run();
    }


    static private function routing()
    {
        $app = self::$app;

        // Index page
        $app->get('/', function() use ($app) {
            $controler = new App\Index($app);

            return $controler->defaultAction();
        })->bind('homepage');

        // Snippet page
        // - new snippet
        $app->get('/snippet/new', function () use ($app) {
            $controler = new App\Snippet($app);

            return $controler->newAction();
        })->bind('snippet_new');
        $app->post('/snippet/new', function () use ($app) {
            $controler = new App\Snippet($app);

            return $controler->insertAction();
        })->bind('snippet_new_post');
        // - show snippet
        $app->get('/snippet/{id}', function ($id) use ($app) {
            $controler = new App\Snippet($app);

            return $controler->defaultAction($id);
        })->bind('snippet');
        // - edit snippet
        $app->get('/snippet/{id}/edit', function ($id) use ($app) {
            $controler = new App\Snippet($app);

            return $controler->editAction($id);
        })->bind('snippet_update');
        $app->post('/snippet/{id}/edit', function ($id) use ($app) {
            $controler = new App\Snippet($app);

            return $controler->updateAction($id);
        })->bind('snippet_update_post');

        // Search
        $app->get('/search/{category_slug}', function ($category_slug) use ($app) {
            $controler = new App\Search($app);

            return $controler->categoryAction($category_slug);
        })->bind('search_category');
        $app->get('/search/{category_slug}/tags/{tags}', function ($category_slug, $tags) use ($app) {
            $controler = new App\Search($app);

            return $controler->categoryTagsAction($category_slug, $tags);
        })->bind('search_category_tags');
        $app->get('/search/tags/{tags}', function ($tags) use ($app) {
            $controler = new App\Search($app);

            return $controler->tagsAction($tags);
        })->bind('search_tags');
    }


    static private function register()
    {
        self::registerDoctrine();
        self::registerUrl();
        self::registerTwig();
    }


    static private function registerDoctrine()
    {
        self::$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => Config::get('database')
        ));
    }


    static private function registerUrl()
    {
        self::$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());
    }

    static private function registerTwig()
    {
        self::$app->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => __DIR__.'/../../../template/'.Config::get('template'),
        ));
    }
}