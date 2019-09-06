<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Email.php';

class Template_Website_mailDelete extends Skins_Skin_Template_Email
{
   /**
    * @var Characters_Character
    */
    protected $character;
    
   /**
    * @var Biographies_Biography
    */
    protected $biography;
    
   /**
    * @var bool
    */
    protected $isInitialPublish = false;
    
    protected function _renderBody()
    {
        $this->character = $this->getVar('target-character');
        $backupData = $this->getVar('backup-data');
        
        ob_start();
        
        ?>
        	<p>
        		<b>Target character:</b> <?php echo $this->character->getName() ?>
        	</p>
        	<p>
        		<b>Backup data:</b>
    		</p>
    		<pre style="border:solid 2px #ddd;border-radius:6px;padding:13px">
        		<?php print_r($backupData) ?>
        	</pre>
        <?php 
        
        return ob_get_clean();
    }
}