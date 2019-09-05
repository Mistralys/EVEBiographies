<?php

namespace EVEBiographies;

abstract class DB_Item
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $data;

   /**
    * @var DB
    */
    protected $db;

   /**
    * @var DB_Collection
    */
    protected $collection;

   /**
    * @var boolean
    */
    protected $modified = false;

    public function __construct(DB_Collection $collection, int $id)
    {
        $this->collection = $collection;
        $this->id = $id;
        $this->db = $collection->getDB();
        $this->data = $this->db->fetchByPrimary($this->collection->getPrimaryPath(), $this->id);
    }

    /**
     * Retrieves the internal character ID (not the EVE one)
     * @return int
     */
    public function getID() : int
    {
        return $this->id;
    }

    protected function getDataKey($name, $default=null)
    {
        if(isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }

    protected function setDataKey($name, $value)
    {
        $current = $this->getDataKey($name);

        if($current !== $value) {
            $this->data[$name] = $value;
            $this->modified = true;
            return true;
        }

        return false;
    }

    public function save() : bool
    {
        if(!$this->modified) {
            return false;
        }

        $this->db->updateByPrimary($this->collection->getPrimaryPath(), $this->data);

        return true;
    }
}
