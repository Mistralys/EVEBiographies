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
}