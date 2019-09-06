<?php

namespace EVEBiographies;

class Characters_Character extends DB_Item
{
   /**
    * @var Characters
    */
    protected $collection;

    public function getForeignID() : int
    {
        return (int)$this->getDataKey('foreign_id');
    }

   /**
    * Retrieves the character name.
    * @return string
    */
    public function getName() : string
    {
        return $this->getDataKey('name');
    }

    public function getBiography() : Biographies_Biography
    {
        return $this->collection->getWebsite()->createBiographies()->getByID($this->getBiographyID());
    }

    public function getBiographyID() : int
    {
        return (int)$this->getDataKey('biography_id');
    }

    public function countLogins() : int
    {
        return (int)$this->getDataKey('amount_logins');
    }

    public function setLastLogin(\DateTime $time) : Characters_Character
    {
        $this->setDataKey('last_login', $time->format('Y-m-d H:i:s'));
        return $this;
    }

    public function incrementAmountLogins() : Characters_Character
    {
        $this->setDataKey('amount_logins', $this->countLogins()+1);
        return $this;
    }

    public function getReadURL()
    {
        return $this->getScreenURL('Read');
    }

    public function getWriteURL()
    {
        return $this->getScreenURL('Write');
    }

    public function getReportURL()
    {
        return $this->getScreenURL('Report', array('char' => $this->getSlug()));
    }

    public function getDeletePortraitURL()
    {
        return $this->getScreenURL(
            'Write',
            array('delete-portrait' => 'yes')
        );
    }

    protected function getScreenURL($screen, $params=array())
    {
        $params['char'] = $this->getSlug();
        return $this->collection->getWebsite()->getScreen()->getScreenURL($screen, $params);
    }

    public function getSlug()
    {
        return $this->getDataKey('slug');
    }

    public function setPortraitFileType($extension)
    {
        $this->setDataKey('portrait_filetype', $extension);
        return $this;
    }

    public function getPortraitFileType()
    {
        return $this->getDataKey('portrait_filetype');
    }

    public function getPortraitPath()
    {
        return sprintf(
            '%s/portraits/%s.%s',
            APP_ROOT,
            $this->getID(),
            $this->getPortraitFileType()
        );
    }

    public function getPortraitURL()
    {
        return sprintf(
            '%s/portraits/%s.%s',
            APP_URL,
            $this->getID(),
            $this->getPortraitFileType()
        );
    }

    public function hasPortrait()
    {
        return file_exists($this->getPortraitPath());
    }

    public function hasAcceptedTerms()
    {
        return $this->getDataKey('terms_accepted') == '1';
    }

    public function setTermsAccepted()
    {
        $this->setDataKey('terms_accepted', 1);
        return $this;
    }

    private $isAdmin;

    public function isAdmin()
    {
        if(!isset($this->isAdmin)) {
            $this->isAdmin = in_array($this->getName(), Website::getAdminNames());
        }
        
        return $this->isAdmin;
    }
}
