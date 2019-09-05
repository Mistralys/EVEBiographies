<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_report extends Skins_Skin_Template_Frontend
{
   /**
    * @var Characters_Character
    */
    protected $character;
    
    protected function _renderContent()
    {
        $this->character = $this->getVar('character');
        
        ob_start();
        
        ?>
            <p>
            	<?php pts('Please describe the issue with %1$s\'s biography.', '<b>'.$this->character->getName().'</b>') ?> 
            </p>
            <p>
            	<?php 
            	   pts('Note:');
            	   pts('We may contact your for more details if needed.')
        	   ?>
            </p>
        <?php 
        echo $this->renderForm($this->getVar('form'));
        
		return ob_get_clean();
	}
}        