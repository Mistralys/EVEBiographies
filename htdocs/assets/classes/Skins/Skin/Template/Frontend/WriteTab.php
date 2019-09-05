<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template.php';

abstract class Skins_Skin_Template_Frontend_WriteTab extends Skins_Skin_Template
{
    /**
     * @var Website_Screen_Write
     */
    protected $screen;
    
    protected function isFullPage() : bool
    {
        return false;
    }

    protected function _renderBody()
    {
        $tpl = $this->skin->createTemplate('write');
        $tpl->addVar('tab-html', $this->_renderTab());
        $tpl->addVar('tabs', $this->getVar('tabs'));
        $tpl->addVar('thumbnail-elements', $this->getVar('thumbnail-elements'));
        $tpl->addVar('active-tab', $this->getVar('active-tab'));
        
        return $tpl->render();
    }
    
    abstract protected function _renderTab() : string;
    
   /**
    * @var \HTML_QuickForm2
    */
	protected $form;
	
   /**
    * @var string
    */
	protected $editorID;
	
   /**
    * @var Characters_Character
    */
	protected $character;
	
   /**
    * @var Biographies_Biography
    */
	protected $bio;
	
	protected function preRender()
	{
	    $this->form = $this->getVar('form');
	    $this->editorID = $this->getVar('editor-id');
	    $this->character = $this->screen->getCharacter();
	    $this->bio = $this->character->getBiography(); 
	}
}        