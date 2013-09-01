<?php

namespace OpenSnippet\Model;

class Snippet
{
    private $db = null;

    private $id             = null;
    private $snippet_id     = null;
    private $version        = null;
    private $category_id    = null;
    private $name           = null;
    private $value          = null;

    private $category = null;

    public function __construct($db, $data = null)
    {
        $this->db = $db;

        if (is_numeric($data) === true) {
            $this->retrieveByPK($data);
        } elseif (is_array($data) === true) {
            $this->populate($data);
        }
    }

    public function retrieveByPK($id)
    {
        $row = $this->db->fetchAssoc('SELECT * FROM snippet WHERE id = ?', array((int)$id));
        if ($row === false) {
            throw new \Exception('Impossible to find the snippet');
        }
        
        return $this->populate($row);
    }


    public function populate($data)
    {
        if (isset($data['id']) === true) {
            $this->setId($data['id']);
        }
        if (isset($data['snippet_id']) === true) {
            $this->setSnippetId($data['snippet_id']);
        }
        if (isset($data['version']) === true) {
            $this->setVersion($data['version']);
        }
        if (isset($data['category_id']) === true) {
            $this->setCategoryId($data['category_id']);
        }
        if (isset($data['name']) === true) {
            $this->setName($data['name']);
        }
        if (isset($data['value']) === true) {
            $this->setValue($data['value']);
        }

        return $this;
    }

    public function save()
    {
        if ($this->id === null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    private function insert()
    {

    }

    private function update()
    {

    }


    public function getCategory()
    {
        if ($this->category === null) {
            $category = $this->db->fetchAssoc('SELECT * FROM category WHERE id = ?', array((int)$this->category_id));
            if ($category !== false) {
                $this->category = $category;
            }
        }

        return $this->category;
    }


    public function getTags()
    {
        $tags = $this->db->fetchAll('SELECT tag.* 
                                     FROM tag 
                                        INNER JOIN snippet_has_tag 
                                            ON tag.id = snippet_has_tag.tag_id 
                                     WHERE snippet_has_tag.snippet_id = ?', array((int)$this->id));

        return $tags;
    }


    public function getValueSyntaxHighLighter()
    {
        $category = $this->getCategory();
        if ($category === null) {
            return $this->getValue();
        }

        $geshi = new \GeSHi($this->getValue(), $category['slug']);
        return $geshi->parse_code();
    }

    ################################################
    #
    #       Getter and Setter
    #
    ################################################

    public function setId($v)
    {
        if (is_numeric($v) === false) {
            throw new \InvalidArgumentException('the value must be a numeric');
        }

        $this->id = (int)$v;
    }

    public function setSnippetId($v)
    {
        if (is_numeric($v) === false) {
            throw new \InvalidArgumentException('the value must be a numeric');
        }

        $this->snippet_id = (int)$v;
    }

    public function setVersion($v)
    {
        if (is_numeric($v) === false) {
            throw new \InvalidArgumentException('the value must be a numeric');
        }

        $this->version = (int)$v;
    }

    public function setCategoryId($v)
    {
        if (is_numeric($v) === false) {
            throw new \InvalidArgumentException('the value must be a numeric');
        }

        $this->category_id = (int)$v;
    }

    public function setName($v)
    {
        if (is_string($v) === false) {
            throw new \InvalidArgumentException('the value must be a string');
        }

        $this->name = (string)$v;
    }

    public function setValue($v)
    {
        if (is_string($v) === false) {
            throw new \InvalidArgumentException('the value must be a string');
        }

        $this->value = (string)$v;
    }

    public function getId() { return $this->id; }
    public function getSnippetId() { return $this->snippet_id; }
    public function getVersion() { return $this->version; }
    public function getCategoryId() { return $this->category_id; }
    public function getName() { return $this->name; }
    public function getValue() { return $this->value; }
}