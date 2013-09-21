<?php

namespace OpenSnippet\App;

use OpenSnippet\Core\Config;
use OpenSnippet\Model;


/**
 * Homepage controler
 *
 * @package     OpenSnippet
 * @subpackage  OpenSnippet\App
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php MIT
 */
class Index extends Base
{
    /**
     * Show the homepage
     *
     * @return  string      the template rendering
     */
    public function defaultAction()
    {
        // Get the last snippets
        $sql = 'SELECT * FROM snippet ORDER BY id DESC LIMIT ?';
        $datas = $this->db->fetchAll($sql, array((int)Config::get('nb_snippets', 5)));

        $snippets = array();
        foreach ($datas as $data) {
            $snippets[] = new Model\Snippet($this->db, $data);
        }

        return $this->render('Index/default.html.twig', array('snippets' => $snippets));
    }
}