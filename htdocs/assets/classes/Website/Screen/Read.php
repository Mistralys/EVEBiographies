<?php

namespace EVEBiographies;

class Website_Screen_Read extends Website_Screen
{
   /**
    * @var Characters_Character
    */
    protected $bioCharacter;

   /**
    *
    * @var Biographies_Biography
    */
    protected $biography;

    protected function _start()
    {
        $this->load();
    }

    public function requiresAuthentication()
    {
        return true;
    }

    protected function load()
    {
        if(isset($this->biography)) {
            return $this->biography;
        }

        if(!isset($_REQUEST['char'])) {
            $this->redirect($this->getScreenURL('Nexus'));
        }

        $chars = $this->website->createCharacters();

        if(!$chars->slugExists($_REQUEST['char'])) {
            $this->redirect($this->getScreenURL('Nexus'));
        }

        $this->bioCharacter = $chars->getBySlug($_REQUEST['char']);
        $this->biography = $this->bioCharacter->getBiography();
        
        // count the view if it's not the owner
        if($this->bioCharacter !== $this->character) 
        {
            $this->startTransaction();
            $this->biography->incrementViews();
            $this->biography->save();
            $this->endTransaction();
        }
    }

    public function getBioCharacter()
    {
        $this->load();
        return $this->bioCharacter;
    }
    public function getBiography()
    {
        $this->load();
        return $this->biography;
    }

    protected function getSkinID()
    {
        return $this->getBiography()->getSkinID();
    }

    public function getPageTitle()
    {
        return t('Biography:').' '.$this->getBioCharacter()->getName();
    }

    public function getNavigationTitle()
    {
        return t('Biography');
    }

    public function getDispatcher()
    {
        return 'read.php';
    }

    public function getPrettyDispatcher()
    {
        return 'read/{char}';
    }

    protected function _render()
    {
        $tpl = $this->skin->createTemplate('read');
        $tpl->addVars(array(
            'character' => $this->bioCharacter,
            'biography' => $this->biography
        ));
        return $tpl->render();
    }
}
