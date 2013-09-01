<?php

namespace OpenSnippet\App;

use OpenSnippet\Model;

class Search extends Base
{
    public function categoryAction($slug)
    {
        $sql = 'SELECT snippet.*
                FROM snippet 
                    INNER JOIN category
                        ON snippet.category_id = category.id
                WHERE category.slug = ?
                ORDER BY snippet.id DESC';
        $datas = $this->db->fetchAll($sql, array((string)$slug));

        $snippets = array();
        foreach ($datas as $data) {
            $snippets[] = new Model\Snippet($this->db, $data);
        }

        if (count($snippets) > 0) {
            $current_category = $snippets[0]->getCategory();
        } else {
            $current_category = null;
        }


        return $this->render('Search/default.html.twig', array('snippets' => $snippets, 'current_category' => $current_category));
    }


    public function tagsAction($tags)
    {
        $tags = explode(',', $tags);

        $nb_tags = count($tags);
        $tag_in = implode(',', array_fill(0, $nb_tags, '?'));

        $sql = 'SELECT snippet.*
                FROM snippet
                    INNER JOIN snippet_has_tag
                        ON snippet.id = snippet_has_tag.snippet_id
                    INNER JOIN tag
                        ON tag.id = snippet_has_tag.tag_id
                WHERE tag.slug IN ('.$tag_in.')
                GROUP BY snippet.id
                HAVING count(snippet_has_tag.tag_id) = ?
                ORDER BY snippet.id DESC';

        $stmt = $this->db->prepare($sql);
        $iterator = 0;
        foreach ($tags as $tag) {
            $stmt->bindValue(++$iterator, $tag, \PDO::PARAM_STR);
        }
        $stmt->bindValue(++$iterator, $nb_tags, \PDO::PARAM_INT);
        $stmt->execute();

        $datas = $stmt->fetchAll();

        $snippets = array();
        foreach ($datas as $data) {
            $snippets[] = new Model\Snippet($this->db, $data);
        }

        $search_tags = $this->db->fetchAll('SELECT * FROM tag WHERE slug in ('.$tag_in.')', $tags);

        return $this->render('Search/default.html.twig', array('snippets' => $snippets, 'search_tags' => $search_tags));
    }


    public function categoryTagsAction($slug, $tags)
    {
        $tags = explode(',', $tags);

        $nb_tags = count($tags);
        $tag_in = implode(',', array_fill(0, $nb_tags, '?'));

        $sql = 'SELECT snippet.*
                FROM snippet
                    INNER JOIN snippet_has_tag
                        ON snippet.id = snippet_has_tag.snippet_id
                    INNER JOIN tag
                        ON tag.id = snippet_has_tag.tag_id
                    INNER JOIN category
                        ON snippet.category_id = category.id
                WHERE category.slug = ? AND tag.slug IN ('.$tag_in.')
                GROUP BY snippet.id
                HAVING count(snippet_has_tag.tag_id) = ?
                ORDER BY snippet.id DESC';

        $stmt = $this->db->prepare($sql);
        
        $iterator = 0;

        $stmt->bindValue(++$iterator, $slug, \PDO::PARAM_STR);
        foreach ($tags as $tag) {
            $stmt->bindValue(++$iterator, $tag, \PDO::PARAM_STR);
        }
        $stmt->bindValue(++$iterator, $nb_tags, \PDO::PARAM_INT);
        $stmt->execute();

        $datas = $stmt->fetchAll();

        $snippets = array();
        foreach ($datas as $data) {
            $snippets[] = new Model\Snippet($this->db, $data);
        }

        if (count($snippets) > 0) {
            $current_category = $snippets[0]->getCategory();
        } else {
            $current_category = null;
        }

        $search_tags = $this->db->fetchAll('SELECT * FROM tag WHERE slug in ('.$tag_in.')', $tags);

        return $this->render('Search/default.html.twig', array(
                                'snippets' => $snippets, 
                                'current_category' => $current_category, 
                                'search_tags' => $search_tags)
        );
    }
}