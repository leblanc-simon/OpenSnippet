<?php

namespace OpenSnippet\App;

use OpenSnippet\Core\Config;
use OpenSnippet\Model;

class Index extends Base
{
    public function defaultAction()
    {
        // Get the last snippets
        $sql = 'SELECT * FROM snippet ORDER BY id DESC LIMIT ?';
        $datas = $this->db->fetchAll($sql, array((int)Config::get('nb_snippets', 5)));

        $snippets = array();
        foreach ($datas as $data) {
            $snippets[] = new Model\Snippet($this->db, $data);
        }

        return $this->twig->render('Index/default.html.twig', array('snippets' => $snippets));
    }
}