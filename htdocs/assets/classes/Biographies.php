<?php

namespace EVEBiographies;

class Biographies extends DB_Collection
{
    public function getTableName()
    {
        return 'biographies';
    }
    
    public function getPrimaryName()
    {
        return 'biography_id';
    }
    
    public function getRecordClass()
    {
        return 'Biographies_Biography';
    }
    
    public function createNew() : Biographies_Biography
    {
        return $this->createRecord(array(
            'text' => ''
        ));
    }
    
   /**
    * Retrieves all published biographies
    * @return \EVEBiographies\Biographies_Biography[]
    */
    public function getPublished()
    {
        $ids = $this->db->fetchAllKey(
            $this->getTableName(),
            'biography_id',
            array(
                'published' => '1'
            )
        );
        
        $result = array();
        
        foreach($ids as $id) {
            $result[] = $this->getByID($id);
        }
        
        return $result;
    }
}