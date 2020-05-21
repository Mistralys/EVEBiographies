<?php

namespace EVEBiographies;

class Characters extends DB_Collection
{
    const ERROR_UNKNOWN_FOREIGN_ID = 30601;

    const ERROR_UNKNOWN_SLUG = 30602;

    public function getTableName()
    {
        return 'characters';
    }

    public function getPrimaryName()
    {
        return 'character_id';
    }

    public function getRecordClass()
    {
        return 'Characters_Character';
    }

    public function createNew($foreignID, $name) : Characters_Character
    {
        $bios = $this->website->createBiographies();
        $bio = $bios->createNew();

        return $this->createRecord(array(
            'foreign_id' => $foreignID,
            'name' => $name,
            'biography_id' => $bio->getID(),
            'date_added' => date('Y-m-d H:i:s'),
            'slug' => $this->generateSlug($name)
        ));
    }

    public function generateSlug($name)
    {
        return \AppUtils\ConvertHelper::transliterate($name, '-', false);
    }

    public function foreignIDExists($foreignID)
    {
        $id = $this->getIDByForeignID($foreignID);
        return $id !== null;
    }

    public function getIDByForeignID($foreignID)
    {
        return $this->db->fetchKey($this->getTableName(), $this->getPrimaryName(), array('foreign_id' => $foreignID));
    }

   /**
    * Retrieves a character by its EVE Online ID.
    * 
    * @param string $foreignID
    * @throws Website_Exception
    * @return \EVEBiographies\Characters_Character
    */
    public function getByForeignID($foreignID)
    {
        $id = $this->getIDByForeignID($foreignID);
        if($id !== null) {
            return $this->getByID($id);
        }

        throw new Website_Exception(
            'Cannot find character: unknown foreign ID.',
            self::ERROR_UNKNOWN_FOREIGN_ID,
            sprintf('Tried finding foreign ID [%s].', $foreignID)
        );
    }

    public function slugExists($slug)
    {
        return $this->getIDBySlug($slug) !== null;
    }

    public function getIDBySlug($slug)
    {
        return $this->db->fetchKey(
            $this->getTableName(),
            $this->getPrimaryName(),
            array(
                'slug' => $slug
            )
        );
    }

   /**
    * Retrieves a character by its request slug name.
    *
    * @param string $slug
    * @throws Website_Exception
    * @return \EVEBiographies\Characters_Character
    */
    public function getBySlug($slug)
    {
        $id = $this->getIDBySlug($slug);
        if($id) {
            return $this->getByID($id);
        }

        throw new Website_Exception(
            'Unknown character slug.',
            self::ERROR_UNKNOWN_SLUG,
            sprintf(
                'Tried fetching slug [%s].',
                $slug
            )
        );
    }
}
