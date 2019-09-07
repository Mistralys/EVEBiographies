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
    public function getPublished($offset, $perPage)
    {
        $query = sprintf(
            "SELECT
                COUNT(char.name) AS amount
            FROM
                characters AS char
            LEFT JOIN
                biographies AS bio
            ON
                char.biography_id = bio.biography_id
            WHERE
                bio.published = 1
            AND
                char.blocked = 0",
            $offset, $perPage
        );
        
        $entry = $this->db->fetchOneQuery($query);

        $total = $entry['amount'];
        
        $query = sprintf(
            "SELECT
                char.name,
                bio.biography_id
            FROM
                characters AS char
            LEFT JOIN
                biographies AS bio
            ON
                char.biography_id = bio.biography_id
            WHERE
                bio.published = 1
            AND
                char.blocked = 0 
            ORDER BY
                char.name ASC
            LIMIT 
                %s,%s",
            $offset, $perPage
        );
        
        $entries = $this->db->fetchAllQuery(
            $query
        );
        
        $bios = array();
        foreach($entries as $entry) {
            $bios[] = $this->getByID($entry['biography_id']);
        }
        
        return array(
            'total' => $total,
            'items' => $bios
        );
    }
}