<?php

namespace OpenSnippet\App;

use OpenSnippet\Model;

class Snippet extends Base
{
    public function defaultAction($id)
    {
        $snippet = new Model\Snippet($this->db, $id);

        if ($snippet->getId() === null) {
            throw new \Exception('Snippet doesn\'t exist', 404);
        }

        return $this->render('Snippet/default.html.twig', array('snippet' => $snippet, 'current_category' => $snippet->getCategoryId()));
    }


    public function newAction()
    {
        $categories = $this->db->fetchAll('SELECT * FROM category ORDER BY name');
        $tags = $this->db->fetchAll('SELECT * FROM tag ORDER BY name');

        return $this->render('Snippet/new.html.twig', array('categories' => $categories, 'tags' => $tags, 'dont_show_search' => true));
    }


    public function insertAction()
    {
        if ($this->request->getMethod() !== 'POST') {
            throw new \InvalidArgumentException('only post is allowed', 400);
        }

        list($name, $category_id, $value, $tags) = $this->getDatas();

        $snippet = new Model\Snippet($this->db, array('name' => $name, 'category_id' => $category_id, 'value' => $value));
        $snippet->setSnippetId(null);
        $snippet->setVersion(1);
        $snippet->save();

        foreach ($tags as $tag) {
            $tag = trim($tag);
            if (empty($tag) === true) {
                continue;
            }
            $snippet->addTag($tag);
        }

        return $this->app->redirect($this->url->generate('homepage'));
    }


    public function editAction($id)
    {
        $categories = $this->db->fetchAll('SELECT * FROM category ORDER BY name');
        $tags = $this->db->fetchAll('SELECT * FROM tag ORDER BY name');

        $snippet = new Model\Snippet($this->db, $id);

        if ($snippet->getId() === null) {
            throw new \Exception('Snippet doesn\'t exist', 404);
        }

        return $this->render('Snippet/edit.html.twig', array('snippet' => $snippet, 'categories' => $categories, 'tags' => $tags, 'dont_show_search' => true));
    }


    public function updateAction($id)
    {
        if ($this->request->getMethod() !== 'POST') {
            throw new \InvalidArgumentException('only post is allowed', 400);
        }

        $snippet = new Model\Snippet($this->db, $id);

        if ($snippet->getId() === null) {
            throw new \Exception('Snippet doesn\'t exist', 404);
        }

        list($name, $category_id, $value, $tags) = $this->getDatas();

        $snippet->setName($name);
        $snippet->setCategoryId($category_id);
        $snippet->setValue($value);

        $snippet->save();

        $snippet->removeTags();

        foreach ($tags as $tag) {
            $tag = trim($tag);
            if (empty($tag) === true) {
                continue;
            }
            $snippet->addTag($tag);
        }

        return $this->app->redirect($this->url->generate('snippet', array('id' => $snippet->getId())));
    }


    private function getDatas()
    {
        $name = $this->request->get('name', '');
        $category_id = $this->request->get('category_id', 0);
        $value = $this->request->get('value');
        $tags = $this->request->get('tags', '');

        if (is_string($name) === false || empty($name) === true) {
            throw new \InvalidArgumentException('name must be a no empty string', 400);
        }
        if (is_numeric($category_id) === false || empty($category_id) === true) {
            throw new \InvalidArgumentException('category_id must be a no empty numeric', 400);
        }
        if (is_string($value) === false || empty($value) === true) {
            throw new \InvalidArgumentException('value must be a no empty string', 400);
        }
        if (is_string($tags) === false) {
            throw new \InvalidArgumentException('tags must be a string', 400);
        }

        return array(
            trim($name),
            (int)$category_id,
            trim($value),
            explode(',', preg_replace('/,$/', '', trim($tags))),
        );
    }
}