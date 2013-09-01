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

        return $this->twig->render('Snippet/default.html.twig', array('snippet' => $snippet));
    }
}