<?php

namespace OpenSnippet\Model;


/**
 * Snippet database management
 *
 * @package     OpenSnippet
 * @subpackage  OpenSnippet\Model
 * @author      Simon Leblanc <contact@leblanc-simon.eu>
 * @license     http://www.opensource.org/licenses/bsd-license.php MIT
 */
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

    /**
     * Retrieve a snippet by id
     *
     * @param   int     $id     The id of the snippet to retrieve
     * @return  Snippet         The snippet found
     */
    public function retrieveByPK($id)
    {
        $row = $this->db->fetchAssoc('SELECT * FROM snippet WHERE id = ?', array((int)$id));
        if ($row === false) {
            throw new \Exception('Impossible to find the snippet');
        }

        return $this->populate($row);
    }


    /**
     * Populate the Snippet object with the datas
     *
     * @param   array   $data   The datas to use for populate the snippet
     * @return  self
     */
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


    /**
     * Save the Snippet object into the database
     *
     * @return  self
     */
    public function save()
    {
        if ($this->id === null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }


    /**
     * Insert the Snippet object into the database
     *
     * @return  self
     * @access  private
     */
    private function insert()
    {
        $sql = 'INSERT INTO snippet (id, snippet_id, version, name, category_id, value) VALUES (?, ?, ?, ?, ?, ?);';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, null, \PDO::PARAM_NULL);
        $stmt->bindValue(2, null, \PDO::PARAM_NULL);
        $stmt->bindValue(3, $this->getVersion(), \PDO::PARAM_INT);
        $stmt->bindValue(4, $this->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(5, $this->getCategoryId(), \PDO::PARAM_INT);
        $stmt->bindValue(6, $this->getValue(), \PDO::PARAM_STR);

        $stmt->execute();
        $this->setId($this->db->lastInsertId());

        return $this;
    }


    /**
     * Update the Snippet object into the database
     *
     * @return  self
     * @access  private
     */
    private function update()
    {
        $sql = 'UPDATE snippet SET name = ?, category_id = ?, value = ? WHERE id = ?;';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $this->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(2, $this->getCategoryId(), \PDO::PARAM_INT);
        $stmt->bindValue(3, $this->getValue(), \PDO::PARAM_STR);
        $stmt->bindValue(4, $this->getId(), \PDO::PARAM_INT);

        $stmt->execute();

        return $this;
    }


    /**
     * Get the category associated to the snippet
     *
     * @return  array   An array with the value of the category
     */
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


    /**
     * Get the tags associated to the snippet
     *
     * @return  array   An array of array with the value of the tags
     */
    public function getTags()
    {
        $tags = $this->db->fetchAll('SELECT tag.* 
                                     FROM tag 
                                        INNER JOIN snippet_has_tag 
                                            ON tag.id = snippet_has_tag.tag_id 
                                     WHERE snippet_has_tag.snippet_id = ?
                                     ORDER BY tag.name', array((int)$this->id));

        return $tags;
    }


    /**
     * Remove all tags associated with the snippet
     *
     * @return  self
     */
    public function removeTags()
    {
        if ($this->getId() === null) {
            return $this;
        }

        $this->db->executeQuery('DELETE FROM snippet_has_tag WHERE snippet_id = ?', array((int)$this->getId()), array(\PDO::PARAM_INT));

        return $this;
    }


    /**
     * Add a tag associated with the snippet
     */
    public function addTag($tag)
    {
        if ($this->getId() === null) {
            return false;
        }

        // Check if the tags already exist
        $row = $this->db->fetchAssoc('SELECT * FROM tag WHERE name = ?', array((string)$tag));
        if ($row === false) {
            // Build slug
            $base_slug = \OpenSnippet\Core\String::labelize($tag);
            $iterator = 0;
            do {
                $test_slug = $iterator === 0 ? $base_slug : $base_slug.'_'.($iterator);
                $row = $this->db->fetchAssoc('SELECT * FROM tag WHERE slug = ?', array($test_slug));
                if ($row === false) {
                    $slug = $test_slug;
                }
                $iterator++;
            } while (isset($slug) === false);

            $this->db->executeQuery('INSERT INTO tag (name, slug) VALUES (?, ?)', array($tag, $slug), array(\PDO::PARAM_STR, \PDO::PARAM_STR));
            $id = $this->db->lastInsertId();

            $row = array(
                'id' => $id,
                'name' => $name,
                'slug' => $slug,
            );
        }

        $this->db->executeQuery('INSERT INTO snippet_has_tag (snippet_id, tag_id) VALUES (?, ?)', array((int)$this->getId(), (int)$row['id']));
    }


    /**
     * Return the snippet's code in highlighter
     *
     * @return  string      The highlihter code
     */
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
        if ($v !== null && is_numeric($v) === false) {
            throw new \InvalidArgumentException('the value must be a numeric');
        }

        if ($v === null) {
            $this->snippet_id = null;
        } else {
            $this->snippet_id = (int)$v;
        }
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