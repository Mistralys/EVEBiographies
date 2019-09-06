<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_report extends Skins_Skin_Template_Frontend
{
   /**
    * @var Characters_Character
    */
    protected $targetCharacter;
    
    protected function _renderContent()
    {
        $this->targetCharacter = $this->getVar('target-character');
        
        ob_start();
        
        ?>
            <p>
            	<?php pts('Please describe the issue with %1$s\'s biography.', '<b>'.$this->targetCharacter->getName().'</b>') ?> 
            </p>
        <?php 
        echo $this->renderForm($this->getVar('form'));
        
		return ob_get_clean();
	}
}        