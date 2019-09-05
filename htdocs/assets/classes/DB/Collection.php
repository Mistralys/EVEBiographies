<?php

namespace EVEBiographies;

abstract class DB_Collection
{
   /**
    * @var DB
    */
    protected $db;
    
   /**
    * @var Website
    */
    protected $website;
    
    public function __construct(Website $website)
    {
        $this->website = $website;
        $this->db = $website->createDB();
        
        $this->init();
    }
    
    protected function init()
    {
        
    }
    
    abstract public function getTableName();
    
    abstract public function getPrimaryName();
    
    abstract public function getRecordClass();
    
    public function getWebsite() : Website
    {
        return $this->website;
    }
    
    public function getDB() : DB
    {
        return $this->db;
    }
    
    public function getPrimaryPath()
    {
        return $this->getTableName().'.'.$this->getPrimaryName();
    }
    
   /**
    * Creates a new record, and returns the instance.
    * @param array $data
    * @return DB_Item
    */
    protected function createRecord($data) : DB_Item
    {
        $this->db->requireTransaction('Create a new '.$this->getRecordClass());
        
        $id = $this->db->insert($this->getTableName(), $data);
        return $this->getByID($id);
    }

    /**
     * @var DB_Item[]
     */
    protected $knownRecords = array();
    
    public function getByID($id) : DB_Item
    {
        if(!isset($this->knownRecords[$id])) {
            $class = 'EVEBiographies\\'.$this->getRecordClass();
            $this->knownRecords[$id] = new $class($this, $id);
        }
        
        return $this->knownRecords[$id];
    }
    
    public function idExists($id) : bool
    {
        if(empty($id)) {
            return false;
        }
        
        $primary = $this->getPrimaryName();
        
        $result = $this->db->fetchKey(
            $this->getTableName(), 
            $primary, 
            array(
                $primary => $id
            )
        );
        
        return $result == $id;
    }
}