<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Email.php';

class Template_Website_mailPublish extends Skins_Skin_Template_Email
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
        $this->character = $this->getVar('character');
        $this->biography = $this->getVar('biography');
        $this->isInitialPublish = $this->getVar('is-initial-publish');
        
        $preview = htmlspecialchars($this->biography->getText());
        $preview = nl2br($preview, true);
        
        ob_start();
        
        ?>
        	<p>
        		<b>Character:</b> <?php echo $this->character->getName() ?>
        	</p>
        	<p>
        		<b>State:</b> <?php echo $this->biography->getPublishState() ?>
	       	</p>
        	<p>
        		<b>Preview:</b>
    		</p>
    		<div style="font-family:monospace;border:solid 2px #ddd;border-radius:6px;padding:13px">
        		<?php echo $preview ?>
        	</div>
        	<p>
        		<b>Admin actions:</b>
        	</p>
        	<p>
        		<?php
        		  if(!$this->biography->isBlocked()) {
    		        ?>
        				<a href="<?php echo $this->biography->getBlockURL() ?>">Block the biography</a><br/>
    				<?php 
        		  } else {
        		      ?>
        				<a href="<?php echo $this->biography->getUnblockURL() ?>">Unblock the biography</a><br/>
    				<?php
        		  }
				?>
        	</p>
        <?php 
        
        return ob_get_clean();
    }
}